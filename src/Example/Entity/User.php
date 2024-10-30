<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\App\Example\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhoneBurner\SaltLite\Framework\Database\Doctrine\Orm\GeneratedValueStrategy;
use PhoneBurner\SaltLite\Framework\Database\Doctrine\Types;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[ORM\GeneratedValue(strategy: GeneratedValueStrategy::IDENTITY)]
    public readonly int $id;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
        public string $username,
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
        public \DateTimeImmutable $date_added = new \DateTimeImmutable(),
    ) {
    }
}
