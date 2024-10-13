<?php

namespace App\Repository\Interface;
use App\Model\User;

interface RUser {
    public function insert(User $user): User;
    public function delete(int $userId): User;
}
