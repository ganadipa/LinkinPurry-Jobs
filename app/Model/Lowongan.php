<?php

namespace App\Model;
use App\Util\Enum\JenisLokasiEnum;
use App\Util\Enum\JobTypeEnum;
use \DateTime;


class Lowongan {
    // key
    public ?int $lowongan_id;

    // foreign key to company detail (user_id)
    public int $company_id;

    public string $posisi;
    public string $deskripsi;
    public JobTypeEnum $jenis_pekerjaan;
    public JenisLokasiEnum $jenis_lokasi;
    public DateTime $created_at;
    public DateTime $updated_at;
    public bool $is_open = true;

    public function __construct(int $company_id, string $posisi, string $deskripsi, JobTypeEnum $jenis_pekerjaan, JenisLokasiEnum $jenis_lokasi, DateTime $created_at, DateTime $updated_at, int $lowongan_id = null, bool $is_open = true) {
        $this->lowongan_id = $lowongan_id;
        $this->company_id = $company_id;
        $this->posisi = $posisi;
        $this->deskripsi = $deskripsi;
        $this->jenis_pekerjaan = $jenis_pekerjaan;
        $this->jenis_lokasi = $jenis_lokasi;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->is_open = $is_open;
    }
}