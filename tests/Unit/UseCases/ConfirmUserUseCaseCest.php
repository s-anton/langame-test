<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCases;

use App\Entity\User;
use App\Tests\Support\UnitTester;
use App\UseCases\ConfirmUserUseCase;
use Codeception\Stub;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

class ConfirmUserUseCaseCest
{
    public function testSuccessConfirm(UnitTester $I): void
    {
        $user = $I->createUser(isVerified: 0);
        $confirmationCode = $I->createConfirmationCode((int) $user->getId());

        $this->getUseCase($I)->execute($confirmationCode->getCode());

        $I->seeInRepository(User::class, ['username' => $user->getUsername(), 'isVerified' => 1]);
    }

    public function testFailureConfirm(UnitTester $I): void
    {
        $user = $I->createUser(isVerified: 0);
        $I->createConfirmationCode((int) $user->getId(), '123456');

        $this->getUseCase($I)->execute('654321');

        $I->seeInRepository(User::class, ['username' => $user->getUsername(), 'isVerified' => 0]);
    }

    private function getUseCase(UnitTester $I): ConfirmUserUseCase
    {
        return $I->grabService(ConfirmUserUseCase::class);
    }
}