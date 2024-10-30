<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Queue;

use PhoneBurner\SaltLiteFramework\Attribute\Contract;

#[Contract]
interface JobDispatcher
{
    /**
     * @param Job&callable $job
     */
    public function dispatch(Job $job): void;
}
