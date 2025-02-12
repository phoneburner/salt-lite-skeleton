<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\App\ApplicationServiceProvider;

return [
    'container' => [
        'enable_deferred_service_registration' => true,
        'service_providers' => [
            ApplicationServiceProvider::class,
        ],
    ],
];
