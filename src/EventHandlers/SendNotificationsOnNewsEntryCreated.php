<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\NewsEntryCreated;
use App\Repository\NewsRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationsOnNewsEntryCreated
{
    public function __construct(
        private HubInterface $hub,
        private NewsRepository $newsRepository,
        private string $appDomain
    ) {
    }

    public function __invoke(NewsEntryCreated $newsEntryCreated): void
    {
        $newsEntry = $this->newsRepository->getById($newsEntryCreated->id);
        if ($newsEntry === null) {
            return;
        }

        $update = new Update(
            sprintf('http://%s/news', $this->appDomain),
            json_encode([
                'id' => $newsEntry->getId(),
                'content' => $newsEntry->getContent(),
                'url' => $newsEntry->getUrl(),
            ])
        );
        $this->hub->publish($update);
    }

}