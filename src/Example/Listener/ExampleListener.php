<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example\Listener;

use PhoneBurner\SaltLiteSkeleton\Example\Event\ExampleEvent;
use Psr\Log\LoggerInterface;

class ExampleListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ExampleEvent $event): void
    {
        $this->logger->info('Event Handled:' . $event->message);
    }
}
