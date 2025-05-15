<?php

declare(strict_types=1);

namespace App\Mercure\Message;

class UserVerifiedMessage implements MessageInterface
{
    private const TYPE = 'user_verified';

    public function __construct(private int $userId, private string $usermame)
    {
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->userId,
            'username' => $this->usermame,
        ];
    }
}