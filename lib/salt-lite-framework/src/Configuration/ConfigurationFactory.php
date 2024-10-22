<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Configuration;

use Brick\VarExporter\VarExporter;
use PhoneBurner\SaltLiteFramework\App\BuildStage;
use PhoneBurner\SaltLiteFramework\App\Environment;
use PhoneBurner\SaltLiteFramework\Util\Filesystem\FileWriter;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

class ConfigurationFactory
{
    private const int EXPORT_OPTIONS = VarExporter::ADD_RETURN | VarExporter::TRAILING_COMMA_IN_ARRAY;
    private const string CONFIG_PATH = APP_ROOT . '/config';
    private const string CACHE_FILE = APP_ROOT . '/storage/bootstrap/config.cache.php';

    public static function make(Environment $environment): ImmutableConfiguration
    {
        $use_cache = $environment->stage === BuildStage::Production || $_ENV['SALT_ENABLE_CONFIG_CACHE'];
        if ($use_cache && \file_exists(self::CACHE_FILE)) {
            /** @phpstan-ignore include.fileNotFound (see https://github.com/phpstan/phpstan/issues/11798) */
            return new ImmutableConfiguration(include self::CACHE_FILE);
        }

        $config = [];
        foreach (\glob(self::CONFIG_PATH . '/*.php') ?: [] as $file) {
            foreach (include $file ?: [] as $key => $value) {
                $config[$key] = $value;
            }
        }

        if ($use_cache) {
            FileWriter::string(self::CACHE_FILE, '<?php ' . VarExporter::export($config, self::EXPORT_OPTIONS));
        }

        return new ImmutableConfiguration($config);
    }
}
