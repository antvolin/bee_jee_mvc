<?php

namespace Tests\Lib\Factory\Repository;

use PDO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactoryInterface;

class EntityPdoRepositoryFactoryTest extends TestCase
{
    /**
     * @var int
     */
    private $entityPerPage;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var MockObject
     */
    private $pdo;

    protected function setUp()
    {
        $this->entityPerPage = 3;
        $this->entityName = App::getEntityName();
        $this->pdo = $this->createMock(PDO::class);
    }

    /**
     * @test
     */
    public function shouldBeCreatableEntityPdoRepository(): void
    {
        $entityFactory = $this->createMock(EntityFactoryInterface::class);
        $repositoryFactory = new EntityPdoRepositoryFactoryInterface($this->pdo, $entityFactory);

        $repository = $repositoryFactory->create($this->entityPerPage, $this->entityName);

        $this->assertTrue(method_exists($repository, 'getEntityById'));
        $this->assertTrue(method_exists($repository, 'getCountEntities'));
        $this->assertTrue(method_exists($repository, 'getEntities'));
        $this->assertTrue(method_exists($repository, 'addEntity'));
        $this->assertTrue(method_exists($repository, 'deleteEntity'));
    }
}
