<?php

namespace App\Repository\Interface;
use App\Model\Lowongan;

interface RLowongan {
    public function insert(Lowongan $lowongan): Lowongan;
    public function delete(int $lowonganId): bool;
    public function update(Lowongan $lowongan): Lowongan;
    public function getById(int $lowonganId): Lowongan;
    // public function getPaginatedJobs(int $page, int $limit, string $search, string $jenisPekerjaan, string $jenisLokasi): array;
    // public function countJobs(string $search, string $jenisPekerjaan, string $jenisLokasi): int;
}