<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Cache\Lock;

use PhoneBurner\SaltLiteFramework\Cache\Lock\NamedKey;
use PhoneBurner\SaltLiteFramework\Cache\Lock\NamedKeyFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NamedKeyFactoryTest extends TestCase
{
    #[Test]
    public function it_makes_and_caches_a_named_key(): void
    {
        $factory = new NamedKeyFactory();
        $named_key = $factory->make('FooBarBaz');
        self::assertSame('named_key.foo_bar_baz', (string)$named_key);
        self::assertSame('foo_bar_baz', $named_key->name);
        self::assertSame('locks.foo_bar_baz', (string)$named_key->key);
        self::assertSame($named_key, $factory->make('FooBarBaz'));
        self::assertSame($named_key, $factory->make('foo_bar_baz'));
        self::assertSame($named_key, $factory->make($named_key));
        self::assertSame($named_key, $factory->make(new class implements \Stringable {
            public function __toString(): string
            {
                return 'FooBarBaz';
            }
        }));

        self::assertTrue($factory->has('FooBarBaz'));
        self::assertTrue($factory->has('foo_bar_baz'));
        $factory->delete('FooBarBaz');

        self::assertFalse($factory->has('FooBarBaz'));
        self::assertFalse($factory->has('foo_bar_baz'));

        $new_named_key = $factory->make('FooBarBaz');
        self::assertEquals($named_key, $new_named_key);
        self::assertNotSame($named_key, $new_named_key);
    }

    #[DataProvider('providesNormalizedKeys')]
    #[Test]
    public function make_normalizes_key(\Stringable|string $key, string $expected): void
    {
        self::assertSame($expected, (new NamedKeyFactory())->make($key)->name);
    }

    public static function providesNormalizedKeys(): \Generator
    {
        yield ['key', 'key'];
        yield ['key_with_underscore', 'key_with_underscore'];
        yield ['key.with.dots', 'key.with.dots'];
        yield ['key with spaces', 'key_with_spaces'];
        yield ['key:with:colons', 'key_with_colons'];
        yield ['key{with}braces', 'key_with_braces'];
        yield ['key(with)parens', 'key_with_parens'];
        yield ['key/with/slashes', 'key_with_slashes'];
        yield ['key@with@at', 'key_with_at'];
        yield ['key\\with\\backslashes', 'key_with_backslashes'];
        yield ['key with spaces:and:colons{and}braces(with)parens/and/slashes@and@at\\and\\backslashes', 'key_with_spaces_and_colons_and_braces_with_parens_and_slashes_and_at_and_backslashes'];
        yield [NamedKey::class . ':1234', 'phone_burner_salt_lite_framework_cache_lock_named_key_1234'];
        yield [NamedKey::class . '.1234', 'phone_burner_salt_lite_framework_cache_lock_named_key.1234'];
        yield [
            new class implements \Stringable {
                public function __toString(): string
                {
                    return 'key with spaces:and:colons{and}braces(with)parens/and/slashes@and@at\\and\\backslashes';
                }
            },
            'key_with_spaces_and_colons_and_braces_with_parens_and_slashes_and_at_and_backslashes',
        ];
    }

    #[Test]
    public function serialize_and_deserialize_have_happy_path(): void
    {
        $key = new NamedKey('foo_bar_baz');

        $serialized = NamedKeyFactory::serialize($key);
        self::assertIsString($serialized);
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9\/+]+={0,2}$/', $serialized);

        $deserialized = NamedKeyFactory::deserialize($serialized);
        self::assertEquals($key, $deserialized);
    }
}
