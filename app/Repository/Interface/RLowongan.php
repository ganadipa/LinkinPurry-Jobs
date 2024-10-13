<?php

namespace App\Repository\Interface;
use App\Model\Lowongan;

interface RLowongan {
    public function insert(Lowongan $lowongan): Lowongan;
    public function delete(int $lowonganId): Lowongan;
}