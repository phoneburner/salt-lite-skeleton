<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('example')]
class ExampleCommand extends Command
{
    public function __construct()
    {
        parent::__construct('example');
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
}
