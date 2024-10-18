<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example;

use PhoneBurner\SaltLiteFramework\Container\MutableContainer;
use PhoneBurner\SaltLiteFramework\Container\ServiceProvider;

class ExampleServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        // TODO: Implement register() method.
    }
}
