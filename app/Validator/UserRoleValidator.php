<?php

namespace App\Validator;
use App\Validator\IValidator;
use App\Util\Enum\UserRoleEnum;
use App\Http\Exception\BadRequestException;

class UserRoleValidator implements IValidator {
    public static function validate(mixed $data): mixed {
        if (in_array($data, [UserRoleEnum::JOBSEEKER, UserRoleEnum::COMPANY])) {
            return $data;
        }
        try {
            $data = strtolower($data);
        } catch (Exception $e) {
            throw new BadRequestException("Role is not valid");
        }
        
        if (in_array($data, [UserRoleEnum::JOBSEEKER->value, UserRoleEnum::COMPANY->value])) {
            return $data;
        }


        throw new BadRequestException("Role is not valid");
    }
}