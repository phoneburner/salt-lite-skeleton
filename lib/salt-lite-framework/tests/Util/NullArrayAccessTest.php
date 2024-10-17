<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Util;

use PhoneBurner\SaltLiteFramework\Util\Helper\Str;
use PhoneBurner\SaltLiteFramework\Util\NullArrayAccess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NullArrayAccessTest extends TestCase
{
    /**
     * @var array<string,mixed>
     */
    private array $test;

    #[\Override]
    protected function setUp(): void
    {
        $this->test = [
            'bool_true' => true,
            'bool_false' => false,
            'int' => 1,
            'int_empty' => 0,
            'float' => 1.2,
            'float_empty' => 0.0,
            'string' => 'Hello World',
            'string_empty' => '',
            'object' => new \stdClass(),
            'resource' => Str::stream()->detach(),
            'callable' => static fn(): int => 1,
        ];
    }

    #[Test]
    public function offsetGet_gets_the_expected_value(): void
    {
        $sut = new NullArrayAccess($this->test);

        self::assertCount(11, $sut);
        self::assertSame($this->test, $sut->toArray());
        self::assertSame($this->test, \iterator_to_array($sut));
        foreach ($this->test as $key => $value) {
            self::assertArrayHasKey($key, $sut);
            self::assertTrue($sut->offsetExists($key));
            self::assertSame($value, $sut[$key]);
            self::assertSame($value, $sut->offsetGet($key));
        }

        self::assertNull($sut['non_existent']);
    }

    public function offsetSet_sets_the_expected_value(): void
    {
    /** @var NullArrayAccess<string, mixed> $sut */
        $sut = new NullArrayAccess([]);

        self::assertCount(0, $sut);
        self::assertSame([], $sut->toArray());
        self::assertSame([], \iterator_to_array($sut));
        foreach ($this->test as $key => $value) {
            self::assertArrayNotHasKey($key, $sut);
            self::assertFalse($sut->offsetExists($key));
            self::assertNull($sut[$key]);
            self::assertNull($sut->offsetGet($key));
            $sut[$key] = $value;
        }

        self::assertCount(11, $sut);
        self::assertSame($this->test, $sut->toArray());
        self::assertSame($this->test, \iterator_to_array($sut));
        foreach ($this->test as $key => $value) {
            self::assertArrayHasKey($key, $sut);
            self::assertTrue($sut->offsetExists($key));
            self::assertSame($value, $sut[$key]);
            self::assertSame($value, $sut->offsetGet($key));
        }
    }
}
