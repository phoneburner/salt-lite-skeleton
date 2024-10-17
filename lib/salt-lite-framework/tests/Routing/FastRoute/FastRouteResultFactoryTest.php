<?php

declare(strict_types=1);

namespace PhoneBurner\Tests\SaltLiteFramework\Routing\FastRoute;

use FastRoute\Dispatcher;
use PhoneBurner\SaltLiteFramework\Http\Domain\HttpMethod;
use PhoneBurner\SaltLiteFramework\Routing\Definition\RouteDefinition;
use PhoneBurner\SaltLiteFramework\Routing\FastRoute\FastRouteMatch;
use PhoneBurner\SaltLiteFramework\Routing\FastRoute\FastRouteResultFactory as SUT;
use PhoneBurner\SaltLiteFramework\Routing\Match\RouteMatch;
use PhoneBurner\SaltLiteFramework\Routing\Result\MethodNotAllowed;
use PhoneBurner\SaltLiteFramework\Routing\Result\RouteFound;
use PhoneBurner\SaltLiteFramework\Routing\Result\RouteNotFound;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FastRouteResultFactoryTest extends TestCase
{
    private SUT $sut;

    #[\Override]
    protected function setUp(): void
    {
        $this->sut = new SUT();
    }

    #[Test]
    public function make_returns_MethodNotAllowed(): void
    {
        $result = $this->sut->make(FastRouteMatch::make([
            Dispatcher::METHOD_NOT_ALLOWED,
            [HttpMethod::Get, HttpMethod::Post],
        ]));

        self::assertInstanceOf(MethodNotAllowed::class, $result);
        self::assertEquals([
            HttpMethod::Get,
            HttpMethod::Post,
        ], $result->getAllowedMethods());
    }

    #[Test]
    public function make_returns_RouteNotFound(): void
    {
        $result = $this->sut->make(FastRouteMatch::make([
            Dispatcher::NOT_FOUND,
        ]));

        self::assertInstanceOf(RouteNotFound::class, $result);
    }

    #[Test]
    public function make_returns_RouteFound(): void
    {
        $route = RouteDefinition::all('/test', ['test' => 'value']);

        $result = $this->sut->make(FastRouteMatch::make([
            Dispatcher::FOUND,
            \serialize($route),
            ['path' => 'value'],
        ]));

        self::assertInstanceOf(RouteFound::class, $result);
        self::assertEquals(
            RouteMatch::make($route, ['path' => 'value']),
            $result->getRouteMatch(),
        );
    }
}
