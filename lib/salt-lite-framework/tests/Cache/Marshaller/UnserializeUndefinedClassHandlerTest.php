<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Cache\Marshaller;

use PhoneBurner\SaltLiteFramework\Cache\Marshaller\UnserializeUndefinedClassHandler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnserializeUndefinedClassHandlerTest extends TestCase
{
    #[Test]
    public function handleUndefinedClass_throws_exception(): void
    {
        $this->expectException(\DomainException::class);
        UnserializeUndefinedClassHandler::fail('foo');
    }
}
