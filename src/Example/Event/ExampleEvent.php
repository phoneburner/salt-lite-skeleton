<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example\Event;

class ExampleEvent
{
    public function __construct(public readonly string $message)
    {
    }
}
