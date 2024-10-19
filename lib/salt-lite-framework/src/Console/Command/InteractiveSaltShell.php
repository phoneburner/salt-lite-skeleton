<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use PhoneBurner\SaltLiteFramework\App\Environment;
use PhoneBurner\SaltLiteFramework\Cache\AppendOnlyCache;
use PhoneBurner\SaltLiteFramework\Cache\Cache;
use PhoneBurner\SaltLiteFramework\Cache\Lock\LockFactory;
use PhoneBurner\SaltLiteFramework\Configuration\Configuration;
use PhoneBurner\SaltLiteFramework\Container\MutableContainer;
use Psr\Log\LoggerInterface;
use Psy\Configuration as PsyConfiguration;
use Psy\Shell;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(self::NAME, self::DESCRIPTION)]
class InteractiveSaltShell extends Command
{
    public const string NAME = 'shell';

    public const string DESCRIPTION = 'Interactive PHP REPL Shell (PsySH)';

    private const string MESSAGE = "Interactive PHP REPL Shell (PsySH) \r\nEnter \"ls -l\" to List Defined Variables or \"exit\" to Quit";

    private const array SERVICES = [
        'config' => Configuration::class,
        'container' => MutableContainer::class,
        'environment' => Environment::class,
        'logger' => LoggerInterface::class,
        'em' => EntityManagerInterface::class,
        'connection' => Connection::class,
        'redis' => \Redis::class,
        'cache' => Cache::class,
        'append_only_cache' => AppendOnlyCache::class,
        'lock_factory' => LockFactory::class,
        'storage' => FilesystemOperator::class,
    ];

    public function __construct(private readonly MutableContainer $container)
    {
        parent::__construct(self::NAME);
        $this->setDescription(self::DESCRIPTION);
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $shell_config = new PsyConfiguration([
            'startupMessage' => self::MESSAGE,
            'updateCheck' => 'never',
        ]);

        $shell = new Shell($shell_config);
        $shell->setScopeVariables(\array_map($this->container->get(...), self::SERVICES));

        return $shell->run();
    }
}
