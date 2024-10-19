<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteFramework\App\AppServiceProvider;
use PhoneBurner\SaltLiteFramework\Cache\CacheServiceProvider;
use PhoneBurner\SaltLiteFramework\Console\ConsoleServiceProvider;
use PhoneBurner\SaltLiteFramework\Database\DatabaseServiceProvider;
use PhoneBurner\SaltLiteFramework\Http\HttpServiceProvider;
use PhoneBurner\SaltLiteFramework\Logging\LoggingServiceProvider;
use PhoneBurner\SaltLiteFramework\Routing\RoutingServiceProvider;
use PhoneBurner\SaltLiteFramework\Storage\StorageServiceProvider;
use PhoneBurner\SaltLiteSkeleton\Example\ExampleServiceProvider;

return [
    'container' => [
        'service_providers' => [
            // Framework Service Providers
            AppServiceProvider::class,
            ConsoleServiceProvider::class,
            LoggingServiceProvider::class,
            CacheServiceProvider::class,
            DatabaseServiceProvider::class,
            HttpServiceProvider::class,
            RoutingServiceProvider::class,
            StorageServiceProvider::class,

            // Application Service Providers
            ExampleServiceProvider::class,
        ],
    ],
];
