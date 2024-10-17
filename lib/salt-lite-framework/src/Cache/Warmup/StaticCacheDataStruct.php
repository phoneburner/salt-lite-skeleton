<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache\Warmup;

use PhoneBurner\SaltLiteFramework\Cache\CacheKey;

readonly class StaticCacheDataStruct
{
    public function __construct(
        public CacheKey $key,
        public mixed $value,
    ) {
    }
}
