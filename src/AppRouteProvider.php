<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton;

use PhoneBurner\SaltLiteFramework\Http\RequestHandler\CspViolationReportRequestHandler;
use PhoneBurner\SaltLiteFramework\Http\RequestHandler\ErrorRequestHandler;
use PhoneBurner\SaltLiteFramework\Router\Definition\RouteDefinition;
use PhoneBurner\SaltLiteFramework\Router\RouteProvider;
use PhoneBurner\SaltLiteSkeleton\Example\RequestHandler\ExampleRequestHandler;

class AppRouteProvider implements RouteProvider
{
    #[\Override]
    public function __invoke(): array
    {
        return [
            RouteDefinition::get('/example')->withHandler(ExampleRequestHandler::class),
            RouteDefinition::post('/csp')->withHandler(CspViolationReportRequestHandler::class),
            RouteDefinition::get('/errors[/{error}]')->withHandler(ErrorRequestHandler::class),
        ];
    }
}
