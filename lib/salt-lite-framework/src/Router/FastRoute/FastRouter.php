<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Router\FastRoute;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use LogicException;
use PhoneBurner\SaltLiteFramework\Router\Definition\DefinitionList;
use PhoneBurner\SaltLiteFramework\Router\Result\RouteFound;
use PhoneBurner\SaltLiteFramework\Router\Result\RouteNotFound;
use PhoneBurner\SaltLiteFramework\Router\Router;
use PhoneBurner\SaltLiteFramework\Router\RouterResult;
use Psr\Http\Message\ServerRequestInterface;

class FastRouter implements Router
{
    private Dispatcher|null $dispatcher = null;

    public function __construct(
        private readonly DefinitionList $definition_list,
        private readonly FastRouteDispatcherFactory $dispatcher_factory,
        private readonly FastRouteResultFactory $found_route_factory,
    ) {
    }

    #[\Override]
    public function resolveByName(string $name): RouterResult
    {
        try {
            $definition = $this->definition_list->getNamedRoute($name);
            return RouteFound::make($definition);
        } catch (LogicException) {
            return RouteNotFound::make();
        }
    }

    #[\Override]
    public function resolveForRequest(ServerRequestInterface $request): RouterResult
    {
        return $this->found_route_factory->make(FastRouteMatch::make(
            $this->dispatcher()->dispatch($request->getMethod(), $request->getUri()->getPath()),
        ));
    }

    public function dispatcher(): Dispatcher
    {
        return $this->dispatcher ??= $this->dispatcher_factory->make(function (RouteCollector $collector): void {
            foreach ($this->definition_list as $definition) {
                $collector->addRoute(
                    $definition->getMethods(),
                    $definition->getRoutePath(),
                    \serialize($definition),
                );
            }
        });
    }
}
