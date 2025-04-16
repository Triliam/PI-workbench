<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
            'stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => env('MAIL_VERIFY_PEER', false),
                    'verify_peer_name' => env('MAIL_VERIFY_PEER_NAME', false),
                ],
            ],
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'faqfatecitapira@gmail.com'),
        'name' => env('MAIL_FROM_NAME', 'Faq Fatec'),
    ],
];
