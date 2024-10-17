<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router\Definition;

interface RouteGroupDefinitionProcessor
{
    public function __invoke(RouteGroupDefinition $definition): RouteGroupDefinition;
}
