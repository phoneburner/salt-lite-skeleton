<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Util\Clock;

class StaticHighResolutionTimer implements HighResolutionTimer
{
    public function __construct(private int $now)
    {
    }

    public function now(): int
    {
        return $this->now;
    }
}
