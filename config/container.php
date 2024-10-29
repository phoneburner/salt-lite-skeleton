<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteFramework\App\AppServiceProvider;
use PhoneBurner\SaltLiteFramework\Bus\BusServiceProvider;
use PhoneBurner\SaltLiteFramework\Cache\CacheServiceProvider;
use PhoneBurner\SaltLiteFramework\Console\ConsoleServiceProvider;
use PhoneBurner\SaltLiteFramework\Database\DatabaseServiceProvider;
use PhoneBurner\SaltLiteFramework\EventDispatcher\EventDispatcherServiceProvider;
use PhoneBurner\SaltLiteFramework\Http\HttpServiceProvider;
use PhoneBurner\SaltLiteFramework\Logging\LoggingServiceProvider;
use PhoneBurner\SaltLiteFramework\Mailer\MailerServiceProvider;
use PhoneBurner\SaltLiteFramework\Routing\RoutingServiceProvider;
use PhoneBurner\SaltLiteFramework\Scheduler\SchedulerServiceProvider;
use PhoneBurner\SaltLiteFramework\Storage\StorageServiceProvider;
use PhoneBurner\SaltLiteSkeleton\Example\ExampleServiceProvider;

return [
    'container' => [
        'service_providers' => [
            // Framework Service Providers
            AppServiceProvider::class,
            BusServiceProvider::class,
            CacheServiceProvider::class,
            ConsoleServiceProvider::class,
            DatabaseServiceProvider::class,
            EventDispatcherServiceProvider::class,
            HttpServiceProvider::class,
            LoggingServiceProvider::class,
            MailerServiceProvider::class,
            RoutingServiceProvider::class,
            SchedulerServiceProvider::class,
            StorageServiceProvider::class,

            // Application Service Providers
            ExampleServiceProvider::class,
        ],
    ],
];
