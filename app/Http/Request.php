<?php


namespace App\Http;
use App\Util\Enum\RequestMethodEnum;
use App\Model\User;

class Request
{
    private RequestMethodEnum $method;
    private string $uri;
    private array $queryParams = [];
    private array $post = [];
    private array $headers = [];
    private array $uriParams = [];
    private User $user;

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

        /**
         * Note that the uri params will be set by the router
         */
        $this->uriParams = [];

        /**
         * Also, the following will be set by the middleware:
         * 1. The user
         */
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
        if (preg_match("/^\/.+/", $this->uri)) {
            $this->uri = substr($this->uri, 1);
        }

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

    public function setUriParams(array $params): void
    {
        $this->uriParams = $params;
    }

    public function getUriParams(): array
    {
        return $this->uriParams;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
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