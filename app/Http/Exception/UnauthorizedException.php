<?php

namespace App\Http\Exception;

class UnauthorizedException extends HttpException
{
    public function __construct($message)
    {
        parent::__construct($message, 401);
    }
}


