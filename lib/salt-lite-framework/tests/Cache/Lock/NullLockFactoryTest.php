<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Cache\Lock;

use PhoneBurner\SaltLiteFramework\Cache\Lock\NullLock;
use PhoneBurner\SaltLiteFramework\Cache\Lock\NullLockFactory;
use PhoneBurner\SaltLiteFramework\Domain\Time\Ttl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NullLockFactoryTest extends TestCase
{
    #[Test]
    public function it_returns_a_null_lock(): void
    {
        $sut = new NullLockFactory();
        $lock = $sut->make('foo', Ttl::seconds(34), false);
        self::assertInstanceOf(NullLock::class, $lock);
        self::assertEquals(Ttl::seconds(34), $lock->ttl());
    }
}
