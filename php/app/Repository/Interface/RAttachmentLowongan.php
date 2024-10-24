<?php

namespace App\Repository\Interface;
use App\Model\AttachmentLowongan;

interface RAttachmentLowongan {
    public function insert(AttachmentLowongan $attachmentLowongan): AttachmentLowongan;
    public function delete(int $attachmentId): void;
    public function update(AttachmentLowongan $attachmentLowongan): AttachmentLowongan;
    public function save(AttachmentLowongan $attachmentLowongan): AttachmentLowongan;
    public function getById(int $attachmentId): ?AttachmentLowongan;
    public function getAttachmentsById(int $lowonganId): array;
    public function getList(): array;
    public function getAttachmentsByLowonganId(int $lowonganId): array;
    public function deleteByLowonganId(int $lowonganId): array;
}