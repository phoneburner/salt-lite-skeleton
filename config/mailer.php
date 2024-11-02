<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\Framework\Mailer\TransportDriver;

use function PhoneBurner\SaltLite\Framework\env;

return [
    'mailer' => [
        'default_from_address' => env('SALT_MAILER_DEFAULT_FROM_ADDRESS') ?? 'nobody@example.com',
        'default_driver' => env('SALT_MAILER_DRIVER') ?: TransportDriver::Smtp->value,
        'async' => (bool)env('SALT_MAILER_ASYNC'),
        'drivers' => [
            TransportDriver::Smtp->value => [
                'host' => env('SALT_SMTP_HOST'),
                'port' => env('SALT_SMTP_PORT'),
                'user' => env('SALT_SMTP_USER'),
                'pass' => env('SALT_SMTP_PASS'),
                'encryption' => (bool)env('SALT_SMTP_SECURITY'),
                ],
            TransportDriver::SendGrid->value => [
                'api_key' => env('SALT_SENDGRID_API_KEY'),
            ],
        ],
    ],
];
