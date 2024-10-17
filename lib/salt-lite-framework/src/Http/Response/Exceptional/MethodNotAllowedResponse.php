<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http\Response\Exceptional;

use PhoneBurner\SaltLiteFramework\Http\Domain\HttpHeader;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpMethod;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpReasonPhrase;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpStatus;

class MethodNotAllowedResponse extends GenericHttpExceptionResponse
{
    protected int $status_code = HttpStatus::METHOD_NOT_ALLOWED;
    protected string $title = HttpReasonPhrase::METHOD_NOT_ALLOWED;

    /**
     * @var array<HttpMethod>
     */
    public readonly array $allowed_methods;

    public function __construct(HttpMethod ...$allowed_methods)
    {
        $this->allowed_methods = $allowed_methods;
        parent::__construct(HttpStatus::METHOD_NOT_ALLOWED, HttpReasonPhrase::METHOD_NOT_ALLOWED, headers: [
            HttpHeader::ALLOW => \implode(', ', \array_column($allowed_methods, 'value')),
        ]);
    }
}
