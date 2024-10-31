<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\App\Example\ExampleServiceProvider;
use PhoneBurner\SaltLite\Framework\App\AppServiceProvider;
use PhoneBurner\SaltLite\Framework\Cache\CacheServiceProvider;
use PhoneBurner\SaltLite\Framework\Console\ConsoleServiceProvider;
use PhoneBurner\SaltLite\Framework\Database\DatabaseServiceProvider;
use PhoneBurner\SaltLite\Framework\EventDispatcher\EventDispatcherServiceProvider;
use PhoneBurner\SaltLite\Framework\Http\HttpServiceProvider;
use PhoneBurner\SaltLite\Framework\Logging\LoggingServiceProvider;
use PhoneBurner\SaltLite\Framework\Mailer\MailerServiceProvider;
use PhoneBurner\SaltLite\Framework\MessageBus\MessageBusServiceProvider;
use PhoneBurner\SaltLite\Framework\Routing\RoutingServiceProvider;
use PhoneBurner\SaltLite\Framework\Scheduler\SchedulerServiceProvider;
use PhoneBurner\SaltLite\Framework\Storage\StorageServiceProvider;

return [
    'container' => [
        'service_providers' => [
            // Framework Service Providers
            AppServiceProvider::class,
            MessageBusServiceProvider::class,
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
