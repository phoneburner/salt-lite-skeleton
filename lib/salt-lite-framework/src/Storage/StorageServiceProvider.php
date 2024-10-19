<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Storage;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemReader;
use League\Flysystem\FilesystemWriter;
use PhoneBurner\SaltLiteFramework\Configuration\Configuration;
use PhoneBurner\SaltLiteFramework\Container\MutableContainer;
use PhoneBurner\SaltLiteFramework\Container\ServiceProvider;
use Psr\Container\ContainerInterface;

class StorageServiceProvider implements ServiceProvider
{
    public function register(MutableContainer $container): void
    {
        $container->bind(FilesystemReader::class, FilesystemOperator::class);
        $container->bind(FilesystemWriter::class, FilesystemOperator::class);
        $container->set(
            FilesystemOperator::class,
            static function (ContainerInterface $container): FilesystemOperator {
                return $container->get(FilesystemOperatorFactory::class)->default();
            },
        );

        $container->set(
            FilesystemOperatorFactory::class,
            static function (ContainerInterface $container): FilesystemOperatorFactory {
                return new FilesystemOperatorFactory($container->get(Configuration::class));
            },
        );
    }
}
