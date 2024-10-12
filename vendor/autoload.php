<?php

spl_autoload_register(function ($class) {
    // Replace backslashes with directory separators for the file path
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    
    if (strpos($class, 'Core') === 0) {
        $class = 'core' . substr($class, 4);
    } elseif (strpos($class, 'App') === 0) {
        $class = 'app' . substr($class, 3);
    }

    // Build the full path to the class file
    $file = __DIR__ . '/../' . $class . '.php';

    
    // Check if the file exists and include it
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class file for $class not found at: $file");
    }
});
