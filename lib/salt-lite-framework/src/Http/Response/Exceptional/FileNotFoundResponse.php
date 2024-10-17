<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Http\Response\Exceptional;

use PhoneBurner\SaltLiteFramework\Http\Domain\HttpStatus;

class FileNotFoundResponse extends GenericHttpExceptionResponse
{
    protected int $status_code = HttpStatus::NOT_FOUND;
    protected string $title = "File Not Found";
    protected string $detail = 'The file requested could not be found.';
}
