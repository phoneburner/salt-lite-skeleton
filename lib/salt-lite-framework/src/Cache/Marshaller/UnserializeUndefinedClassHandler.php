<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache\Marshaller;

class UnserializeUndefinedClassHandler
{
    public static function fail(string $class): never
    {
        throw new \DomainException('Class not found: ' . $class);
    }
}
