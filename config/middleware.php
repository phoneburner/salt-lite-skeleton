<?php

declare(strict_types=1);

use PhoneBurner\SaltLite\Framework\Http\Middleware\CatchExceptionalResponses;
use PhoneBurner\SaltLite\Framework\Http\Middleware\TransformHttpExceptionResponses;
use PhoneBurner\SaltLite\Framework\Routing\Middleware\AttachRouteToRequest;
use PhoneBurner\SaltLite\Framework\Routing\Middleware\DispatchRouteMiddleware;
use PhoneBurner\SaltLite\Framework\Routing\Middleware\DispatchRouteRequestHandler;

return [
    'middleware' => [
        TransformHttpExceptionResponses::class,
        CatchExceptionalResponses::class,
        AttachRouteToRequest::class,
        DispatchRouteMiddleware::class,
        DispatchRouteRequestHandler::class,
    ],
];
