<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use PhoneBurner\SaltLiteFramework\Configuration\Configuration;
use PhoneBurner\SaltLiteFramework\Container\MutableContainer;
use PhoneBurner\SaltLiteFramework\Container\ServiceProvider;
use PhoneBurner\SaltLiteFramework\Http\Middleware\LazyMiddlewareRequestHandlerFactory;
use PhoneBurner\SaltLiteFramework\Http\Middleware\MiddlewareRequestHandlerFactory;
use PhoneBurner\SaltLiteFramework\Http\Middleware\TransformHttpExceptionResponses;
use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\HttpExceptionResponseTransformer;
use PhoneBurner\SaltLiteFramework\Logging\LogTrace;
use PhoneBurner\SaltLiteFramework\Routing\RequestHandler\NullHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class HttpServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        $container->set(
            HttpKernel::class,
            static function (ContainerInterface $container): HttpKernel {
                return new HttpKernel(
                    $container->get(RequestFactory::class),
                    $container->get(RequestHandlerInterface::class),
                    $container->get(EmitterInterface::class),
                    $container->get(LoggerInterface::class),
                );
            },
        );

        $container->set(
            RequestFactory::class,
            static function (ContainerInterface $container): RequestFactory {
                return new RequestFactory();
            },
        );

        $container->set(
            EmitterInterface::class,
            static function (ContainerInterface $container): EmitterInterface {
                return new SapiStreamEmitter();
            },
        );

        $container->set(
            MiddlewareRequestHandlerFactory::class,
            static function (ContainerInterface $container): MiddlewareRequestHandlerFactory {
                return new LazyMiddlewareRequestHandlerFactory($container);
            },
        );

        $container->set(
            RequestHandlerInterface::class,
            static function (ContainerInterface $container): RequestHandlerInterface {
                return $container->get(MiddlewareRequestHandlerFactory::class)->queue(
                    new NullHandler($container->get(LoggerInterface::class)),
                    $container->get(Configuration::class)->get('middleware') ?? [],
                );
            },
        );

        $container->set(
            TransformHttpExceptionResponses::class,
            static function (ContainerInterface $container): TransformHttpExceptionResponses {
                return new TransformHttpExceptionResponses(
                    $container->get(HttpExceptionResponseTransformer::class),
                );
            },
        );

        $container->set(
            HttpExceptionResponseTransformer::class,
            static function (ContainerInterface $container): HttpExceptionResponseTransformer {
                return new HttpExceptionResponseTransformer(
                    $container->get(LogTrace::class),
                );
            },
        );
    }
}
