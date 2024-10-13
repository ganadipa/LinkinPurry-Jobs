<?php

namespace App\Model;
use App\Util\Enum\UserRoleEnum;

class User {
    public int $user_id;
    public string $email;
    public string $password;
    public UserRoleEnum $role;
    public string $nama;

    public function __construct(int $user_id, string $email, string $password, UserRoleEnum $role, string $nama) {
        $this->user_id = $user_id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->nama = $nama;
    }
}

