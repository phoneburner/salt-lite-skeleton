<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Http\Response\Exceptional;

use PhoneBurner\SaltLite\Framework\Http\Domain\HttpReasonPhrase;
use PhoneBurner\SaltLite\Framework\Http\Domain\HttpStatus;

class TooManyRequestsResponse extends GenericHttpExceptionResponse
{
    protected int $status_code = HttpStatus::TOO_MANY_REQUESTS;
    protected string $title = HttpReasonPhrase::TOO_MANY_REQUESTS;
}
