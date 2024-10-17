<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton;

use PhoneBurner\SaltLiteFramework\Http\RequestHandler\CspViolationReportRequestHandler;
use PhoneBurner\SaltLiteFramework\Http\RequestHandler\ErrorRequestHandler;
use PhoneBurner\SaltLiteFramework\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLiteFramework\Routing\RouteProvider;

class AppRouteProvider implements RouteProvider
{
    #[\Override]
    public function __invoke(): array
    {
        return [
            RouteDefinition::post('/csp')->withHandler(CspViolationReportRequestHandler::class),
            RouteDefinition::get('/errors[/{error}]')->withHandler(ErrorRequestHandler::class),
        ];
    }
}
