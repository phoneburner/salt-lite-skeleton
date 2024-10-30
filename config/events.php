<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\App\Example\Event\ExampleEvent;
use PhoneBurner\SaltLite\App\Example\Listener\ExampleListener;
use PhoneBurner\SaltLite\Framework\EventDispatcher\Listener\LogEvent;
use PhoneBurner\SaltLite\Framework\Queue\Event\JobHandlingComplete;
use PhoneBurner\SaltLite\Framework\Queue\Event\JobHandlingFailed;
use PhoneBurner\SaltLite\Framework\Queue\Event\JobHandlingStart;
use Symfony\Component\Console\EventListener\ErrorListener;
use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageRetriedEvent;
use Symfony\Component\Messenger\Event\WorkerRateLimitedEvent;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\Messenger\Event\WorkerStoppedEvent;
use Symfony\Component\Messenger\EventListener\AddErrorDetailsStampListener;
use Symfony\Component\Messenger\EventListener\DispatchPcntlSignalListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnCustomStopExceptionListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnFailureLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMemoryLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMessageLimitListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnRestartSignalListener;
use Symfony\Component\Messenger\EventListener\StopWorkerOnTimeLimitListener;
use Symfony\Component\Scheduler\Event\FailureEvent;
use Symfony\Component\Scheduler\Event\PostRunEvent;
use Symfony\Component\Scheduler\Event\PreRunEvent;
use Symfony\Component\Scheduler\EventListener\DispatchSchedulerEventListener;

return [
    'events' => [
        'listeners' => [
            // Message Bus Events
            SendMessageToTransportsEvent::class => [],
            WorkerStartedEvent::class => [],
            WorkerRunningEvent::class => [],
            WorkerStoppedEvent::class => [],
            WorkerMessageReceivedEvent::class => [],
            WorkerMessageHandledEvent::class => [],
            WorkerMessageFailedEvent::class => [],
            WorkerMessageRetriedEvent::class => [],
            WorkerRateLimitedEvent::class => [],

            // Scheduler Events
            PreRunEvent::class => [
                LogEvent::class,
            ],

            PostRunEvent::class => [
                LogEvent::class,
            ],

            FailureEvent::class => [
                LogEvent::class,
            ],

            // Queue Job Events
            JobHandlingStart::class => [],
            JobHandlingComplete::class => [],
            JobHandlingFailed::class => [],

            // Application Listeners

            ExampleEvent::class => [
                ExampleListener::class,
            ],
        ],
        'subscribers' => [
            // Framework Subscribers
            // Console Subscribers
            ErrorListener::class,

            // Messenger Subscribers
            AddErrorDetailsStampListener::class,
            DispatchPcntlSignalListener::class,
//            SendFailedMessageForRetryListener::class,
//            SendFailedMessageToFailureTransportListener::class,
            StopWorkerOnCustomStopExceptionListener::class,
            StopWorkerOnFailureLimitListener::class,
            StopWorkerOnMemoryLimitListener::class,
            StopWorkerOnMessageLimitListener::class,
            StopWorkerOnRestartSignalListener::class,
            StopWorkerOnTimeLimitListener::class,

            // Scheduler Subscribers
            DispatchSchedulerEventListener::class,
        ],
    ],
];
