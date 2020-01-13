<?php

namespace BeeJeeMVC\Tests\Lib\Factory\Repository;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Factory\Repository\EntityFileRepositoryFactory;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use PHPUnit\Framework\TestCase;

class EntityFileRepositoryFactoryTest extends TestCase
{
    /**
     * @var int
     */
    protected $entityPerPage;

    protected function setUp()
    {
        $this->entityPerPage = 3;
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeCreatedEntityFileRepository(): void
    {
        $app = new App();
        $factory = new EntityFileRepositoryFactory($app->getEntityName());
        $repository = $factory->create($this->entityPerPage);

        $this->assertInstanceOf(EntityRepositoryInterface::class, $repository);
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeNotCreatedWithNotValidEntityName(): void
    {
        $this->expectException(NotAllowedEntityName::class);

        $factory = new EntityFileRepositoryFactory('not valid entity name');
        $factory->create($this->entityPerPage);
    }
}
