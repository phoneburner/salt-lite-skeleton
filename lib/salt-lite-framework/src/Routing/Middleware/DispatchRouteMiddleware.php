<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\Middleware;

use PhoneBurner\SaltLiteFramework\Http\Middleware\MiddlewareRequestHandlerFactory;
use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DispatchRouteMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly MiddlewareRequestHandlerFactory $middleware_factory)
    {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RouteMatch::class);
        if ($route instanceof RouteMatch) {
            $handler = $this->middleware_factory->queue($handler, $route->getMiddleware());
        }

        return $handler->handle($request);
    }
}
