<?php

namespace BeeJeeMVC\Tests\HashGenerator;

use BeeJeeMVC\Lib\IdGenerator;
use PHPUnit\Framework\TestCase;

class IdGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeGenerated(): void
    {
        $userName = 'test user name';
        $email = 'test@test.test';
        $text = 'test text';

        $id = (new IdGenerator)->generateId($userName, $email, $text);

        $this->assertEquals(hash('md5', 'test user nametest@test.testtest text'), $id);
    }
}