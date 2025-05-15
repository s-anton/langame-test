<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\UserVerified;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationOnUserVerified
{
    public function __invoke(UserVerified $userVerified): void
    {
        // TODO: Implement __invoke() method.
    }

}