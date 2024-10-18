<?php

namespace Core;

use App\Util\Enum\RequestMethodEnum;
use App\Middleware\IMiddleware;
use App\Http\Request;
use App\Http\Response;

class Router {
    private array $routes = [];

    public function __construct() {
        $this->routes = [
            RequestMethodEnum::GET->value => [],
            RequestMethodEnum::POST->value => [],
            RequestMethodEnum::PUT->value => [],
            RequestMethodEnum::DELETE->value => [],
        ];
    }

    public function register(RequestMethodEnum $method, string $path, Callable $callback, array $middlewares = []): void {
        // Validate the middlewares
        $this->validateMiddlewares($middlewares);
        

        // Validate the path
        /**
         * Dynamic path segments are denoted by a colon followed by the name of the segment.
         * e.g. /users/:id
         * 
         * if it isn't valid, throw an exception
        */

        // Split the path into segments
        $segments = explode('/', $path);

        // Remove the first element because it will always be empty
        array_shift($segments);

        /**
         * The following code does:
         * 1. If it is dynamic segment, must be in the form of :name
         * 2. If it is static segment, must be alphanumeric
         */
        foreach ($segments as $segment) {
            if (strpos($segment, ':') === 0) {
                // This is a dynamic segment
                $segmentName = substr($segment, 1);
                if (empty($segmentName)) {
                    throw new \InvalidArgumentException("Invalid path: $path");
                }

                // Validate the segment name
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $segmentName)) {
                    throw new \InvalidArgumentException("Invalid path: $path");
                }
            } else {
                // This is a static segment
                if (empty($segment)) {

                    // print the segments as json
                    if (count($segments) == 1) {
                        continue;
                    }

                    throw new \InvalidArgumentException("Invalid path: $path");
                }

                // Also, validate the static segment
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $segment)) {
                    throw new \InvalidArgumentException("Invalid path: $path");
                }
            }
        }

    


        // Validate the HTTP method (GET, POST, PUT, DELETE)
        if (!in_array($method, [RequestMethodEnum::GET, RequestMethodEnum::POST, RequestMethodEnum::PUT, RequestMethodEnum::DELETE])) {
            throw new \InvalidArgumentException("Unsupported HTTP method: $method");
        }

        // Validated, register the route
        $this->routes[$method->value][$path]['CALLBACK'] = $callback;
        $this->routes[$method->value][$path]['MIDDLEWARES'] = $middlewares;
    }

    public function resolve(Request $req): void {
        // Prepare the response
        $res = new Response();


        // Validate the HTTP method
        $method = strtoupper($req->getMethod());
        if (!in_array($method, [RequestMethodEnum::GET->value, RequestMethodEnum::POST->value, RequestMethodEnum::PUT->value, RequestMethodEnum::DELETE->value])) {
            throw new \InvalidArgumentException("Unsupported HTTP method: $method");
        }

        $path = $req->getUri();

        // Is it static?
        if (isset($this->routes[$method][$path])) {

            /** Return the result of the callback, with the following will be the parameter of the callback:
             * 1. the first parameter is the url parameter
             * 2. the second parameter is the url query
             */
            $req->setMatchedRoute($path);
            $this->call($req, $res);
            return;

        }  

        // Not static, then prolly dynamic
        foreach ($this->routes[$method] as $route => $routeData) {
            // Split the route into segments
            $routeSegments = explode('/', $route);
            array_shift($routeSegments);

            // Split the path into segments
            $pathSegments = explode('/', $path);
            array_shift($pathSegments);

            // Check if the number of segments match
            if (count($routeSegments) != count($pathSegments)) {
                continue;
            }

            // Check if the segments match
            $params = [];
            $match = true;
            for ($i = 0; $i < count($routeSegments); $i++) {
                if (strpos($routeSegments[$i], ':') === 0) {
                    // This is a dynamic segment
                    $params[substr($routeSegments[$i], 1)] = $pathSegments[$i];
                } else {
                    // This is a static segment
                    if ($routeSegments[$i] != $pathSegments[$i]) {
                        $match = false;
                        break;
                    }
                }
            }

            
            if ($match) {
                // Found a match

                // Set the request's url parameters
                $req->setUriParams($params);

                // Set the matched route
                $req->setMatchedRoute($route);

                // Call the callback
                $this->call($req, $res);
                return;
            }
        }

        // No route found
        echo "404 - Not Found";
    }

    public function call(Request $req, Response $res): void {
        $method = $req->getMethod();
        $route = $req->getMatchedRoute();

        // Loop throguh all the middlewares
        foreach ($this->routes[$method][$route]['MIDDLEWARES'] as $middleware) {
            // Call the middleware
            $ok = $middleware->handle($req);
            if (!$ok) {
                return;
            }
        }

        // Call the callback
        $this->routes[$method][$route]['CALLBACK']($req, $res);
    }

    private function validateMiddlewares(array $middlewares): void {
        // For each middleware, check if it is the type IMiddleware
        foreach ($middlewares as $middleware) {
            if (!($middleware instanceof IMiddleware)) {
                throw new \InvalidArgumentException("Middleware must implement IMiddleware");
            }
        }
    }
}
