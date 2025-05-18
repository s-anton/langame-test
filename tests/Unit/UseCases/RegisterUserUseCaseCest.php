<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCases;

use App\Entity\ConfirmationCode;
use App\Entity\User;
use App\Tests\Support\UnitTester;
use App\UseCases\Auth\RegisterUserUseCase;

class RegisterUserUseCaseCest
{
    public function testRegisterUser(UnitTester $I): void
    {
        $username = $I->getFaker()->userName();
        $this->getUseCase($I)->execute($username, '123456');

        $user = $I->grabEntityFromRepository(User::class, ['username' => $username]);

        $I->assertInstanceOf(User::class, $user);
        $I->seeInRepository(ConfirmationCode::class, ['userId' => $user->getId()]);
    }

    private function getUseCase(UnitTester $I): RegisterUserUseCase
    {
        return $I->grabService(RegisterUserUseCase::class);
    }
}