<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\Database\DatabaseException;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

abstract class BaseRepository
{
    public Connection $connection;
    private ManagerRegistry $managerRegistry;
    protected ObjectRepository $objectRepository;

    public function __construct(Connection $connection, ManagerRegistry $managerRegistry)
    {
        $this->connection = $connection;
        $this->managerRegistry = $managerRegistry;
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    /**
     * @return ObjectManager|EntityManagerInterface
     */
    public function getEntityManager()
    {
        $entityManager = $this->managerRegistry->getManager();

        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    }

    abstract protected static function entityClass(): string;

    protected function saveEntity(object $entity): void
    {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            if ($e instanceof UniqueConstraintViolationException) {
                throw $e;
            }
            throw DatabaseException::savingEntity($e->getMessage());
        }
    }
}
