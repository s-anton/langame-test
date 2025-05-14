<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: NewsRepository::class)]
#[Table(name: 'news')]
class NewsEntry
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(type: 'string', length: 255)]
    private string $content;
    #[Column(type: 'string', length: 255)] // может быть длиннее, да и ладно, для теста пойдет
    private string $url;
    private string $publishedAt;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        string $content,
        string $url,
        string $publishedAt,
    ) {
        $this->url = $url;
        $this->content = $content;
        $this->publishedAt = $publishedAt;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}