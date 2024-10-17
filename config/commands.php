<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteFramework\Console\Command\InteractiveSaltShell;
use PhoneBurner\SaltLiteFramework\Routing\Command\CacheRoutes;
use PhoneBurner\SaltLiteFramework\Routing\Command\ListRoutes;

return [
    'commands' => [
        // Framework Commands
        InteractiveSaltShell::class,
        ListRoutes::class,
        CacheRoutes::class,

        // Application Commands
    ],
];
