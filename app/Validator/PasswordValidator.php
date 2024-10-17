<?php

namespace App\Validator;
use App\Validator\IValidator;
use \Exception;

class PasswordValidator implements IValidator {
    public static function validate(mixed $data): mixed {

        if (!(is_string($data))) {
            throw new Exception("Password must be a string.");
        }

        if (strlen($data) > 0) {
            return $data;
        }

        throw new Exception("Password must be valid and cannot be empty.");
    }
}