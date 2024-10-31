<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\App\AppRouteProvider;
use PhoneBurner\SaltLite\App\Example\ExampleRouteProvider;

use function PhoneBurner\SaltLite\Framework\env;

use const PhoneBurner\SaltLite\Framework\APP_ROOT;

return [
    'routing' => [
        'route_cache' => [
            'enable' => (bool)env('SALT_ENABLE_ROUTE_CACHE'),
            'filepath' => APP_ROOT . '/storage/bootstrap/routes.cache.php',
        ],
        'route_providers' => [
            AppRouteProvider::class,
            ExampleRouteProvider::class,
        ],
    ],
];
