<?php

interface RAttachmentLowongan {
    public function insert(AttachmentLowongan $attachmentLowongan): void;
    public function delete(int $attachmentId): void;
}