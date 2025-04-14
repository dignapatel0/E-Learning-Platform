<?php

$envPath = __DIR__ . '/../../.env'; // Make sure this points to the actual location

if (file_exists($envPath)) {

    $env = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($env as $line) {
        if (strpos(trim($line), '#') === 0 || !str_contains($line, '=')) continue;

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        $_ENV[$key] = $value;
    }
} else {
    die("<pre> .env file not found at: $envPath</pre>");
}
