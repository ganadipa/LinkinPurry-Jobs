<?php

namespace App\Model;
use App\Util\Enum\JenisLokasiEnum;
use \DateTime;


class Lowongan {
    public int $lowongan_id;
    public int $company_id;
    public string $posisi;
    public string $deskripsi;
    public string $jenis_pekerjaan;
    public JenisLokasiEnum $jenis_lokasi;
    public bool $is_open = true;
    public DateTime $created_at;
    public DateTime $updated_at;

    public function __construct(int $lowongan_id, int $company_id, string $posisi, string $deskripsi, string $jenis_pekerjaan, JenisLokasiEnum $jenis_lokasi, bool $is_open = true, DateTime $created_at, DateTime $updated_at) {
        $this->lowongan_id = $lowongan_id;
        $this->company_id = $company_id;
        $this->posisi = $posisi;
        $this->deskripsi = $deskripsi;
        $this->jenis_pekerjaan = $jenis_pekerjaan;
        $this->jenis_lokasi = $jenis_lokasi;
        $this->is_open = $is_open;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}