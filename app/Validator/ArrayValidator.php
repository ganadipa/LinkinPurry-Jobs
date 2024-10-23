<?php

namespace App\Validator;

use App\Http\Exception\BadRequestException;
use App\Validator\IValidator;
use Exception;

class ArrayValidator implements IValidator {
    public static function validate(mixed $data): mixed {
        if (is_array($data)) {
            return $data;
        } else {
            throw new BadRequestException('Field must be an array.');
        }
    }
}