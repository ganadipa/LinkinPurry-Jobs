<?php

namespace App\Validator;
use App\Validator\IValidator;
use App\Http\Exception\BadRequestException;

class PasswordValidator implements IValidator {
    public static function validate(mixed $data): mixed {

        if (!(is_string($data))) {
            throw new BadRequestException("Password must be a string.");
        }

        if (strlen($data) > 0) {
            return $data;
        }

        throw new BadRequestException("Password must be valid and cannot be empty.");
    }
}