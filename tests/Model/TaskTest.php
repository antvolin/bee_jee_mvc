<?php

namespace BeeJeeMVC\Tests\Text;

use BeeJeeMVC\Lib\Exceptions\CannotDoneTaskException;
use BeeJeeMVC\Lib\Exceptions\CannotEditTaskException;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Id;
use BeeJeeMVC\Model\Status;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    /**
     * @var Id
     */
    protected $taskId;

    /**
     * @var UserName
     */
    protected $taskUserName;

    /**
     * @var Email
     */
    protected $taskEmail;

    /**
     * @var Text
     */
    protected $taskText;

    /**
     * @var Status
     */
    protected $taskStatus;

    /**
     * @var Task
     */
    protected $task;

    protected function setUp(): void
    {
        $this->taskUserName = new UserName('test user name');
        $this->taskEmail = new Email('test@test.test');
        $this->taskText = new Text('test task text');
        $this->taskId = new Id();
        $this->taskStatus = new Status();
        $this->task = new Task($this->taskId, $this->taskUserName, $this->taskEmail, $this->taskText, $this->taskStatus);
    }

    /**
     * @test
     */
    public function shouldBeCreated(): void
    {
        $this->assertEquals($this->taskId, $this->task->getId());
        $this->assertEquals($this->taskUserName, $this->task->getUserName());
        $this->assertEquals($this->taskEmail, $this->task->getEmail());
        $this->assertEquals($this->taskText, $this->task->getText());
        $this->assertEquals($this->taskStatus, $this->task->getStatus());
    }

    /**
     * @test
     *
     * @throws CannotEditTaskException
     */
    public function shouldBeEditable(): void
    {
        $newText = 'new test text';
        $this->task->edit($newText);
        $this->assertEquals($newText, $this->task->getText());
        $this->assertEquals(Status::EDITED, $this->task->getStatus());
    }

    /**
     * @test
     *
     * @throws CannotEditTaskException
     */
    public function notShouldBeEditableIfStatusDone(): void
    {
        $this->expectException(CannotEditTaskException::class);
        $this->task->setStatus(new Status(Status::DONE));
        $this->task->edit('new test text');
    }

    /**
     * @test
     *
     * @throws CannotDoneTaskException
     */
    public function shouldBeDone(): void
    {
        $this->task->done();
        $this->assertEquals(Status::DONE, $this->task->getStatus());
    }

    /**
     * @test
     *
     * @throws CannotDoneTaskException
     */
    public function notShouldBeDoneIfStatusDone(): void
    {
        $this->expectException(CannotDoneTaskException::class);
        $this->task->setStatus(new Status(Status::DONE));
        $this->task->done();
    }
}
