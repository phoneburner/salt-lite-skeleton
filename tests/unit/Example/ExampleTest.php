<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Tests\Unit\Example;

use PhoneBurner\SaltLite\App\Tests\Unit\TestSupport\HasApplicationLifecycle;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    use HasApplicationLifecycle;

    #[Test]
    #[DataProvider('providesExampleHappyPath')]
    public function example_happy_path(bool $expected, int $value): void
    {
        self::assertSame($expected, (bool)$value);
    }

    public static function providesExampleHappyPath(): \Generator
    {
        yield [true, 1];
        yield [false, 0];
    }
}
