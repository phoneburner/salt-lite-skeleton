<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Tests\Behat;

use Behat\Hook\AfterScenario;
use Behat\Hook\BeforeScenario;
use Doctrine\DBAL\Connection;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use PhoneBurner\SaltLite\App\Tests\Unit\TestSupport\MockEmitter;
use PhoneBurner\SaltLite\Framework\App\App;
use PhoneBurner\SaltLite\Framework\App\Context;
use PhoneBurner\SaltLite\Framework\Container\MutableContainer;
use PhoneBurner\SaltLite\Framework\Container\ServiceContainer;
use PhoneBurner\SaltLite\Framework\Http\HttpKernel;
use PhoneBurner\SaltLite\Framework\Logging\LogTrace;
use PhoneBurner\SaltLite\Framework\Util\Helper\Uuid;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait HasApplicationLifecycle
{
    private ServiceContainer|null $services = null;

    private ResponseInterface|null $response = null;

    #[BeforeScenario]
    public function bootApp(): void
    {
        $this->services = App::bootstrap(Context::Test)->services;
        $this->services->get(Connection::class)->beginTransaction();
        $this->services->set(LogTrace::class, new LogTrace(Uuid::nil()));
        $this->services->set(EmitterInterface::class, new MockEmitter());
    }

    #[AfterScenario]
    public function teardownApplication(): void
    {
        $this->services?->get(Connection::class)->rollBack();
        unset($this->services);
        App::teardown();
    }

    protected function container(): MutableContainer
    {
        return $this->services ?? throw new \RuntimeException('Container not initialized');
    }

    protected function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->container()->get(HttpKernel::class)->run($request);
        $emitter = $this->container()->get(EmitterInterface::class);

        Assert::assertInstanceOf(MockEmitter::class, $emitter);
        Assert::assertInstanceOf(ResponseInterface::class, $emitter->response);

        return $emitter->response;
    }

    protected function response(): ResponseInterface
    {
        return $this->response ?? throw new \RuntimeException('Response not initialized');
    }
}
