<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Cache\Lock;

use PhoneBurner\SaltLite\Framework\Attribute\Contract;
use PhoneBurner\SaltLite\Framework\Domain\Time\Ttl;

#[Contract]
interface LockFactory
{
    public function make(NamedKey|\Stringable|string $key, Ttl $ttl = new Ttl(300), bool $auto_release = true): Lock;
}
