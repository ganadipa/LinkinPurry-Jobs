<?php

namespace App\Validator;
use App\Validator\IValidator;
use App\Http\Exception\BadRequestException;

class PositiveNumericValidator implements IValidator {
    public static function validate(mixed $data): mixed {

        if (!(is_numeric($data))) {
            throw new BadRequestException("Job id must be a number.");
        }

        // id must be greater than 0
        if ($data > 0) {
            return $data;
        }

        throw new BadRequestException("Job id must be greater than 0.");
    }
}