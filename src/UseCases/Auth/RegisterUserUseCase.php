<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Entity\ConfirmationCode;
use App\Entity\User;
use App\Events\UserCreated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserUseCase
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function execute(string $username, string $plainPassword): void
    {
        $user = new User();
        $user->setUsername($username);
        // encode the plain password
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $code = random_int(100000, 999999);
        $verificationCode = new ConfirmationCode(
            code: strval($code),
            userId: intval($user->getId())
        );

        $this->entityManager->persist($verificationCode);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new UserCreated($user->getId()));
    }
}