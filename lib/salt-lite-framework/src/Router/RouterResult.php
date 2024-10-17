<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router;

use PhoneBurner\SaltLiteFramework\Router\Match\RouteMatch;

interface RouterResult
{
    public function isFound(): bool;

    public function getRouteMatch(): RouteMatch;
}
