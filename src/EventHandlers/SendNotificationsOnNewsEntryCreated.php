<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\NewsEntryCreated;
use App\Mercure\Message\NewsEntryCreatedMessage;
use App\Repository\NewsRepository;
use App\Service\MercurePublisherService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationsOnNewsEntryCreated
{
    public function __construct(
        private NewsRepository $newsRepository,
        private MercurePublisherService $mercurePublisherService,
    ) {
    }

    public function __invoke(NewsEntryCreated $newsEntryCreated): void
    {
        $newsEntry = $this->newsRepository->getById($newsEntryCreated->id);
        if ($newsEntry === null) {
            return;
        }

        $message = new NewsEntryCreatedMessage($newsEntry->getId(), $newsEntry->getContent(), $newsEntry->getUrl());
        $this->mercurePublisherService->publish($message);
    }
}