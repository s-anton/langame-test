<?php

declare(strict_types=1);

namespace App\Scheduler\Task;

use App\Entity\NewsEntry;
use App\Repository\NewsRepository;
use App\Service\HabrRssService;
use App\UseCases\BulkCreateNewsEntriesUseCase;
use jcobhams\NewsApi\NewsApi;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '60 seconds', jitter: 6)]
readonly class LoadNewsEntriesTask
{
    public function __construct(
        private BulkCreateNewsEntriesUseCase $bulkCreateNewsEntriesUseCase,
        private HabrRssService $habrRssService,
        private NewsRepository $newsRepository,
    ) {
    }

    public function __invoke(): void
    {
        $items = $this->habrRssService->getItems();
        $lastNewsEntry = $this->newsRepository->getLastRecord();

        $this->bulkCreateNewsEntriesUseCase->execute(
            $items,
            $lastNewsEntry?->getUrl()
        );
    }
}