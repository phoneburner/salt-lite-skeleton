<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache\Lock;

use PhoneBurner\SaltLiteFramework\Attribute\Contract;
use PhoneBurner\SaltLiteFramework\Domain\Time\Ttl;

#[Contract]
interface LockFactory
{
    public function make(NamedKey|\Stringable|string $key, Ttl $ttl = new Ttl(300), bool $auto_release = true): Lock;
}
