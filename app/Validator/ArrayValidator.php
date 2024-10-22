<?php

namespace App\Validator;
use App\Validator\IValidator;

class ArrayValidator implements IValidator {
    public static function validate(mixed $data): mixed {
        if (is_array($data)) {
            return $data;
        } else {
            return null;
        }
    }
}