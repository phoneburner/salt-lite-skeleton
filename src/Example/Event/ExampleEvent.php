<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Example\Event;

class ExampleEvent
{
    public function __construct(public readonly string $message)
    {
    }
}
