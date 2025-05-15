<?php

declare(strict_types=1);

namespace App\Mercure\Message;

readonly class NewsEntryCreatedMessage implements MessageInterface
{
    private const TYPE = 'news-entry_created';

    public function __construct(private int $newsEntryId, private string $content, private string $url)
    {
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->newsEntryId,
            'content' => $this->content,
            'url' => $this->url,
        ];
    }
}