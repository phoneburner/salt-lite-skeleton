<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\Result;

use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;
use PhoneBurner\SaltLiteFramework\Routing\RouterResult;

class RouteNotFound implements RouterResult
{
    public static function make(): self
    {
        return new self();
    }

    private function __construct()
    {
    }

    #[\Override]
    public function isFound(): bool
    {
        return false;
    }

    #[\Override]
    public function getRouteMatch(): RouteMatch
    {
        throw new \LogicException('match was not found');
    }
}
