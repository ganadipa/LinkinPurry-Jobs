<?php


namespace App\Http;

class Response
{
    protected $statusCode = 200;
    protected $headers = [];
    protected $body;

    public function __construct()
    {
        // Set the Content-Type header by default
        $this->addHeader('Content-Type', 'text/html');
    }

    public function setStatusCode(int $code): self
    {
        // Set the status code
        /**
         * @reference to https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
         */
        $this->statusCode = $code;
        return $this;
    }

    public function addHeader(string $name, string $value): self
    {
        
        /**
         * Most important HTTP headers:
         * 
         * 1. Content-Type: The MIME type of the body of the request (used with POST and PUT requests).
         * e.g. Content-Type: application/json, Content-Type: text/html
         *
         */

        // Add a header
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody(string $content): self
    {
        /**
         * The body of the response
         * can be a string, a resource, or an object.
         */
        $this->body = $content;
        return $this;
    }

    public function json(array $data): self
    {
        // Encode the data to JSON
        $json = json_encode($data);

        // If the encoding failed
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->setStatusCode(500)
                 ->setBody(json_encode([
                     'status' => 'error',
                     'message' => 'Failed to encode JSON.'
                 ]));
        } 
        
        // If the encoding was successful
        else {
            $this->setBody($json);
        }

        // Add the Content-Type header
        $this->addHeader('Content-Type', 'application/json');
        return $this;
    }

    public function redirect(string $url, int $statusCode = 302): self
    {
        // Redirect to the URL
        $this->setStatusCode($statusCode)
             ->addHeader('Location', $url);
        return $this;
    }

    public function send(): void
    {
        // Set status code
        http_response_code($this->statusCode);

        // Set headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Output body
        if ($this->body) {
            echo $this->body;
        }
    }
}
