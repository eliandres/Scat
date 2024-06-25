<?php

namespace App\config;

class Dotenv {
    public static function load($path) {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s directorio no existe', $path));
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Directorio raíz
$envPath = __DIR__ . '/../.env';
Dotenv::load($envPath);

