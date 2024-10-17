<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MutableMiddlewareRequestHandler extends RequestHandlerInterface
{
    public function push(MiddlewareInterface $middleware): self;
}
