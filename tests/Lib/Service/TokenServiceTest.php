<?php

namespace Todo\Tests\Lib\Service;

use Todo\Lib\App;
use Todo\Lib\Service\TokenService;
use PHPUnit\Framework\TestCase;

class TokenServiceTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $tokenSalt;

    /**
     * @var TokenService
     */
    protected $tokenManager;

    protected function setUp()
    {
        $this->app = new App();
        $this->tokenManager = new TokenService();
        $this->secret = $this->app->getSecret();
        $this->tokenSalt = $this->app->getTokenSalt();
        $this->tokenManager->generateToken($this->secret, $this->tokenSalt);
        $this->token = $this->tokenManager->getToken();
    }

    /**
     * @test
     */
    public function tokenShouldBeGenerated(): void
    {
        $this->assertEquals($this->tokenSalt.':'.md5($this->tokenSalt.':'.$this->secret), $this->token);
    }

    /**
     * @test
     */
    public function validationOfAValidTokenMustBeSuccessful(): void
    {
        $this->assertTrue($this->tokenManager->isValidToken($this->token, $this->secret));
    }

    /**
     * @test
     */
    public function validationOfInvalidTokenMustFail(): void
    {
        $this->assertFalse($this->tokenManager->isValidToken('not valid token', 'not valid secret'));
    }
}
