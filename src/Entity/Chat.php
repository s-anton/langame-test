<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChatsRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ChatsRepository::class)]
#[Table(name: 'chats')]
class Chat
{
    #[Id]
    #[Column(type: 'string', length: 11)]
    private string $id;
    #[Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $id) {
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}