<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\App;

use PhoneBurner\SaltLiteFramework\Configuration\Configuration;
use PhoneBurner\SaltLiteFramework\Configuration\ConfigurationFactory;
use PhoneBurner\SaltLiteFramework\Container\PhpDiContainerAdapter;
use PhoneBurner\SaltLiteFramework\Container\ServiceProvider;
use PhoneBurner\SaltLiteFramework\Logging\LogTrace;
use Psr\Container\ContainerInterface;

final class App
{
    private static self|null $instance = null;

    public readonly ContainerInterface $container;

    public readonly Configuration $config;

    public static function bootstrap(Context $context): self
    {
        return self::$instance ??= new self($context);
    }

    public static function booted(): bool
    {
        return isset(self::$instance);
    }

    public static function instance(): self
    {
        return self::$instance ?? throw new \RuntimeException('Application has not been bootstrapped.');
    }

    public static function teardown(): void
    {
        self::$instance = null;
    }

    private function __construct(public readonly Context $context)
    {
        $environment = new Environment($_SERVER, $context);

        // resolve configuration.
        $this->config = ConfigurationFactory::make($environment);
        $container = new PhpDiContainerAdapter();

        // instantiate container and register application service providers
        foreach ($this->config->get('service_providers') as $provider_class) {
            \assert(\is_a($provider_class, ServiceProvider::class, true));
            (new $provider_class())->register($container);
        }

        $container->set(Configuration::class, $this->config);
        $container->set(Environment::class, $environment);
        $container->set(LogTrace::class, LogTrace::make());
        $container->set(self::class, $this);

        $this->container = $container;
    }
}
