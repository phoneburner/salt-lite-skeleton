<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http\RequestHandler;

use PhoneBurner\SaltLiteFramework\Http\Domain\HttpReasonPhrase;
use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\GenericHttpExceptionResponse;
use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\PageNotFoundResponse;
use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorRequestHandler implements RequestHandlerInterface
{
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route_match = $request->getAttribute(RouteMatch::class);
        if (! $route_match instanceof RouteMatch) {
            return new PageNotFoundResponse();
        }

        $status_code = (int)$route_match->getPathParameter('error');
        $reason_phrase = HttpReasonPhrase::lookup($status_code);
        return $reason_phrase
            ? new GenericHttpExceptionResponse($status_code, $reason_phrase)
            : new PageNotFoundResponse();
    }
}
