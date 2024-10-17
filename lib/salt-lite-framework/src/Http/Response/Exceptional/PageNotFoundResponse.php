<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http\Response\Exceptional;

use PhoneBurner\SaltLiteFramework\Http\Domain\HttpStatus;

class PageNotFoundResponse extends GenericHttpExceptionResponse
{
    protected int $status_code = HttpStatus::NOT_FOUND;
    protected string $title = "Page Not Found";
    protected string $detail = 'The requested page could not be found.';
}
