<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Example\EventListener;

use PhoneBurner\SaltLite\App\Example\Event\ExampleEvent;
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
