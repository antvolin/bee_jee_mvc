<?php

namespace Tests\Lib\Factory\Repository;

use PDO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Todo\Lib\App;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use Todo\Lib\Repository\EntityPdoRepository;

class EntityPdoRepositoryFactoryTest extends TestCase
{
    private int $entityPerPage;
    private string $entityName;
    private MockObject $pdo;

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
        $repositoryFactory = new EntityPdoRepositoryFactory(
            $this->pdo,
            $entityFactory,
            $this->entityPerPage,
            $this->entityName
        );

        $repository = $repositoryFactory->createRepository();

        $this->assertInstanceOf(EntityPdoRepository::class, $repository);
    }
}
