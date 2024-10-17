<?php

namespace App\Validator;

interface IValidator {
    public static function validate(mixed $data): mixed;
}