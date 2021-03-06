<?php

namespace Todo\Lib\Factory\Repository;

use PDO;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Repository\EntityPdoRepository;
use Todo\Lib\Repository\EntityRepositoryInterface;

class EntityPdoRepositoryFactory implements EntityRepositoryFactoryInterface
{
    private PDO $pdo;
    private EntityFactoryInterface $entityFactory;
    private string $entityPerPage;
    private string $entityName;

    public function __construct(
        Pdo $pdo,
        EntityFactoryInterface $entityFactory,
        string $entityPerPage,
        string $entityName
    )
    {
        $this->pdo = $pdo;
        $this->entityFactory = $entityFactory;
        $this->entityPerPage = $entityPerPage;
        $this->entityName = $entityName;
    }

    public function createRepository(): EntityRepositoryInterface
    {
        return new EntityPdoRepository(
            $this->pdo,
            $this->entityFactory,
            $this->entityPerPage,
            $this->entityName
        );
    }
}
