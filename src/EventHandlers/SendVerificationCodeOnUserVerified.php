<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\Sync\UserVerified;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendVerificationCodeOnUserVerified
{
    public function __invoke(UserVerified $userVerified): void
    {
        // put to async for sending
    }
}