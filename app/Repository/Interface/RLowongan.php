<?php

namespace App\Repository\Interface;
use App\Model\Lowongan;

interface RLowongan {
    public function insert(Lowongan $lowongan): Lowongan;
    public function delete(int $lowonganId): bool;
    public function update(int $lowonganId, Lowongan $lowongan): Lowongan;
    public function getById(int $lowonganId): Lowongan;
    public function getList(int $page, int $limit, ?string $posisi, ?string $jenisPekerjaan, ?string $jenisLokasi, ?string $search): array;
    public function getJobs(int $page, int $perPage, 
        string $q, array $jobType, array $locationType, string $sortOrder
    ): array;
    public function getJobsByCompany(int $companyId, int $page, int $perPage, 
        string $q, array $jobType, array $locationType, string $sortOrder
    ): array;

    public function getNumberOfJobsPostedByCompany(int $companyId): int;
    public function getNumberOfJobs(): int;
}