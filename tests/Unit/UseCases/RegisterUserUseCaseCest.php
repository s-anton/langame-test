<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCases;

use App\Entity\ConfirmationCode;
use App\Entity\User;
use App\Events\UserCreated;
use App\Tests\Support\UnitTester;
use App\UseCases\Auth\RegisterUserUseCase;
use Codeception\Stub;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserUseCaseCest
{
    public function testRegisterUser(UnitTester $I): void
    {
        $username = $I->getFaker()->userName();
        $messageBusStub = Stub::makeEmpty(MessageBusInterface::class, [
            'dispatch' => Stub\Expected::once(fn (UserCreated $event) => new Envelope(new \stdClass()))
        ]);
        $this->getUseCase($I, $messageBusStub)->execute($username, '123456');

        $user = $I->grabEntityFromRepository(User::class, ['username' => $username]);

        $I->assertInstanceOf(User::class, $user);
        $I->seeInRepository(ConfirmationCode::class, ['userId' => $user->getId()]);
    }

    private function getUseCase(UnitTester $I, MessageBusInterface $messageBus): RegisterUserUseCase
    {
        return new RegisterUserUseCase(
            messageBus: $messageBus,
            entityManager: $I->grabService(EntityManagerInterface::class),
            passwordHasher: $I->grabService(UserPasswordHasherInterface::class)
        );
    }
}