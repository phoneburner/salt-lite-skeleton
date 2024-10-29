<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Queue\Event;

use PhoneBurner\SaltLiteFramework\Queue\Job;

class JobHandlingStart
{
    public function __construct(public readonly Job $job)
    {
    }
}
