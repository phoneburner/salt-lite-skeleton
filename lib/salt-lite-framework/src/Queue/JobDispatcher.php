<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Queue;

use PhoneBurner\SaltLite\Framework\Attribute\Contract;

#[Contract]
interface JobDispatcher
{
    /**
     * @param Job&callable $job
     */
    public function dispatch(Job $job): void;
}
