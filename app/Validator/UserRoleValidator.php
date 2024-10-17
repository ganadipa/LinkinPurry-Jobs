<?php

namespace App\Validator;
use App\Validator\IValidator;
use \Exception;
use App\Util\Enum\UserRoleEnum;

class UserRoleValidator implements IValidator {
    public static function validate(mixed $data): mixed {
        if (in_array($data, [UserRoleEnum::JOBSEEKER, UserRoleEnum::COMPANY])) {
            return $data;
        }
        try {
            $data = strtolower($data);
        } catch (Exception $e) {
            throw new Exception("Role is not valid");
        }
        
        if (in_array($data, [UserRoleEnum::JOBSEEKER->value, UserRoleEnum::COMPANY->value])) {
            return $data;
        }


        throw new Exception("Role is not valid");
    }
}