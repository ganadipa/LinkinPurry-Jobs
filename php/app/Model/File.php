<?php

namespace App\Model;

class File {
    // key 
    public string $absolutePath;

    public function __construct(
        public string $name,
        public string $extension,
        public string $mimeType,
        public int $size,
        public string $tmpPath
    ) {
    }
}