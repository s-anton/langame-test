<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChatsRepository
{
    /** @var EntityRepository<Chat> */
    private EntityRepository $entityRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $manager = $registry->getManagerForClass(Chat::class);
        assert($manager instanceof EntityManagerInterface);
        $this->entityRepository = new EntityRepository(
            $manager,
            $manager->getClassMetadata(Chat::class)
        );
    }

    /**
     * @return iterable<Chat>
     */
    public function iterateOverAll(): iterable
    {
        return $this->entityRepository
            ->createQueryBuilder('chats')
            ->getQuery()
            ->toIterable();
    }

    public function findById(string $id): ?Chat
    {
        return $this->entityRepository->find($id);
    }
}