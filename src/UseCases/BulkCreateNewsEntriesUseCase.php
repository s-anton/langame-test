<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Dto\HabrItemDto;
use App\Entity\NewsEntry;
use App\Events\NewsEntryCreated;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class BulkCreateNewsEntriesUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
        private NewsRepository $newsRepository,
    ) {
    }

    /**
     * @param HabrItemDto[] $datas
     * @return void
     * @throws ExceptionInterface
     */
    public function execute(array $datas, ?string $lastPublishedAt): void
    {
        if (empty($datas)) {
            return;
        }

        $lastEntry = null;
        $publishedAt = $lastPublishedAt !== null ? date_create($lastPublishedAt) : null;
        foreach ($datas as $data) {
            if ($publishedAt !== null && date_create($data->pubDate) <= $publishedAt) {
                continue;
            }
            if ($this->newsRepository->urlExists($data->url)) {
                return;
            }

            $entry = new NewsEntry(
                content: mb_substr($data->title, 0, 255),
                url: $data->url,
                publishedAt: date_create($data->pubDate)->format(\DateTimeInterface::ATOM),
            );

            $this->entityManager->persist($entry);
            $lastEntry = $entry;
        }

        $this->entityManager->flush();

        $this->messageBus->dispatch(new NewsEntryCreated($lastEntry->getId()));
    }
}