<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Mailer;

enum TransportDriver: string
{
    case Smtp = 'smtp';
    case SendGrid = 'sendgrid';
    case None = 'none';
}
