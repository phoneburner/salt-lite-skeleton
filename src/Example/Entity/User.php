<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteSkeleton\Example\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhoneBurner\SaltLiteFramework\Database\Doctrine\Orm\GeneratedValueStrategy;
use PhoneBurner\SaltLiteFramework\Database\Doctrine\Types;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[ORM\GeneratedValue(strategy: GeneratedValueStrategy::IDENTITY)]
    protected int $id;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
        protected string $username,
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
        protected \DateTimeImmutable $date_added = new \DateTimeImmutable(),
    ) {
    }
}
