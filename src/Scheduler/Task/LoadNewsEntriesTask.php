<?php

declare(strict_types=1);

namespace App\Scheduler\Task;

use App\Repository\NewsRepository;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '1 minute', jitter: 10)]
readonly class LoadNewsEntriesTask
{
    public function __construct(private NewsRepository $newsRepository)
    {
    }

    public function __invoke(): void
    {
        $lastEntry = $this->newsRepository->getLastRecord();
    }
}