<?php

return [
    'mailers' => [
        'smtp' => [
            'host' => env('EMAIL_HOST', 'smtp.example.com'),
            'port' => env('EMAIL_PORT', '25'), // 25, 465 or 587
            'username' => env('EMAIL_USER', 'user@example.com'),
            'password' => env('EMAIL_PASSWORD', 'secret'),
        ],
    ],
    'from' => [
        'address' => env('EMAIL_FROM', 'noreply@example.com'),
        'name' => env('EMAIL_NAME', 'Example'),
    ],
];
