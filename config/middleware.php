<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteFramework\Http\Middleware\CatchExceptionalResponses;
use PhoneBurner\SaltLiteFramework\Http\Middleware\TransformHttpExceptionResponses;
use PhoneBurner\SaltLiteFramework\Routing\Middleware\AttachRouteToRequest;
use PhoneBurner\SaltLiteFramework\Routing\Middleware\DispatchRouteMiddleware;
use PhoneBurner\SaltLiteFramework\Routing\Middleware\DispatchRouteRequestHandler;

return [
    'middleware' => [
        TransformHttpExceptionResponses::class,
        CatchExceptionalResponses::class,
        AttachRouteToRequest::class,
        DispatchRouteMiddleware::class,
        DispatchRouteRequestHandler::class,
    ],
];
