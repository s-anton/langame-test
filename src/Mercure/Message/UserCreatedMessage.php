<?php

declare(strict_types=1);

namespace App\Mercure\Message;

readonly class UserCreatedMessage implements MessageInterface
{
    private const TYPE = 'user_created';

    public function __construct(
        public int $userId,
        public string $username
    ) {
    }

    /**
     * @return array{userId: int, username: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'userId' => $this->userId,
            'username' => $this->username,
        ];
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}