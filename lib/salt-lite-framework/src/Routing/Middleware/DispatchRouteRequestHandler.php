<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\Middleware;

use PhoneBurner\SaltLiteFramework\Http\RequestHandlerFactory;
use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DispatchRouteRequestHandler implements MiddlewareInterface
{
    public function __construct(private readonly RequestHandlerFactory $request_handler_factory)
    {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RouteMatch::class);
        if ($route instanceof RouteMatch) {
            $handler = $this->request_handler_factory->make($route->getHandler());
        }

        return $handler->handle($request);
    }
}
