<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Routing\Result;

use LogicException;
use PhoneBurner\SaltLiteFramework\Routing\Result\RouteNotFound as SUT;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RouteNotFoundTest extends TestCase
{
    #[Test]
    public function make_returns_found(): void
    {
        $sut = SUT::make();
        self::assertFalse($sut->isFound());
    }

    #[Test]
    public function make_does_not_return_RouteMatch(): void
    {
        $sut = SUT::make();
        $this->expectException(LogicException::class);
        $sut->getRouteMatch();
    }
}
