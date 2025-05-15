<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\HabrItemDto;

class HabrRssService
{
    public function __construct(private string $rssFeed)
    {
    }

    /**
     * @return HabrItemDto[]
     */
    public function getItems(): array
    {
        $dom = simplexml_load_file($this->rssFeed);
        $result = [];
        foreach ($dom->channel->item as $item) {
            $result[] = new HabrItemDto(
                title: (string) $item->title,
                url: (string) $item->link,
                pubDate: (string) $item->pubDate,
            );
        }

        return $result;
    }
}