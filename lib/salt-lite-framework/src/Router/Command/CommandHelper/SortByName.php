<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router\Command\CommandHelper;

use PhoneBurner\SaltLiteFramework\Router\Definition\RouteDefinition;
use PhoneBurner\SaltLiteFramework\Router\Route;

class SortByName extends RouteDefinitionListSorter
{
    #[\Override]
    public function __invoke(RouteDefinition $a, RouteDefinition $b): int
    {
        return $this->sort_asc * ($a->getAttribute(Route::class) <=> $b->getAttribute(Route::class));
    }
}
