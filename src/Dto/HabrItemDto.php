<?php

declare(strict_types=1);

namespace App\Dto;

readonly class HabrItemDto
{
    public function __construct(
        public string $title,
        public string $url,
        public string $pubDate
    ) {
    }
}