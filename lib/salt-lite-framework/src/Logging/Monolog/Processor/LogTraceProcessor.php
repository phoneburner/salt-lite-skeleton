<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Logging\Monolog\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use PhoneBurner\SaltLite\Framework\Logging\LogTrace;

class LogTraceProcessor implements ProcessorInterface
{
    public function __construct(private readonly LogTrace $log_trace)
    {
    }

    #[\Override]
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['log_trace'] ??= $this->log_trace->toString();
        return $record;
    }
}
