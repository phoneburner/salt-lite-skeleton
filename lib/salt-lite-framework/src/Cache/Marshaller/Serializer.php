<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache\Marshaller;

enum Serializer
{
    case Igbinary;
    case Php;
}
