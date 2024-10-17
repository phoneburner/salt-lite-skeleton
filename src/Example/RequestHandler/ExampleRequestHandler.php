<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example\RequestHandler;

use PhoneBurner\SaltLiteFramework\Http\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExampleRequestHandler implements RequestHandlerInterface
{
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $time = (new \DateTimeImmutable())->format(\DATE_RFC3339);
        return new HtmlResponse(<<<HTML
            <h1>Hello World!</h1><br><br><strong>The Current Time Is: </strong>{$time}
            HTML);
    }
}
