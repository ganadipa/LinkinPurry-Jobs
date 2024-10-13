<?php

namespace App\Model;

class AttachmentLowongan {
    public int $attachment_id;
    public int $lowongan_id;
    public string $file_path;

    public function __construct(int $attachment_id, int $lowongan_id, string $file_path) {
        $this->attachment_id = $attachment_id;
        $this->lowongan_id = $lowongan_id;
        $this->file_path = $file_path;
    }
}