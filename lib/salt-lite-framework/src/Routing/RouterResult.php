<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing;

use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;

interface RouterResult
{
    public function isFound(): bool;

    public function getRouteMatch(): RouteMatch;
}
