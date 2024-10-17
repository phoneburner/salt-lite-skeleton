<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router;

use PhoneBurner\SaltLiteFramework\App\BuildStage;
use PhoneBurner\SaltLiteFramework\Configuration\Configuration;
use PhoneBurner\SaltLiteFramework\Container\MutableContainer;
use PhoneBurner\SaltLiteFramework\Container\ServiceProvider;
use PhoneBurner\SaltLiteFramework\Router\Definition\DefinitionList;
use PhoneBurner\SaltLiteFramework\Router\Definition\LazyConfigDefinitionList;
use PhoneBurner\SaltLiteFramework\Router\FastRoute\FastRouteDispatcherFactory;
use PhoneBurner\SaltLiteFramework\Router\FastRoute\FastRouter;
use PhoneBurner\SaltLiteFramework\Router\FastRoute\FastRouteResultFactory;
use PhoneBurner\SaltLiteFramework\Router\RequestHandler\NullHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class RouterServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        $container->bind(Router::class, FastRouter::class);
        $container->set(FastRouter::class, static function (ContainerInterface $container): FastRouter {
            return new FastRouter(
                $container->get(DefinitionList::class),
                $container->get(FastRouteDispatcherFactory::class),
                $container->get(FastRouteResultFactory::class),
            );
        });

        $container->set(
            FastRouteDispatcherFactory::class,
            static function (ContainerInterface $container): FastRouteDispatcherFactory {
                $cache_file = $container->get(Configuration::class)->get('router.route_cache.filepath');
                $cache_enable = $container->get(BuildStage::class) !== BuildStage::Development
                    || $container->get(Configuration::class)->get('router.route_cache.enable');

                return new FastRouteDispatcherFactory(
                    $container->get(LoggerInterface::class),
                    $cache_enable ? $cache_file : null,
                );
            },
        );

        $container->set(
            FastRouteResultFactory::class,
            static function (ContainerInterface $container): FastRouteResultFactory {
                return new FastRouteResultFactory();
            },
        );

        $container->set(
            DefinitionList::class,
            static function (ContainerInterface $container): DefinitionList {
                return LazyConfigDefinitionList::makeFromCallable(...\array_map(
                    static function (string $provider): RouteProvider {
                        $provider = new $provider();
                        \assert($provider instanceof RouteProvider);
                        return $provider;
                    },
                    $container->get(Configuration::class)->get('route_providers') ?? [],
                ));
            },
        );

        $container->set(
            NullHandler::class,
            static function (ContainerInterface $container): NullHandler {
                return new NullHandler($container->get(LoggerInterface::class));
            },
        );
    }
}
