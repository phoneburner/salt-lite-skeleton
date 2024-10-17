<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\RequestHandler;

use PhoneBurner\SaltLiteFramework\Http\Domain\HttpHeader;
use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\FileNotFoundResponse;
use PhoneBurner\SaltLiteFramework\Http\Response\Exceptional\ServerErrorResponse;
use PhoneBurner\SaltLiteFramework\Http\Response\StreamResponse;
use PhoneBurner\SaltLiteFramework\Routing\Domain\StaticFile;
use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StaticFileRequestHandler implements RequestHandlerInterface
{
    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route_attributes = $request->getAttribute(RouteMatch::class)?->getAttributes() ?? [];

        $file = $route_attributes[StaticFile::class] ?? null;
        if (! $file instanceof StaticFile) {
            return new ServerErrorResponse();
        }

        $stream = @\fopen($file->path, 'rb');
        if ($stream === false) {
            return new FileNotFoundResponse();
        }

        return new StreamResponse($stream, headers: [
            HttpHeader::CONTENT_TYPE => $file->content_type,
            HttpHeader::CONTENT_LENGTH => \filesize($file->path),
            HttpHeader::CONTENT_DISPOSITION => $route_attributes[HttpHeader::CONTENT_DISPOSITION] ?? 'inline',
        ]);
    }
}
