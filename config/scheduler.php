<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\App\ApplicationServiceProvider;

return [
    'scheduler' => [
        'schedule_providers' => [
           'default' => ApplicationServiceProvider::class,
        ],
    ],
];
