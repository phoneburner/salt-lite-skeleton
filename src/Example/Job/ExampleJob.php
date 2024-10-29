<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example\Job;

use PhoneBurner\SaltLiteFramework\Queue\Job;
use Psr\Log\LoggerInterface;

class ExampleJob implements Job
{
    public function __construct(public readonly string $message = 'Example Job Message')
    {
    }

    public function getMessage(): string
    {
        return $this->message . ' ' . \date('Y-m-d H:i:s');
    }

    public function __invoke(LoggerInterface $logger): void
    {
        $logger->notice($this->getMessage());
    }
}
