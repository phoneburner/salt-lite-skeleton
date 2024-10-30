<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Routing\Command\CommandHelper;

use PhoneBurner\SaltLite\Framework\Routing\Definition\RouteDefinition;

abstract class RouteDefinitionListSorter
{
    protected int $sort_asc;

    final public function __construct(bool $sort_asc = true)
    {
        $this->sort_asc = $sort_asc ? 1 : -1;
    }

    abstract public function __invoke(RouteDefinition $a, RouteDefinition $b): int;
}
