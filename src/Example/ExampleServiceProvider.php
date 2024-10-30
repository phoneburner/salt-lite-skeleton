<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Example;

use PhoneBurner\SaltLite\Framework\Container\MutableContainer;
use PhoneBurner\SaltLite\Framework\Container\ServiceProvider;

class ExampleServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        // TODO: Implement register() method.
    }
}
