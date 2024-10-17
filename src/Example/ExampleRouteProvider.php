<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example;

use PhoneBurner\SaltLiteFramework\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLiteFramework\Routing\RouteProvider;
use PhoneBurner\SaltLiteSkeleton\Example\RequestHandler\ExampleRequestHandler;

class ExampleRouteProvider implements RouteProvider
{
    #[\Override]
    public function __invoke(): array
    {
        return [
            RouteDefinition::get('/example')->withHandler(ExampleRequestHandler::class),
        ];
    }
}
