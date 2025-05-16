<?php

declare(strict_types=1);

namespace App\Service;

use App\Mercure\Message\MessageInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercurePublisherService
{
    public function __construct(
        private HubInterface $hub,
        private string $appDomain
    ) {
    }

    public function publish(MessageInterface $message): void
    {
        $update = new Update(
            sprintf('https://%s/update', $this->appDomain),
            json_encode([
                'type' => $message->getType(),
                'data' => $message
            ])
        );
        $this->hub->publish($update);
    }
}