<?php

namespace App\Model;
use App\Util\Enum\UserRoleEnum;

class User {
    // key
    public ?int $user_id = null;

    public string $email;
    public string $password;
    public UserRoleEnum $role;
    public string $nama;

    public function __construct(string $email, string $password, UserRoleEnum $role, string $nama, int $user_id = null) {

        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->nama = $nama;
        $this->user_id = $user_id;
    }
}

