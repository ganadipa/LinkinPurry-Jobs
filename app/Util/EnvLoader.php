<?php

namespace App\Util;

class EnvLoader {
    public static function load(string $path): void {

        // If the file isn't there, throw an exception
        if (!file_exists($path)) {
            throw new \Exception(".env file not found at: $path");
        }

        // Extract the lines
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // For each line, insert into superglobal
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            $value = trim($value, '"');

            $_ENV[$key] = $value;
        }
    }
} 