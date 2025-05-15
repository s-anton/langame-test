<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ConfirmationCodeRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ConfirmationCodeRepository::class)]
#[Table(name: 'confirmation_codes')]
class ConfirmationCode
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(type: 'string', length: 6)]
    private string $code;

    #[Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'integer')]
    private int $userId;

    public function __construct(string $code, int $userId)
    {
        $this->code = $code;
        $this->userId = $userId;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}