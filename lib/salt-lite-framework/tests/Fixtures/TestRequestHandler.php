<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLite\Framework\Fixtures;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class TestRequestHandler implements RequestHandlerInterface
{
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new RuntimeException('For testing only');
    }
}
