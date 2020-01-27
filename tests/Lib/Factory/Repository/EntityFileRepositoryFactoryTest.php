<?php

namespace Tests\Lib\Factory\Repository;

use Todo\Lib\App;
use Todo\Lib\Exceptions\NotAllowedEntityName;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
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
    public function shouldBeCreatableEntityFileRepository(): void
    {
        $factory = new EntityFileRepositoryFactory(App::getEntityName());
        $repository = $factory->create($this->entityPerPage);

        $this->assertTrue(method_exists($repository, 'getCountEntities'));
        $this->assertTrue(method_exists($repository, 'getEntities'));
        $this->assertTrue(method_exists($repository, 'addEntity'));
        $this->assertTrue(method_exists($repository, 'getEntityById'));
    }

    /**
     * @test
     *
     * @throws NotAllowedEntityName
     */
    public function shouldBeNotCreatableWithNotValidEntityName(): void
    {
        $this->expectException(NotAllowedEntityName::class);

        $factory = new EntityFileRepositoryFactory('not valid entity name');
        $factory->create($this->entityPerPage);
    }
}
