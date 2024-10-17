<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\Command\CommandHelper;

use PhoneBurner\SaltLiteFramework\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLiteFramework\Routing\Route;

class SortByName extends RouteDefinitionListSorter
{
    #[\Override]
    public function __invoke(RouteDefinition $a, RouteDefinition $b): int
    {
        return $this->sort_asc * ($a->getAttribute(Route::class) <=> $b->getAttribute(Route::class));
    }
}
