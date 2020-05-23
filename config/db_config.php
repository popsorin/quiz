<?php

return [
        'host' => 'localhost',
        'db' => 'quiz',
        'user' => 'sorinpop',
        'pass' => '123456789',
        'charset' => 'utf8mb4',
        "PDOOptions" => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        ],
    ];
