<?php

namespace Todo\Tests\Lib\Service;

use Todo\Lib\App;
use Todo\Lib\Service\PdoService;
use PDO;
use PHPUnit\Framework\TestCase;

class PdoServiceTest extends TestCase
{
    /**
     * PdoManager $pdoManager
     */
    protected $pdoManager;

    /**
     * Pdo $pdo
     */
    protected $pdo;

    protected function setUp()
    {
        $app = new App();
        $this->pdoManager = new PdoService($app->getEntityName(), $app->getStorageType(), $app->getDbFolderName());
        $this->pdo = $this->pdoManager->getPdo();
    }

    /**
     * @test
     */
    public function shouldBeGettingPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->pdo);
    }

    /**
     * @test
     */
    public function shouldBeCreatedTables(): void
    {
        $this->assertTrue($this->pdoManager->createTables());
    }
}
