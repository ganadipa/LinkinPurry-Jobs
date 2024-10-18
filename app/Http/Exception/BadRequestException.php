<?php

namespace App\Http\Exception;

class BadRequestException extends HttpException
{
    public function __construct($message)
    {
        parent::__construct($message, 400);
    }
}


