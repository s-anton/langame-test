<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCases;

use App\Entity\Chat;
use App\Tests\Support\UnitTester;
use App\UseCases\HandleTelegramWebhookDataUseCase;

class HandleTelegramWebhookDataUseCaseCest
{
    public function testSaveChatId(UnitTester $I): void
    {
        /** @var string $data */
        $data = json_encode(['message' => ['chat' => ['id' => -100]]]);
        $this->getService($I)->execute($data);

        $I->seeInRepository(Chat::class, ['id' => '-100']);
    }

    private function getService(UnitTester $I): HandleTelegramWebhookDataUseCase
    {
        return $I->grabService(HandleTelegramWebhookDataUseCase::class);
    }
}