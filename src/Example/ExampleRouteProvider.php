<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Example;

use PhoneBurner\SaltLite\App\Example\RequestHandler\ExampleRequestHandler;
use PhoneBurner\SaltLite\Framework\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLite\Framework\Routing\RouteProvider;

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
