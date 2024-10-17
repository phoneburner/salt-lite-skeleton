<?php

declare(strict_types=1);

use PhoneBurner\SaltLiteFramework\Http\Middleware\CatchExceptionalResponses;
use PhoneBurner\SaltLiteFramework\Http\Middleware\TransformHttpExceptionResponses;
use PhoneBurner\SaltLiteFramework\Router\Middleware\AttachRouteToRequest;
use PhoneBurner\SaltLiteFramework\Router\Middleware\DispatchRouteMiddleware;
use PhoneBurner\SaltLiteFramework\Router\Middleware\DispatchRouteRequestHandler;

return [
    'middleware' => [
        TransformHttpExceptionResponses::class,
        CatchExceptionalResponses::class,
        AttachRouteToRequest::class,
        DispatchRouteMiddleware::class,
        DispatchRouteRequestHandler::class,
    ],
];
