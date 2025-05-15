<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Entity\ConfirmationCode;
use App\Events\UserCreated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendVerificationCodeOnUserCreated
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(UserCreated $userCreated): void
    {
        if ($userCreated->userId === null) {
            return;
        }


        $this->entityManager->flush();
    }
}