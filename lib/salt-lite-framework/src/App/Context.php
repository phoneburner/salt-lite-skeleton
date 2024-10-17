<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\App;

enum Context
{
    case Http;
    case Cli;
    case Test;
}
