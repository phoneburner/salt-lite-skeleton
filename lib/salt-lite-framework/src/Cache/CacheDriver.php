<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache;

enum CacheDriver: string
{
    case File = 'file';
    case Memory = 'memory';
    case None = 'none';
    case Remote = 'remote';
}
