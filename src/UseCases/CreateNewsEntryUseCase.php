<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Entity\NewsEntry;
use App\Events\NewsEntryCreated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateNewsEntryUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus
    ) {
    }

    public function execute(
        string $content,
        string $url,
        string $publishedAt
    ): NewsEntry {
        $entry = new NewsEntry($content, $url, $publishedAt);

        $this->entityManager->persist($entry);
        $this->entityManager->flush();

        $this->messageBus->dispatch(new NewsEntryCreated($entry->getId()));

        return $entry;
    }
}