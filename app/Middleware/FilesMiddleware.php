<?php

namespace App\Middleware;
use App\Model\User;
use App\Http\Request;


class FilesMiddleware  implements IMiddleware{
    public function __construct(private mixed $names) {}


    public function handle(Request $req): bool {


        if (is_array($this->names)) {
            foreach ($this->names as $file) {
                $req->setPostValue($file, $_FILES[$file]);
            }
        } else {
            assert(is_string($this->names));
            $size = count($_FILES[$this->names]['name']);

            // Generate the wanted $files format
            $files = [];
            for ($i = 0; $i < $size; $i++) {
                $files[$i] = [
                    'name' => $_FILES[$this->names]['name'][$i],
                    'type' => $_FILES[$this->names]['type'][$i],
                    'tmp_name' => $_FILES[$this->names]['tmp_name'][$i],
                    'error' => $_FILES[$this->names]['error'][$i],
                    'size' => $_FILES[$this->names]['size'][$i]
                ];
            }

            for ($i = 0; $i < $size; $i++) {
                $req->setPostValue($this->names, $files);
            }
        }

        return true;
    }

    // Message will be used to send a message to the user if the middleware fails
    public function getMessage(): string {
        return "Files not found.";
    }
}