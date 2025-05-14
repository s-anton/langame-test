<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\UserCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class SendVerificationCodeOnUserCreated
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function __invoke(UserCreated $userVerified): void
    {
        // do something
    }
}