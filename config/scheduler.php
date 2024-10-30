<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\App\Example\ExampleScheduleProvider;

return [
    'scheduler' => [
        'schedule_providers' => [
            ExampleScheduleProvider::class,
        ],
    ],
];
