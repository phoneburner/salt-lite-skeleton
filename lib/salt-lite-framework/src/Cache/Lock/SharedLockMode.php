<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache\Lock;

enum SharedLockMode
{
    case Write;
    case Read;
}
