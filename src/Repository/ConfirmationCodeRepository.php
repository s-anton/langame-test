<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConfirmationCode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ConfirmationCodeRepository
{
    /** @var EntityRepository<ConfirmationCode> */
    private EntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $manager = $registry->getManagerForClass(ConfirmationCode::class);
        assert($manager instanceof EntityManagerInterface);
        $this->entityRepository = new EntityRepository(
            $manager,
            $manager->getClassMetadata(ConfirmationCode::class)
        );
    }

    /**
     * @return ConfirmationCode[]
     */
    public function getAll(): array
    {
        return $this->entityRepository->findAll();
    }
}