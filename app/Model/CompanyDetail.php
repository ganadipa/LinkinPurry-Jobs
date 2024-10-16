<?php

namespace App\Model;

class CompanyDetail {
    // foreign key to user (user_id) (must be defined)
    public int $user_id;

    public string $lokasi;
    public string $about;

    public function __construct(int $user_id, string $lokasi, string $about) {
        $this->user_id = $user_id;
        $this->lokasi = $lokasi;
        $this->about = $about;
    }
}