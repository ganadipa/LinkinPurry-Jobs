<?php

namespace App\Middleware;
use App\Http\Request;

/**
 * the method handle returns the information whether it should go to the next middleware or not.
 * @return true if it should, otherwise false
 */
interface IMiddleware {
    public function handle(Request $req): bool;
    public function getMessage(): string;
}