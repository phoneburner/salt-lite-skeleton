<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\FastRoute;

use FastRoute\Dispatcher;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpMethod;
use PhoneBurner\SaltLiteFramework\Routing\Result\MethodNotAllowed;
use PhoneBurner\SaltLiteFramework\Routing\Result\RouteFound;
use PhoneBurner\SaltLiteFramework\Routing\Result\RouteNotFound;
use PhoneBurner\SaltLiteFramework\Routing\RouterResult;

class FastRouteResultFactory
{
    public function make(FastRouteMatch $match): RouterResult
    {
        if ($match->getStatus() === Dispatcher::METHOD_NOT_ALLOWED) {
            return MethodNotAllowed::make(...\array_map(HttpMethod::from(...), $match->getMethods()));
        }

        if ($match->getStatus() === Dispatcher::FOUND) {
            return RouteFound::make(
                \unserialize($match->getRouteData(), [
                    'allowed_classes' => true,
                ]),
                $match->getPathVars(),
            );
        }

        return RouteNotFound::make();
    }
}
