<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Entity\User;
use App\Events\UserVerified;
use App\Repository\ConfirmationCodeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ConfirmUserUseCase
{
    public function __construct(
        private ConfirmationCodeRepository $confirmationCodeRepository,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function execute(string $code): ?User
    {
        $confirmationCode = $this->confirmationCodeRepository->findOneByCode($code);
        if ($confirmationCode === null) {
            return null;
        }

        $user = $this->userRepository->find($confirmationCode->getUserId());
        if ($user === null) {
            return null;
        }

        $user->verify();

        $this->entityManager->remove($confirmationCode);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new UserVerified((int) $user->getId()));

        return $user;
    }
}