<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Console;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\CurrentCommand;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\ListCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use PhoneBurner\SaltLiteFramework\Configuration\Configuration;
use PhoneBurner\SaltLiteFramework\Container\MutableContainer;
use PhoneBurner\SaltLiteFramework\Container\ServiceProvider;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        $container->set(CliKernel::class, static function (MutableContainer $container): CliKernel {
            $application = $container->get(Application::class);
            return new CliKernel($application);
        });

        $container->set(CommandLoaderInterface::class, static function (ContainerInterface $container): CommandLoader {
            return new CommandLoader($container, $container->get(Configuration::class)->get('commands') ?? []);
        });

        $container->set(Application::class, function (MutableContainer $container): Application {
            $application = new class extends Application{
                protected function configureIO(InputInterface $input, OutputInterface $output): void
                {
                    parent::configureIO($input, $output);
                    $output->getFormatter()->setStyle('error', new OutputFormatterStyle('red'));
                }

                protected function doRenderThrowable(\Throwable $e, OutputInterface $output): void
                {
                    $output->getFormatter()->setStyle('error', new OutputFormatterStyle('default', 'red'));
                    parent::doRenderThrowable($e, $output);
                }
            };

            $configuration = $container->get(Configuration::class)->get('database.doctrine.connections.default.migrations') ?? [];

            $dependency_factory = DependencyFactory::fromConnection(
                new ConfigurationArray($configuration),
                new ExistingConnection($container->get(Connection::class)),
                $container->get(LoggerInterface::class),
            );

            $application->addCommands([
                new CurrentCommand($dependency_factory),
                new DiffCommand($dependency_factory),
                new DumpSchemaCommand($dependency_factory),
                new ExecuteCommand($dependency_factory),
                new GenerateCommand($dependency_factory),
                new LatestCommand($dependency_factory),
                new ListCommand($dependency_factory),
                new MigrateCommand($dependency_factory),
                new RollupCommand($dependency_factory),
                new StatusCommand($dependency_factory),
                new SyncMetadataCommand($dependency_factory),
                new UpToDateCommand($dependency_factory),
                new VersionCommand($dependency_factory),
            ]);
            $application->setCommandLoader($container->get(CommandLoaderInterface::class));
            $application->setAutoExit(false);
            $application->setCatchExceptions(false);
            return $application;
        });
    }
}
