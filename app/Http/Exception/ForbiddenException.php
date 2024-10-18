<?php

namespace App\Http\Exception;

class ForbiddenException extends HttpException
{
    public function __construct($message)
    {
        parent::__construct($message, 403);
    }
}


