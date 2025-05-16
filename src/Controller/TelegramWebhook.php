<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/telegram/webhook/{token}', name: 'telegram_webhook')]
class TelegramWebhook
{
    public function __construct(private string $telegramToken)
    {
    }

    public function __invoke(string $token): Response
    {
        if ($token !== $this->telegramToken) {
            return new JsonResponse([]);
        }

        $data = file_get_contents('php://input');
        file_put_contents('/tmp/tg.txt', $data);

        return new JsonResponse([]);
    }
}