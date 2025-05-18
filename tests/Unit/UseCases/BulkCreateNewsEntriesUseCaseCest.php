<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCases;

use App\Dto\HabrItemDto;
use App\Entity\NewsEntry;
use App\Tests\Support\UnitTester;
use App\UseCases\BulkCreateNewsEntriesUseCase;

class BulkCreateNewsEntriesUseCaseCest
{
    public function testSuccessCreate(UnitTester $I): void
    {
        $item1 = new HabrItemDto('first', 'https://ya.ru', date_create()->format(DATE_ATOM));
        $item2 = new HabrItemDto('second', 'https://vk.ru', date_create()->format(DATE_ATOM));
        $item3 = new HabrItemDto('third', 'https://fb.ru', date_create()->format(DATE_ATOM));

        $this->getUseCase($I)->execute([$item1, $item2, $item3], null);

        $I->seeInRepository(NewsEntry::class, ['content' => 'first', 'url' => 'https://ya.ru',]);
        $I->seeInRepository(NewsEntry::class, ['content' => 'second', 'url' => 'https://vk.ru',]);
        $I->seeInRepository(NewsEntry::class, ['content' => 'third', 'url' => 'https://fb.ru',]);
    }

    public function testOlderNewsEntriesWillNotCreate(UnitTester $I): void
    {
        $item1 = new HabrItemDto('first', 'https://ya.ru', date_create()->format(DATE_ATOM));
        $item2 = new HabrItemDto('second', 'https://vk.ru', date_create('-1 day')->format(DATE_ATOM));

        $this->getUseCase($I)->execute([$item1, $item2], date_create('-10 minute')->format(DATE_ATOM));

        $I->seeInRepository(NewsEntry::class, ['content' => 'first', 'url' => 'https://ya.ru',]);
        $I->dontSeeInRepository(NewsEntry::class, ['content' => 'second']);
    }

    public function testNotCreatesWithExistingUrl(UnitTester $I): void
    {
        $item1 = new HabrItemDto('first', 'https://ya.ru', date_create()->format(DATE_ATOM));
        $this->getUseCase($I)->execute([$item1], null);

        $item2 = new HabrItemDto('second', 'https://ya.ru', date_create()->format(DATE_ATOM));
        $this->getUseCase($I)->execute([$item2], null);

        $I->seeInRepository(NewsEntry::class, ['content' => 'first', 'url' => 'https://ya.ru',]);
        $I->dontSeeInRepository(NewsEntry::class, ['content' => 'second']);
    }

    private function getUseCase(UnitTester $I): BulkCreateNewsEntriesUseCase
    {
        return $I->grabService(BulkCreateNewsEntriesUseCase::class);
    }
}