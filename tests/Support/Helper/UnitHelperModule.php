<?php

declare(strict_types=1);

namespace App\Tests\Support\Helper;

use Codeception\Module;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UnitHelperModule extends Module
{
    private array $mocks = [];

    /**
     * @template T
     * @param class-string<T> $serviceId
     * @return T
     */
    public function grabService(string $serviceId): mixed
    {
        return $this->getModule('Symfony')->grabService($serviceId);
    }

    public function grabEntityFromRepository(string $entity, array $params): mixed
    {
        $em = $this->grabService(EntityManagerInterface::class);
        $entityRepository = new EntityRepository(
            em: $em,
            class: $em->getClassMetadata($entity)
        );

        return $entityRepository->findOneBy($params);
    }

    public function seeInRepository(string $entity, array $params): void
    {
        $res = $this->grabEntityFromRepository($entity, $params);
        $this->assert(['True', $res instanceof $entity, "$entity with params" . json_encode($params)]);
    }

    public function dontSeeInRepository(string $entity, array $params): void
    {
        $res = $this->grabEntityFromRepository($entity, $params);
        $this->assertNot(['True', $res instanceof $entity, "$entity with params" . json_encode($params)]);
    }
}