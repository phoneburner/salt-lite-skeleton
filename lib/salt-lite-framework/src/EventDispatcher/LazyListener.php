<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\EventDispatcher;

use Psr\Container\ContainerInterface;

class LazyListener
{
    public function __construct(
        private readonly ContainerInterface $container,
        public readonly string $listener_class,
        public readonly string|null $listener_method = null,
    ) {
    }

    public function __invoke(object $event): void
    {
        if ($this->listener_method === null) {
            $this->container->get($this->listener_class)($event);
        } else {
            $this->container->get($this->listener_class)->{$this->listener_method}($event);
        }
    }
}
