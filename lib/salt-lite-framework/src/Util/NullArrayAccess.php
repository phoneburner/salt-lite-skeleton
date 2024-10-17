<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Util;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use PhoneBurner\SaltLiteFramework\Domain\Arrayable;

/**
 * @template TKey
 * @template TValue
 * @template-implements IteratorAggregate<TKey, TValue>
 * @template-implements ArrayAccess<TKey, TValue>
 */
class NullArrayAccess implements ArrayAccess, IteratorAggregate, Countable, Arrayable
{
    /**
     * @param array<TKey, TValue> $array
     */
    public function __construct(private array $array)
    {
    }

    #[\Override]
    public function offsetExists(mixed $offset): bool
    {
        return \array_key_exists($offset, $this->array);
    }

    /**
     * @param TKey $offset
     * @return TValue|null
     */
    #[\ReturnTypeWillChange]
    #[\Override]
    public function offsetGet($offset)
    {
        return $this->array[$offset] ?? null;
    }

    /**
     * @param TKey $offset
     * @param TValue $value
     */
    #[\Override]
    public function offsetSet($offset, $value): void
    {
        $this->array[$offset] = $value;
    }

    #[\Override]
    public function offsetUnset(mixed $offset): void
    {
        unset($this->array[$offset]);
    }

    /**
     * @return \Generator<TKey, TValue>
     */
    #[\Override]
    public function getIterator(): \Generator
    {
        yield from $this->array;
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->array);
    }

    /**
     * @return array<TKey, TValue>
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->array;
    }
}
