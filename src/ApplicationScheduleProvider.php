<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App;

use PhoneBurner\SaltLite\App\Example\Message\ExampleMessage;
use PhoneBurner\SaltLite\Framework\MessageBus\Transport;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Messenger\Message\RedispatchMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Event\FailureEvent;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

/**
 * @codeCoverageIgnore
 */
#[AsSchedule('app')]
class ApplicationScheduleProvider implements ScheduleProviderInterface
{
    private Schedule|null $schedule = null;

    public function __construct(
        private readonly EventDispatcher $dispatcher,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function getSchedule(): Schedule
    {
        return $this->schedule ??= $this->configure();
    }

    private function configure(): Schedule
    {
        return new Schedule($this->dispatcher)->with(
            RecurringMessage::cron('@daily', new RedispatchMessage(
                new ExampleMessage('Scheduled Message'),
                Transport::ASYNC,
            )),
        )->onFailure(function (FailureEvent $event): void {
            $this->logger->error('Failed to run message "{message}"', [
                'message' => $event->getMessage(),
                'exception' => $event->getError(),
            ]);
        })->processOnlyLastMissedRun(true);
    }
}
