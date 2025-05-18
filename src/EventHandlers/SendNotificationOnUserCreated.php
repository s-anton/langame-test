<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\UserCreated;
use App\Mercure\Message\UserCreatedMessage;
use App\Repository\UserRepository;
use App\Service\MercurePublisherService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationOnUserCreated
{
    public function __construct(
        private UserRepository $userRepository,
        private MercurePublisherService $mercurePublisherService
    ) {
    }

    public function __invoke(UserCreated $userCreated): void
    {
        if ($userCreated->userId === null) {
            return;
        }

        $user = $this->userRepository->find($userCreated->userId);
        if ($user === null) {
            return;
        }

        $message = new UserCreatedMessage($user->getId(), $user->getUsername());
        $this->mercurePublisherService->publish($message);
    }
}