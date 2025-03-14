<?php

namespace App\Repository\Interface;
use App\Model\File;

interface RFile {
    public function save(File $file): File;
    public function delete(string $path): void;
}