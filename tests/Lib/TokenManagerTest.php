<?php

namespace BeeJeeMVC\Tests\Lib;

use BeeJeeMVC\Lib\SecretGenerator;
use BeeJeeMVC\Lib\TokenManager;
use PHPUnit\Framework\TestCase;

class TokenManagerTest extends TestCase
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var TokenManager
     */
    protected $tokenManager;

    protected function setUp()
    {
        $this->tokenManager = new TokenManager();
        $this->secret = (new SecretGenerator())->generateSecret();
        $this->tokenManager->generateToken($this->secret, $_ENV['TOKEN_SALT']);
        $this->token = $this->tokenManager->getToken();
    }

    /**
     * @test
     */
    public function tokenShouldBeGenerated(): void
    {
        $this->assertEquals($_ENV['TOKEN_SALT'].':'.md5($_ENV['TOKEN_SALT'].':'.$this->secret), $this->token);
    }

    /**
     * @test
     */
    public function validationOfAValidTokenMustBeSuccessful(): void
    {
        $this->assertTrue($this->tokenManager->checkToken($this->token, $this->secret));
    }

    /**
     * @test
     */
    public function validationOfInvalidTokenMustFail(): void
    {
        $this->assertFalse($this->tokenManager->checkToken('not valid token', 'not valid secret'));
    }
}