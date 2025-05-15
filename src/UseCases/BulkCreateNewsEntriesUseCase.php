<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Dto\HabrItemDto;
use App\Entity\NewsEntry;
use App\Events\NewsEntryCreated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class BulkCreateNewsEntriesUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus
    ) {
    }

    /**
     * @param HabrItemDto[] $datas
     * @return void
     * @throws ExceptionInterface
     */
    public function execute(array $datas, ?string $lastUrl): void
    {
        if (empty($datas)) {
            return;
        }

        $lastEntry = null;
        foreach ($datas as $data) {
            if ($data->url === $lastUrl) {
                break;
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