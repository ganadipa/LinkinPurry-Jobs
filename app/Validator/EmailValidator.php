<?php

namespace App\Validator;
use App\Validator\IValidator;
use App\Http\Exception\BadRequestException;

class EmailValidator implements IValidator  {
    public static function validate(mixed $data): mixed {
        $sanitized = filter_var($data, FILTER_SANITIZE_EMAIL);
        if (filter_var($sanitized, FILTER_VALIDATE_EMAIL)) {
            return $sanitized;
        }

        throw new BadRequestException("Email is not valid");
    }
}