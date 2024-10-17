<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http\Response\Exceptional;

use PhoneBurner\SaltLiteFramework\Http\Domain\HttpReasonPhrase;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpStatus;

class TooManyRequestsResponse extends GenericHttpExceptionResponse
{
    protected int $status_code = HttpStatus::TOO_MANY_REQUESTS;
    protected string $title = HttpReasonPhrase::TOO_MANY_REQUESTS;
}
