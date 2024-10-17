<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache\Lock;

use PhoneBurner\SaltLiteFramework\Cache\CacheKey;
use PhoneBurner\SaltLiteFramework\Cache\Marshaller\RemoteCacheMarshaller;

class NamedKeyFactory
{
    private array $cache = [];

    public function make(NamedKey|\Stringable|string $name): NamedKey
    {
        $normalized_name = self::normalize($name);
        return $this->cache[self::normalize($name)] ??= match (true) {
            $name instanceof NamedKey => $name,
            default => new NamedKey($normalized_name),
        };
    }

    public function has(NamedKey|\Stringable|string $name): bool
    {
        return isset($this->cache[self::normalize($name)]);
    }

    public function delete(NamedKey|\Stringable|string $name): void
    {
        unset($this->cache[self::normalize($name)]);
    }

    private static function normalize(NamedKey|\Stringable|string $name): string
    {
        return $name instanceof NamedKey ? $name->name : CacheKey::make($name)->normalized;
    }

    /**
     * Transform a NamedKey object into a compressed base64-encoded string that
     * is safe for putting in a Beanstalkd job data payload.
     */
    public static function serialize(NamedKey $key): string
    {
        $key = \igbinary_serialize($key) ?? throw new \UnexpectedValueException('igbinary_serialize failure');
        $key = \gzcompress($key, RemoteCacheMarshaller::COMPRESSION_LEVEL) ?: throw new \UnexpectedValueException('gzcompress failure');
        return \base64_encode($key);
    }

    /**
     * Transform a compressed base64-encoded string back into a NamedKey object.
     */
    public static function deserialize(string $key): NamedKey
    {
        $key = \base64_decode($key) ?: throw new \UnexpectedValueException('invalid base64 encoded string');
        $key = \gzuncompress($key) ?: throw new \UnexpectedValueException('invalid zlib string');
        return \igbinary_unserialize($key);
    }
}
