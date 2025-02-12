<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App;

use PhoneBurner\SaltLite\Framework\App\App;
use PhoneBurner\SaltLite\Framework\Container\ServiceProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @codeCoverageIgnore
 */
class ApplicationServiceProvider implements ServiceProvider
{
    public static function bind(): array
    {
        return [];
    }

    #[\Override]
    public static function register(App $app): void
    {
        $app->set(
            ApplicationRouteProvider::class,
            static fn(App $app): ApplicationRouteProvider => new ApplicationRouteProvider(),
        );

        $app->set(
            ApplicationScheduleProvider::class,
            static fn(App $app): ApplicationScheduleProvider => new ApplicationScheduleProvider(
                $app->get(EventDispatcher::class),
                $app->get(LoggerInterface::class),
            ),
        );
    }
}
