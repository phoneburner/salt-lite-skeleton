<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Configuration;

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use PhoneBurner\SaltLiteFramework\App\BuildStage;
use PhoneBurner\SaltLiteFramework\App\Environment;

use function PhoneBurner\SaltLiteFramework\env;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

class ConfigurationFactory
{
    private const string CONFIG_PATH = APP_ROOT . '/config';
    private const string CACHE_FILE = APP_ROOT . '/storage/bootstrap/config.cache.php';

    private static ImmutableConfiguration $cache;

    public static function make(
        Environment $environment,
    ): ImmutableConfiguration {
        return self::$cache ??= new ImmutableConfiguration((new ConfigAggregator(
            [
                new PhpFileProvider(self::CONFIG_PATH . '/*.php'),
                new ArrayProvider([
                    ConfigAggregator::ENABLE_CACHE => true,
                ]),
            ],
            self::cache($environment->stage),
            self::processors(),
        ))->getMergedConfig());
    }

    /**
     * @return non-empty-string|null
     */
    private static function cache(BuildStage $build_stage): string|null
    {
        return match (true) {
            $build_stage === BuildStage::Production, (bool)env('SALT_ENABLE_CONFIG_CACHE') => self::CACHE_FILE,
            default => null,
        };
    }

    /**
     * @return list<callable(array): array>
     */
    private static function processors(): array
    {
        return [];
    }
}
