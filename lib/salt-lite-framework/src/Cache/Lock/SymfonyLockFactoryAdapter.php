<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache\Lock;

use PhoneBurner\SaltLiteFramework\Domain\Time\Ttl;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory as SymfonyLockFactory;

class SymfonyLockFactoryAdapter implements LockFactory, LoggerAwareInterface
{
    public function __construct(
        private readonly NamedKeyFactory $key_factory,
        private readonly SymfonyLockFactory $lock_factory,
    ) {
    }

    #[\Override]
    public function make(
        NamedKey|\Stringable|string $key,
        Ttl $ttl = new Ttl(300),
        bool $auto_release = true,
    ): SymfonyLockAdapter {
        return new SymfonyLockAdapter($this->lock_factory->createLockFromKey(
            $key instanceof NamedKey ? $key->key : $this->key_factory->make($key)->key,
            $ttl->seconds,
            $auto_release,
        ));
    }

    #[\Override]
    public function setLogger(LoggerInterface $logger): void
    {
        $this->lock_factory->setLogger($logger);
    }
}
