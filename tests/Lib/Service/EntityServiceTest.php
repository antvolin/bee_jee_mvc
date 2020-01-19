<?php

namespace BeeJeeMVC\Tests\Lib\Service;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotDoneEntityException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotAllowedEntityName;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Service\EntityService;
use BeeJeeMVC\Lib\Service\EntityServiceInterface;
use BeeJeeMVC\Model\EntityInterface;
use BeeJeeMVC\Model\Status;
use PHPUnit\Framework\TestCase;

class EntityServiceTest extends TestCase
{
    /**
     * @var EntityServiceInterface
     */
    private $manager;

    /**
     * @throws NotAllowedEntityName
     */
    protected function setUp()
    {
        $app = new App();
        $repository = $app->getRepository();
        $entityClass = $app->getEntityClassNamespace().ucfirst(strtolower($app->getEntityName()));
        $this->manager = new EntityService($entityClass, $repository);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotFoundException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntityById(): void
    {
        $id = $this->manager->saveEntity(uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));
        $entity = $this->manager->getEntityById($id);

        $this->assertInstanceOf(EntityInterface::class, $entity);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingEntitiesArray(): void
    {
        $this->manager->saveEntity(uniqid('user_name1'.__METHOD__.__CLASS__, true), 'test@test.test1', uniqid('text1'.__METHOD__.__CLASS__, true));
        $this->manager->saveEntity(uniqid('user_name2'.__METHOD__.__CLASS__, true), 'test@test.test2', uniqid('text2'.__METHOD__.__CLASS__, true));
        $this->manager->saveEntity(uniqid('user_name3'.__METHOD__.__CLASS__, true), 'test@test.test3', uniqid('text3'.__METHOD__.__CLASS__, true));

        $entities = $this->manager->getEntities(0);

        foreach ($entities as $entity) {
            $this->assertInstanceOf(EntityInterface::class, $entity);
        }
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     */
    public function shouldBeGettingCountOfEntities(): void
    {
        $this->manager->saveEntity(uniqid('user_name1'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text1'.__METHOD__.__CLASS__, true));
        $this->manager->saveEntity(uniqid('user_name2'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text2'.__METHOD__.__CLASS__, true));
        $this->manager->saveEntity(uniqid('user_name3'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text3'.__METHOD__.__CLASS__, true));

        $count = $this->manager->getCountEntities();

        $this->assertLessThan($count, 0);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     * @throws NotFoundException
     */
    public function shouldBeDeletingEntity(): void
    {
        $this->expectException(NotFoundException::class);

        $id = $this->manager->saveEntity(uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));

        $this->manager->deleteEntity($id);
        $this->manager->getEntityById($id);
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     * @throws NotFoundException
     * @throws CannotEditEntityException
     */
    public function shouldBeEditingEntity(): void
    {
        $newText = uniqid('text'.__METHOD__.__CLASS__, true);
        $id = $this->manager->saveEntity(uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test', uniqid('text'.__METHOD__.__CLASS__, true));
        $this->manager->editEntity($id, $newText);
        $entity = $this->manager->getEntityById($id);

        $this->assertEquals(Status::EDITED, $entity->getStatus());
        $this->assertEquals($newText, $entity->getText());
    }

    /**
     * @test
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     * @throws PdoErrorsException
     * @throws NotFoundException
     * @throws CannotDoneEntityException
     */
    public function shouldBeDoneEntity(): void
    {
        $id = $this->manager->saveEntity(uniqid('user_name'.__METHOD__.__CLASS__, true), 'test@test.test111', uniqid('text'.__METHOD__.__CLASS__, true));
        $this->manager->doneEntity($id);
        $entity = $this->manager->getEntityById($id);

        $this->assertEquals(Status::DONE, $entity->getStatus());
    }
}