<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Queue;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @api
 * @internal
 */
class BusJobDispatcher implements JobDispatcher
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {
    }

    #[\Override]
    public function dispatch(Job $job): void
    {
        $this->bus->dispatch($job);
    }
}
