<?php

declare(strict_types=1);

namespace App\Events;

readonly class UserCreated
{
    public function __construct(public ?int $userId)
    {
    }
}