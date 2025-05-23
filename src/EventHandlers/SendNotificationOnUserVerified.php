<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\UserVerified;
use App\Mercure\Message\UserVerifiedMessage;
use App\Repository\UserRepository;
use App\Service\MercurePublisherService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationOnUserVerified
{
    public function __construct(
        private UserRepository $userRepository,
        private MercurePublisherService $mercurePublisherService
    ) {
    }

    public function __invoke(UserVerified $userVerified): void
    {
        $user = $this->userRepository->find($userVerified->userId);
        if ($user === null) {
            return;
        }

        $message = new UserVerifiedMessage($user->getId(), $user->getUsername());
        $this->mercurePublisherService->publish($message);
    }
}