<?php

namespace App\Middleware;

interface IMiddleware {
    public function handle(): bool;
}