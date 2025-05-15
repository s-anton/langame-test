<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Events\UserVerified;
use App\Repository\ConfirmationCodeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;

class ConfirmUserUseCase
{
    public function __construct(
        private ConfirmationCodeRepository $confirmationCodeRepository,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function execute(string $code): bool
    {
        $confirmationCode = $this->confirmationCodeRepository->findByCode($code);
        if ($confirmationCode === null) {
            return false;
        }

        $user = $this->userRepository->find($confirmationCode->getUserId());
        if ($user === null) {
            return false;
        }

        $user->verify();

        $this->entityManager->remove($confirmationCode);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new UserVerified((int) $user->getId()));

        $this->security->login($user);

        return true;
    }
}