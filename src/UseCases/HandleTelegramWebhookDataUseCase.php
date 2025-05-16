<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Entity\Chat;
use App\Repository\ChatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class HandleTelegramWebhookDataUseCase
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
        private ChatsRepository $chatRepository,
    ) {
    }

    public function execute(string $data): void
    {
        if (empty($data)) {
            return;
        }

        $decoded = json_decode($data, true);
        if (!is_array($decoded)) {
            $this->logger->warning('Data is not an array', ['data' => $data]);
            return;
        }

        $chatId = $decoded['message']['chat']['id'] ?? null;
        if ($chatId === null) {
            $this->logger->warning('Unknown structure of data', ['data' => $data]);
        }

        if ($this->chatRepository->findById(strval($chatId)) instanceof Chat) {
            return;
        }

        $chat = new Chat(strval($chatId));

        $this->entityManager->persist($chat);
        $this->entityManager->flush();
    }
}