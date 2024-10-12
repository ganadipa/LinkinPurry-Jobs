<?php

namespace App\Utils;

enum RequestMethodEnum: string {
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}