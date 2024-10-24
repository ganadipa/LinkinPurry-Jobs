<?php

namespace App\Validator;

use App\Http\Exception\BadRequestException;
use App\Validator\IValidator;
use Exception;

class NotNullValidator implements IValidator {
    public static function validate(mixed $data): mixed {
        if ($data !== null) {
            return $data;
        } else {
            throw new BadRequestException('Field is required.');
        }
    }
}