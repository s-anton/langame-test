<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NewsEntry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NewsRepository
{
    /** @var EntityRepository<NewsEntry> */
    private EntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $manager = $registry->getManagerForClass(NewsEntry::class);
        assert($manager instanceof EntityManagerInterface);
        $this->entityRepository = new EntityRepository(
            $manager,
            $manager->getClassMetadata(NewsEntry::class)
        );
    }
}