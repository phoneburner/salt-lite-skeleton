<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteFramework\App\AppServiceProvider;
use PhoneBurner\SaltLiteFramework\Cache\CacheServiceProvider;
use PhoneBurner\SaltLiteFramework\Console\ConsoleServiceProvider;
use PhoneBurner\SaltLiteFramework\Database\DatabaseServiceProvider;
use PhoneBurner\SaltLiteFramework\Http\HttpServiceProvider;
use PhoneBurner\SaltLiteFramework\Logging\LoggingServiceProvider;
use PhoneBurner\SaltLiteFramework\Router\RouterServiceProvider;

return [
    'service_providers' => [
        AppServiceProvider::class,
        ConsoleServiceProvider::class,
        LoggingServiceProvider::class,
        CacheServiceProvider::class,
        DatabaseServiceProvider::class,
        HttpServiceProvider::class,
        RouterServiceProvider::class,
    ],
];
