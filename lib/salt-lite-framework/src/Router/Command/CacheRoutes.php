<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router\Command;

use PhoneBurner\SaltLiteFramework\App\BuildStage;
use PhoneBurner\SaltLiteFramework\App\Environment;
use PhoneBurner\SaltLiteFramework\Configuration\Configuration;
use PhoneBurner\SaltLiteFramework\Router\FastRoute\FastRouter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(self::NAME, self::DESCRIPTION)]
class CacheRoutes extends Command
{
    public const string NAME = 'router:cache';

    public const string DESCRIPTION = 'Generate the cached routes file';

    public function __construct(
        private readonly Environment $environment,
        private readonly Configuration $config,
        private readonly FastRouter $router,
    ) {
        parent::__construct(self::NAME);
        $this->setDescription(self::DESCRIPTION);
        $this->addOption('clear', null, InputOption::VALUE_NONE, 'Only clear the routes cache file, without regenerating it');
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cache_file = $this->config->get('router.route_cache.filepath');

        if ($this->environment->stage === BuildStage::Development) {
            $output->writeln('<comment>Route caching is disabled in development mode</comment>');
            $output->writeln('Set the ENABLE_ROUTE_CACHE environment variable to `true` enable it');
            return self::SUCCESS;
        }

        if ($input->getOption('clear') || \file_exists($cache_file)) {
            $output->write("<comment>Clearing Existing Route Cache File:</comment> ");

            if (! \file_exists($cache_file)) {
                $output->writeln("N/A");
            } elseif (\unlink($cache_file)) {
                $output->writeln("<info>OK</info>");
            } else {
                $output->writeln("<error>FAIL</error>");
                return self::FAILURE;
            }
        }

        if ($input->getOption('clear')) {
            return self::SUCCESS;
        }

        $output->write("<comment>Generating Route Cache File:</comment> ");

        \assert(! \file_exists($cache_file));

        $this->router->dispatcher();

        // Verify that we created a valid PHP-parsable file by trying to include it
        // By catching on `\Throwable`, we'll catch any both "file not exists" E_ERROR
        // and "file not parsable" \E_PARSE_ERROR errors.
        try {
            require $cache_file;
            $output->writeln("<info>OK</info>");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln("<error>FAIL ({$e->getMessage()})</error>");
            return self::FAILURE;
        }
    }
}
