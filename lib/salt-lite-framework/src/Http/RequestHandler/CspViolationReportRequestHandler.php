<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http\RequestHandler;

use PhoneBurner\SaltLiteFramework\Http\Domain\HttpStatus;
use PhoneBurner\SaltLiteFramework\Http\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class CspViolationReportRequestHandler implements RequestHandlerInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->notice('CSP Violation Reported', (array)$request->getParsedBody());
        return new EmptyResponse(HttpStatus::ACCEPTED);
    }
}
