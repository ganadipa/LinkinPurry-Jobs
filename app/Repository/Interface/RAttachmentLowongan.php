<?php

namespace App\Repository\Interface;
use App\Model\AttachmentLowongan;

interface RAttachmentLowongan {
    public function insert(AttachmentLowongan $attachmentLowongan): AttachmentLowongan;
    public function delete(int $attachmentId): void;
}