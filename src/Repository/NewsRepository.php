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

    public function getLastRecord(): ?NewsEntry
    {
        return $this->entityRepository
            ->createQueryBuilder('news')
            ->orderBy('news.publishedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getById(int $id): ?NewsEntry
    {
        return $this->entityRepository->find($id);
    }

    /**
     * @return NewsEntry[]
     */
    public function iterateUsingCursor(string $lastPublishedAt, string $term, string $direction): array
    {
        $query = $this->entityRepository
            ->createQueryBuilder('news');

        if ($direction === 'forward') {
            $query->where('news.publishedAt < :publishedAt')->orderBy('news.publishedAt', 'DESC');
        } else {
            $query->where('news.publishedAt > :publishedAt')->orderBy('news.publishedAt', 'ASC');
        }

        if (!empty($term)) {
            $query->andWhere('news.content LIKE :term')->setParameter('term', '%' . $term . '%');
        }

        $result = $query
            ->setParameter('publishedAt', $lastPublishedAt)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        if ($direction === 'back') {
            $result = array_reverse($result);
        }

        return $result;
    }

    public function getMinMaxPublishedAt(string $term): array
    {
        $query = $this->entityRepository
            ->createQueryBuilder('news')
            ->select(['MAX(news.publishedAt) as max', 'MIN(news.publishedAt) as min']);

        if (!empty($term)) {
            $query->andWhere('news.content LIKE :term')->setParameter('term', '%' . $term . '%');
        }

        return $query->getQuery()->getSingleResult();
    }

    public function urlExists(string $url): bool
    {
        return $this->entityRepository->findOneBy(['url' => $url]) instanceof NewsEntry;
    }
}