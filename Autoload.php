<?php

// Borrowed and adapted from the example PSR-4 Autoloader at
// https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md

spl_autoload_register(function ($class) {
    $prefix = 'App\\Utils\\';
    $base_dir = realpath(__DIR__.'/utils/');
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $relative_class = strtolower($relative_class);
    $last_slash = strrpos($relative_class, '\\');
    $mutated_class = substr($relative_class, 0, $last_slash + 1).ucfirst(substr($relative_class, $last_slash + 1));
    $file = $base_dir.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $mutated_class).'.php';

    if (file_exists($file)) {
        require $file;
    }
});

define('BANTAM', time());
