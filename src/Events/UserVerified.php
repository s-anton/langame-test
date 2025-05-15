<?php

declare(strict_types=1);

namespace App\Events;

readonly class UserVerified
{
    public function __construct(public int $userId)
    {
    }
}