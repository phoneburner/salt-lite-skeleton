<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Logging;

use PhoneBurner\SaltLite\Framework\App\Environment;
use PhoneBurner\SaltLite\Framework\Container\MutableContainer;
use PhoneBurner\SaltLite\Framework\Container\ServiceProvider;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggingServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        $container->set(
            LoggerInterface::class,
            static function (ContainerInterface $container): LoggerInterface {
                return (new LoggerFactory())->make(
                    $container->get(Environment::class),
                    $container->get(LogTrace::class),
                );
            },
        );
    }
}
