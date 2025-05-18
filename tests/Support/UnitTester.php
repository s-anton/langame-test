<?php

declare(strict_types=1);

namespace App\Tests\Support;

use App\Entity\ConfirmationCode;
use App\Entity\User;
use Faker\Factory;
use Faker\Generator;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

    public function getFaker(): Generator
    {
        return Factory::create();
    }

    public function createUser(
        ?string $username = null,
        ?int $isVerified = null,
        ?string $password = null,
    ): User {
        $faker = $this->getFaker();
        $id = $this->haveInRepository(User::class, [
            'username' => $username ?? $faker->userName(),
            'isVerified' => $isVerified ?? $faker->numberBetween(0, 1),
            'createdAt' => new \DateTimeImmutable(),
            'password' => $password ?? $faker->password(),
        ]);

        return $this->grabEntityFromRepository(User::class, ['id' => $id]);
    }

    public function createConfirmationCode(int $userId, ?string $code = null): ConfirmationCode
    {
        $id = $this->haveInRepository(ConfirmationCode::class, [
            'code' => $code ?? strval($this->getFaker()->randomNumber(6)),
            'userId' => $userId,
            'createdAt' => new \DateTimeImmutable(),
        ]);

        return $this->grabEntityFromRepository(ConfirmationCode::class, ['id' => $id]);
    }
}
