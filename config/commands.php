<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\Framework\Console\Command\InteractiveSaltShell;
use PhoneBurner\SaltLite\Framework\EventDispatcher\Command\ListEventListeners;
use PhoneBurner\SaltLite\Framework\Routing\Command\CacheRoutes;
use PhoneBurner\SaltLite\Framework\Routing\Command\ListRoutes;
use Symfony\Component\Mailer\Command\MailerTestCommand;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Scheduler\Command\DebugCommand as ScheduleDebugCommand;

return [
    'commands' => [
        // Framework Commands
        InteractiveSaltShell::class,
        ListRoutes::class,
        CacheRoutes::class,
        ListEventListeners::class,

        // Vendor Commands
        ConsumeMessagesCommand::class,
        ScheduleDebugCommand::class,
        MailerTestCommand::class,

        // Application Commands
    ],
];
