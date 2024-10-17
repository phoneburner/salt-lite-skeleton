<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Routing\Command\CommandHelper;

use PhoneBurner\SaltLiteFramework\Routing\Definition\RouteDefinition;

class SortByPath extends RouteDefinitionListSorter
{
    #[\Override]
    public function __invoke(RouteDefinition $a, RouteDefinition $b): int
    {
        return $this->sort_asc * ($a->getRoutePath() <=> $b->getRoutePath());
    }
}
