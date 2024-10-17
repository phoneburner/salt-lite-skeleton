<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router\Result;

use PhoneBurner\SaltLiteFramework\Router\Match\RouteMatch;
use PhoneBurner\SaltLiteFramework\Router\RouterResult;

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
