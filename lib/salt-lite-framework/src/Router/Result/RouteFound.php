<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router\Result;

use PhoneBurner\SaltLiteFramework\Router\Definition\RouteDefinition;
use PhoneBurner\SaltLiteFramework\Router\Match\RouteMatch;
use PhoneBurner\SaltLiteFramework\Router\RouterResult;

class RouteFound implements RouterResult
{
    private readonly RouteMatch $match;

    public static function make(RouteDefinition $definition, array $path_parameters = []): self
    {
        return new self($definition, $path_parameters);
    }

    private function __construct(RouteDefinition $definition, array $path_parameters)
    {
        $this->match = RouteMatch::make($definition, $path_parameters);
    }

    #[\Override]
    public function isFound(): bool
    {
        return true;
    }

    #[\Override]
    public function getRouteMatch(): RouteMatch
    {
        return $this->match;
    }
}
