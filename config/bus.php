<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\Framework\Queue\Job;
use PhoneBurner\SaltLite\Framework\Queue\JobMessageHandler;
use Symfony\Component\Mailer\Messenger\MessageHandler as EmailMessageHandler;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpTransport;

use function PhoneBurner\SaltLite\Framework\env;

return [
    'bus' => [
        'worker' => [
            'max_failures' => (int)env('SALT_WORKER_MAX_FAILURES') ?: 10,
            'max_messages' => (int)env('SALT_WORKER_MAX_MESSAGES') ?: 100,
            'max_memory_usage_bytes' => (int)env('SALT_WORKER_MAX_MEMORY') ?: 128 * 1024 * 1024,
            'time_limit_seconds' => (int)env('SALT_WORKER_TIME_LIMIT_SECONDS') ?: 300,
        ],
        'senders' => [
            Job::class => [AmqpTransport::class],
            SendEmailMessage::class => [AmqpTransport::class],
        ],
        'handlers' => [
            Job::class => [
                JobMessageHandler::class,
            ],
            SendEmailMessage::class => [
                EmailMessageHandler::class,
            ],
        ],
        'transports' => [
            AmqpTransport::class => [
                'dsn' => env('SALT_AMQP_DSN'),
            ],
        ],
    ],
];
