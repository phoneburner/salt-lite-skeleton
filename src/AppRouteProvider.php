<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App;

use PhoneBurner\SaltLite\Framework\Http\Domain\ContentType;
use PhoneBurner\SaltLite\Framework\Http\RequestHandler\CspViolationReportRequestHandler;
use PhoneBurner\SaltLite\Framework\Http\RequestHandler\ErrorRequestHandler;
use PhoneBurner\SaltLite\Framework\Http\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLite\Framework\Http\Routing\Domain\StaticFile;
use PhoneBurner\SaltLite\Framework\Http\Routing\RouteProvider;

use const PhoneBurner\SaltLite\Framework\APP_ROOT;

class AppRouteProvider implements RouteProvider
{
    #[\Override]
    public function __invoke(): array
    {
        return [
            RouteDefinition::file('/', new StaticFile(APP_ROOT . '/resources/views/welcome.html', ContentType::HTML)),
            RouteDefinition::post('/csp')->withHandler(CspViolationReportRequestHandler::class),
            RouteDefinition::get('/errors[/{error}]')->withHandler(ErrorRequestHandler::class),
        ];
    }
}
