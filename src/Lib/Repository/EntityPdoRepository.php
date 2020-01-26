<?php

namespace Todo\Lib\Repository;

use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotFoundException;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Ordering\OrderingService;
use Todo\Model\EntityInterface;
use PDO;
use PDOException;

class EntityPdoRepository implements EntityRepositoryInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var EntityServiceInterface
     */
    private $entityService;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var int
     */
    private $entityPerPage;

    /**
     * @param PDO $pdo
     * @param EntityServiceInterface $entityService
     * @param int $entityPerPage
     */
    public function __construct(Pdo $pdo, EntityServiceInterface $entityService, int $entityPerPage)
    {
        $this->pdo = $pdo;
        $this->entityService = $entityService;
        $this->entityName = $entityService->getEntityName();
        $this->entityPerPage = $entityPerPage;
    }

    /**
     * @inheritdoc
     */
    public function getEntityById(int $id): EntityInterface
    {
        $sth = $this->pdo->prepare("SELECT * FROM $this->entityName WHERE id = :id;");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();

        if (!$entity = $sth->fetch(PDO::FETCH_ASSOC)) {
            throw new NotFoundException();
        }

        return $this->entityService->createEntity($entity);
    }

    /**
     * @inheritdoc
     */
    public function getCountEntities(): int
    {
        return  $this->pdo->query("SELECT COUNT(id) FROM $this->entityName;")->fetchColumn();
    }

    /**
     * @inheritdoc
     */
    public function getEntities(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        $result = [];

        $orderBy = OrderingService::getOrderBy($orderBy);
        $order = OrderingService::getOrder($order);

        $sth = $this->pdo->prepare("SELECT * FROM $this->entityName ORDER BY $orderBy $order LIMIT :limit OFFSET :offset;");
        $limit = $this->entityPerPage;
        $offset = $this->entityPerPage * ($page - 1);
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->bindParam(':offset', $offset, PDO::PARAM_INT);
        $sth->execute();

        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $entity) {
            $result[$entity['id']] = $this->entityService->createEntity($entity);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function saveEntity(EntityInterface $entity, ?int $entityId = null): int
    {
        $userName = $entity->getUserName();
        $email = $entity->getEmail();
        $text = $entity->getText();
        $status = $entity->getStatus();

        if ($entityId) {
            $sth = $this->pdo->prepare("UPDATE $this->entityName SET user_name = :userName, email = :email, text = :text, status = :status WHERE id = :id;");
            $sth->bindParam(':id', $entityId, PDO::PARAM_INT);
        } else {
            $sth = $this->pdo->prepare("INSERT INTO $this->entityName (user_name, email, text, status) VALUES(:userName, :email, :text, :status);");
        }

        $sth->bindParam(':userName', $userName);
        $sth->bindParam(':email', $email);
        $sth->bindParam(':text', $text);
        $sth->bindParam(':status', $status);

        try {
            $sth->execute();
        } catch (PDOException $exception) {
            throw new PdoErrorsException($exception->getMessage());
        }

        return $this->pdo->lastInsertId();
    }

    /**
     * @inheritdoc
     */
    public function deleteEntity(int $entityId): void
    {
        $sth = $this->pdo->prepare("DELETE FROM $this->entityName WHERE id = :id;");
        $sth->bindParam(':id', $entityId, PDO::PARAM_INT);
        $sth->execute();
    }
}
