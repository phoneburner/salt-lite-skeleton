<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteSkeleton\AppRouteProvider;
use PhoneBurner\SaltLiteSkeleton\Example\ExampleRouteProvider;

use function PhoneBurner\SaltLiteFramework\env;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

return [
    'routing' => [
        'route_cache' => [
            'enable' => env('SALT_ENABLE_ROUTE_CACHE'),
            'filepath' => APP_ROOT . '/storage/bootstrap/routes.cache.php',
        ],
        'route_providers' => [
            AppRouteProvider::class,
            ExampleRouteProvider::class,
        ],
    ],
];
