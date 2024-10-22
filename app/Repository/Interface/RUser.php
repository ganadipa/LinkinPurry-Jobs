<?php

namespace App\Repository\Interface;
use App\Model\User;

interface RUser {
    public function insert(User $user): User;
    public function delete(int $userId): void;
    public function findByEmail(string $email): ?User;
    public function getUserProfileById(int $userId): ?User;
    public function save(User $user): User;
}
