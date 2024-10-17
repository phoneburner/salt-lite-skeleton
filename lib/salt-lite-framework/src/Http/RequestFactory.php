<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http;

use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

class RequestFactory
{
    public function fromGlobals(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals();
    }
}
