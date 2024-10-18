<?php

namespace App\Middleware;
use App\Model\User;
use App\Http\Request;


class FilesMiddleware  implements IMiddleware{
    public function __construct(private array $files) {}


    public function handle(Request $req): bool {

        foreach ($this->files as $file) {
            $req->setPostValue($file, $_FILES[$file]);
        }

        return true;
    }

    // Message will be used to send a message to the user if the middleware fails
    public function getMessage(): string {
        return "Files not found.";
    }
}