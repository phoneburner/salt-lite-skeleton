<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Util\Helper\Fixture;

class NestedObject
{
    /** @phpstan-ignore property.onlyWritten */
    public function __construct(public mixed $public_value, private readonly mixed $private_value)
    {
    }
}
