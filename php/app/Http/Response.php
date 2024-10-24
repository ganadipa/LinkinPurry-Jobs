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

    public function pdf(string $path) {
        // Set the Content-Type header
        $this->addHeader('Content-Type', 'application/pdf');

        // Set the Content-Disposition header
        $this->addHeader('Content-Disposition', 'inline; filename="' . basename($path) . '"');

        // Set the body
        $this->setBody(file_get_contents($path));
        return $this;
    }

    public function video(string $path) {
        // Set the Content-Type header
        $this->addHeader('Content-Type', 'video/mp4');

        // Set the body
        $this->setBody(file_get_contents($path));
        return $this;
    }

    public function image(string $path) {
        // Set the Content-Type header
        $this->addHeader('Content-Type', 'image/jpeg');

        // Set the body
        $this->setBody(file_get_contents($path));
        return $this;
    }

    public function csv(string $csv_content, string $filename = "data.csv") {
        // Clear any previous output to prevent header issues
        if (ob_get_length()) {
            ob_end_clean();
        }
    
        // Set the Content-Type header to indicate CSV format
        $this->addHeader('Content-Type', 'text/csv; charset=utf-8');
    
        // Set the Content-Disposition header to prompt a download with the specified filename
        $this->addHeader('Content-Disposition', 'attachment; filename="' . basename($filename) . '"');
    
        // Optionally, set headers to prevent caching
        $this->addHeader('Pragma', 'no-cache');
        $this->addHeader('Expires', '0');
    
        // Set the body to the CSV content
        $this->setBody($csv_content);
    
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
