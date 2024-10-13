<?php

namespace App\Util;

enum RequestMethodEnum: string {
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}