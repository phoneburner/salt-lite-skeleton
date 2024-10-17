<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\Middleware;

use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\MethodNotAllowedResponse;
use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;
use PhoneBurner\SaltLiteFramework\Routing\Result\MethodNotAllowed;
use PhoneBurner\SaltLiteFramework\Routing\Result\RouteFound;
use PhoneBurner\SaltLiteFramework\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AttachRouteToRequest implements MiddlewareInterface
{
    public function __construct(private readonly Router $finder)
    {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $this->finder->resolveForRequest($request);

        if ($result instanceof MethodNotAllowed) {
            return new MethodNotAllowedResponse(...$result->getAllowedMethods());
        }

        if ($result instanceof RouteFound) {
            $request = $request->withAttribute(RouteMatch::class, $result->getRouteMatch());
        }

        return $handler->handle($request);
    }
}
