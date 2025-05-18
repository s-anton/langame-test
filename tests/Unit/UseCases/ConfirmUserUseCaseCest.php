<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCases;

use App\Entity\User;
use App\Events\UserVerified;
use App\Repository\ConfirmationCodeRepository;
use App\Repository\UserRepository;
use App\Tests\Support\UnitTester;
use App\UseCases\ConfirmUserUseCase;
use Codeception\Stub;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ConfirmUserUseCaseCest
{
    public function testSuccessConfirm(UnitTester $I): void
    {
        $user = $I->createUser(isVerified: 0);
        $confirmationCode = $I->createConfirmationCode((int) $user->getId());
        $messageBusStub = Stub::makeEmpty(MessageBusInterface::class, [
            'dispatch' => Stub\Expected::once(function (UserVerified $userVerified) use ($user, $I) {
                $I->assertEquals($user->getId(), $userVerified->userId);
                return new Envelope(new \stdClass());
            })
        ]);

        $this->getUseCase($I, $messageBusStub)->execute($confirmationCode->getCode());

        $I->seeInRepository(User::class, ['username' => $user->getUsername(), 'isVerified' => 1]);
    }

    public function testFailureConfirm(UnitTester $I): void
    {
        $user = $I->createUser(isVerified: 0);
        $I->createConfirmationCode((int) $user->getId(), '123456');
        $messageBusStub = Stub::makeEmpty(MessageBusInterface::class, [
            'dispatch' => Stub\Expected::never()
        ]);

        $this->getUseCase($I, $messageBusStub)->execute('654321');

        $I->seeInRepository(User::class, ['username' => $user->getUsername(), 'isVerified' => 0]);
    }

    private function getUseCase(UnitTester $I, MessageBusInterface $messageBus): ConfirmUserUseCase
    {
        return new ConfirmUserUseCase(
            confirmationCodeRepository: $I->grabService(ConfirmationCodeRepository::class),
            userRepository: $I->grabService(UserRepository::class),
            entityManager: $I->grabService(EntityManagerInterface::class),
            messageBus: $messageBus
        );
    }
}