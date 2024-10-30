<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Mailer;

use PhoneBurner\SaltLite\Framework\Configuration\Configuration;
use PhoneBurner\SaltLite\Framework\Container\MutableContainer;
use PhoneBurner\SaltLite\Framework\Container\ServiceProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MailerServiceProvider implements ServiceProvider
{
    #[\Override]
    public function register(MutableContainer $container): void
    {
        $container->set(
            MailerInterface::class,
            static function (MutableContainer $container): MailerInterface {
                if ($container->get(Configuration::class)->get('mailer.async')) {
                    return new Mailer(
                        $container->get(TransportInterface::class),
                        $container->get(MessageBusInterface::class),
                        $container->get(EventDispatcherInterface::class),
                    );
                }

                return new Mailer(
                    $container->get(TransportInterface::class),
                );
            },
        );

        $container->set(
            TransportInterface::class,
            static function (ContainerInterface $container): TransportInterface {
                $transport_driver = (string)$container->get(Configuration::class)->get('mailer.default');
                $transport_config = $container->get(Configuration::class)->get('mailer.drivers.' . $transport_driver) ?? [];

                $dns = match (TransportDriver::tryFrom($transport_driver)) {
                    TransportDriver::SendGrid => \vsprintf('sendgrid+api://%s@default', [
                        $transport_config['api_key'] ?? throw new \RuntimeException('Missing SendGrid API key'),
                    ]),
                    TransportDriver::Smtp => \vsprintf('smtp://%s:%s@%s:%s%s', [
                        $transport_config['user'] ?? throw new \RuntimeException('Missing SMTP Credentials'),
                        \urlencode($transport_config['pass'] ?? throw new \RuntimeException('Missing SMTP Credentials')),
                        $transport_config['host'] ?? throw new \RuntimeException('Missing SMTP Credentials'),
                        $transport_config['port'] ?? throw new \RuntimeException('Missing SMTP Credentials'),
                        $transport_config['encryption'] ? '' : '?auto_tls=false',
                    ]),
                    TransportDriver::None => 'null://default',
                    default => throw new \RuntimeException("Unknown mailer transport driver: {$transport_driver}"),
                };

                return Transport::fromDsn(
                    dsn: $dns,
                    dispatcher: $container->get(EventDispatcherInterface::class),
                    logger: $container->get(LoggerInterface::class),
                );
            },
        );
    }
}
