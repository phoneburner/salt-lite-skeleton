<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App;

use PhoneBurner\SaltLite\Framework\Http\RequestHandler\CspViolationReportRequestHandler;
use PhoneBurner\SaltLite\Framework\Http\RequestHandler\ErrorRequestHandler;
use PhoneBurner\SaltLite\Framework\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLite\Framework\Routing\RouteProvider;

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
