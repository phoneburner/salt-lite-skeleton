<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\App;

use Crell\AttributeUtils\Analyzer;
use Crell\AttributeUtils\ClassAnalyzer;
use Crell\AttributeUtils\MemoryCacheAnalyzer;
use Crell\AttributeUtils\Psr6CacheAnalyzer;
use PhoneBurner\SaltLiteFramework\App\Exception\KernelError;
use PhoneBurner\SaltLiteFramework\Cache\CacheDriver;
use PhoneBurner\SaltLiteFramework\Cache\CacheItemPoolFactory;
use PhoneBurner\SaltLiteFramework\Console\CliKernel;
use PhoneBurner\SaltLiteFramework\Container\MutableContainer;
use PhoneBurner\SaltLiteFramework\Container\PhpDiContainerAdapter;
use PhoneBurner\SaltLiteFramework\Container\ServiceProvider;
use PhoneBurner\SaltLiteFramework\Http\HttpKernel;
use PhoneBurner\SaltLiteFramework\Util\Clock\Clock;
use PhoneBurner\SaltLiteFramework\Util\Clock\HighResolutionTimer;
use PhoneBurner\SaltLiteFramework\Util\Clock\SystemClock;
use PhoneBurner\SaltLiteFramework\Util\Clock\SystemHighResolutionTimer;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;

class AppServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        // When asked for a concrete instance or an implementation of either of
        // the two container interfaces, the container should return itself.
        $container->set(ContainerInterface::class, $container);
        $container->set(MutableContainer::class, $container);
        $container->set(PhpDiContainerAdapter::class, $container);

        $container->set(
            Environment::class,
            static function (ContainerInterface $container): never {
                throw new \LogicException('Environment is not defined');
            },
        );

        $container->set(
            BuildStage::class,
            static function (ContainerInterface $container): BuildStage {
                return $container->get(Environment::class)->stage;
            },
        );

        $container->set(
            Context::class,
            static function (ContainerInterface $container): Context {
                return $container->get(Environment::class)->context;
            },
        );

        $container->set(
            Kernel::class,
            static function (ContainerInterface $container): Kernel {
                return match ($container->get(Context::class)) {
                    Context::Http => $container->get(HttpKernel::class),
                    Context::Cli => $container->get(CliKernel::class),
                    default => throw new KernelError('Salt Context is Not Defined or Supported'),
                };
            },
        );

        $container->bind(ClockInterface::class, Clock::class);
        $container->bind(Clock::class, SystemClock::class);
        $container->set(
            SystemClock::class,
            static function (ContainerInterface $container): SystemClock {
                return new SystemClock();
            },
        );

        $container->set(
            HighResolutionTimer::class,
            static function (ContainerInterface $container): SystemHighResolutionTimer {
                return new SystemHighResolutionTimer();
            },
        );

        $container->set(
            ClassAnalyzer::class,
            static function (ContainerInterface $container): ClassAnalyzer {
                $analyzer = new Analyzer();
                if ($container->get(BuildStage::class) !== BuildStage::Development) {
                    $pool = $container->get(CacheItemPoolFactory::class)->make(CacheDriver::File);
                    $analyzer = new Psr6CacheAnalyzer($analyzer, $pool);
                }

                return new MemoryCacheAnalyzer($analyzer);
            },
        );
    }
}
