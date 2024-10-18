<?php


namespace App\Http;
use App\Util\Enum\RequestMethodEnum;
use App\Model\User;

class Request
{
    private RequestMethodEnum $method;
    private string $uri;
    private ?string $matchedRoute = null;
    private array $queryParams = [];
    private array $post = [];
    private array $headers = [];
    private array $uriParams = [];
    private ?User $user;
    private array $session = [];

    public function __construct()
    {
        // Set the request method
        $this->method = RequestMethodEnum::from($_SERVER['REQUEST_METHOD']);

        // Set the request uri
        $this->setUri($_SERVER['REQUEST_URI']);

        // Set the query parameters
        $this->setQueryParams($_GET);

        // Set the post parameters
        $this->post = $_POST;

        // Set the headers
        $this->headers = getallheaders();

        // Set the session
        $this->session = $_SESSION;

        /**
         * Note that the uri params will be set by the router
         */
        $this->uriParams = [];

        /**
         * Also, the following will be set by the middleware:
         * 1. The user
         */
        $this->user = null;
    }

    public function getMethod(): string
    {
        return $this->method->value;
    }

    public function setUri(string $uri): void
    {
        $exUri = explode('?', $uri);

        $uri = $exUri[0];
        $params = $exUri[1] ?? [];

        $this->uri = $uri;
        $this->setQueryParams($params);
    }

    public function getUri(): string
    {
        return $this->uri;
    }


    /**
     * @param string|array $params
     * @return void
     */
    public function setQueryParams($params)
    {
        if (is_string($params)) {
            $params = explode('&', $params);

            foreach ($params as $param) {
                $exParam = explode('=', $param);

                $key = $exParam[0];
                $value = $exParam[1] ?? null;

                if (!empty($key)) {
                    $this->queryParams[$key] = $value;
                }

            }
        } else {
            $this->queryParams = array_merge($this->queryParams, $params);
        }

    }

    public function setMatchedRoute(string $route): void
    {
        $this->matchedRoute = $route;
    }

    public function getMatchedRoute(): ?string
    {
        return $this->matchedRoute;
    }

    public function setUriParams(array $params): void
    {
        $this->uriParams = $params;
    }

    public function getUriParams(): array
    {
        return $this->uriParams;
    }

    public function getUriParamsValue(string $key, $default = null)
    {
        return $this->uriParams[$key] ?? $default;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getQueryParam(string $key, $default = null)
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Get POST variables
     *
     * @return array|string
     */
    public function getPost($field = null, $default = null)
    {
        if ($field === null) {
            return $this->post;
        } else {
            return $this->post[$field] ?? $default;
        }
    }

    public function setPostValue(string $field, mixed $value) {
        $this->post[$field] = $value;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getSession(): array
    {
        return $this->session;
    }

    public function getSessionValue(string $key, $default = null)
    {
        return $this->session[$key] ?? $default;
    }


    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }
}