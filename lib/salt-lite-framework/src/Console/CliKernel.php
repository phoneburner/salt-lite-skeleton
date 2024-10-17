<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Console;

use PhoneBurner\SaltLiteFramework\App\Kernel;
use Symfony\Component\Console\Application;

class CliKernel implements Kernel
{
    private const string APP_NAME = "Salt-Lite Command Line Console";

    public function __construct(private readonly Application $application)
    {
    }

    #[\Override]
    public function run(): void
    {
        $this->application->setName(self::APP_NAME);
        $this->application->setAutoExit(true);
        $this->application->setCatchExceptions(true);
        $this->application->run();
    }
}
