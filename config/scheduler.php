<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteSkeleton\Example\ExampleScheduleProvider;

return [
    'scheduler' => [
        'schedule_providers' => [
            ExampleScheduleProvider::class,
        ],
    ],
];
