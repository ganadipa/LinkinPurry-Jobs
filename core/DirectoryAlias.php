<?php

namespace Core;

class DirectoryAlias {
    private static array $aliases = [];

    public static function set(string $alias, string $path): void {
        self::$aliases[$alias] = $path;
    }

    public static function get(string $alias): string {
        return self::$aliases[$alias];
    }
}