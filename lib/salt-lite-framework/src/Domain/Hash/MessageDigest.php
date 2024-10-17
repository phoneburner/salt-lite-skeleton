<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Domain\Hash;

interface MessageDigest extends \Stringable
{
    public function algorithm(): HashAlgorithm;

    public function digest(): string;
}
