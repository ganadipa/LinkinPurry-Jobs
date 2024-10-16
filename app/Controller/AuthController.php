<?php

namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use Core\View;

class AuthController {
    public static function login(Request $req, Response $res): void {
        $html = View::render('/', 'HomeView', ['name' => 'John Doe']);
        $res->setBody($html);
        $res->send();
    }

}