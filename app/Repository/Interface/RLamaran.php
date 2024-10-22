<?php

namespace App\Repository\Interface;
use App\Model\Lamaran;

interface RLamaran {
    public function insert(Lamaran $lamaran): Lamaran;
    public function delete(int $lamaranId): void;
    public function save(Lamaran $lamaran): Lamaran;
    public function update(Lamaran $lamaran): Lamaran;
    public function getLamaranByUserIdAndJobId(int $userId, int $jobId): ?Lamaran;
    public function getNumberOfApplicants(int $jobId): int;
    public function getApplicantsByLowonganId(int $jobId): array;
}