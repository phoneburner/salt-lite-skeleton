<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Configuration;

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use PhoneBurner\SaltLiteFramework\App\BuildStage;
use PhoneBurner\SaltLiteFramework\App\Environment;
use PhoneBurner\SaltLiteFramework\Domain\Hash\Hash;
use PhoneBurner\SaltLiteFramework\Util\Filesystem\FileReader;

use function PhoneBurner\SaltLiteFramework\env;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

class ConfigurationFactory
{
    private const string ENV_FILE = APP_ROOT . '/.env';
    private const string CONFIG_PATH = APP_ROOT . '/config';
    private const string CACHE_FILE = APP_ROOT . '/storage/bootstrap/config.%s.cache.php';

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
            $build_stage === BuildStage::Production => \sprintf(self::CACHE_FILE, BuildStage::Production->value),
            (bool)env('SALT_ENABLE_CONFIG_CACHE') => \sprintf(self::CACHE_FILE, Hash::iterable(self::parts($build_stage))),
            default => null,
        };
    }

    private static function parts(BuildStage $build_stage): \Generator
    {
        if (\file_exists(self::ENV_FILE)) {
            yield FileReader::make(self::ENV_FILE);
        }

        if ($build_stage === BuildStage::Development) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::CONFIG_PATH)) as $file) {
                if (\str_ends_with((string)$file, '.php')) {
                    yield FileReader::make($file);
                }
            }
        }
    }

    /**
     * @return list<callable(array): array>
     */
    private static function processors(): array
    {
        return [];
    }
}
