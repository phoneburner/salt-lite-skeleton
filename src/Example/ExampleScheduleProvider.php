<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example;

use PhoneBurner\SaltLiteSkeleton\Example\Job\ExampleJob;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('example')]
class ExampleScheduleProvider implements ScheduleProviderInterface
{
    private Schedule|null $schedule = null;

    public function __construct(
        private readonly EventDispatcher $dispatcher,
    ) {
    }

    #[\Override]
    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule($this->dispatcher))->with(
            RecurringMessage::cron('* * * * *', new ExampleJob('Scheduled Message')),
        );
    }
}
