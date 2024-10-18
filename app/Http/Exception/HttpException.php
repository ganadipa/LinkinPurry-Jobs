<?php

namespace App\Http\Exception;
use Exception;

abstract class HttpException extends Exception
{
    protected $statusCode;

    public function __construct($message, $statusCode)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}