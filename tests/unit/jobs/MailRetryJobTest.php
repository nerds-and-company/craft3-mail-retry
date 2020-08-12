<?php

namespace nerdsandcompany\mailretry\tests\jobs;

use Craft;
use Codeception\Test\Unit;
use nerdsandcompany\mailretry\jobs\MailRetryJob;
use nerdsandcompany\mailretry\errors\MailRetryException;
use UnitTester;
use craft\mail\Message;

use nerdsandcompany\mailretry\models\Settings;

class MailRetryJobTest extends Unit
{
    protected MailRetryJob $job;
    protected UnitTester $tester;

    protected function _before()
    {
        $this->tester->getPlugin($this);
        $this->job = new MailRetryJob([
            'message' => new Message(['variables' => []])
        ]);
    }

    public function testDefaults()
    {
        $this->assertEquals(5, $this->job->getMaxAttempts());
        $this->assertEquals(900, $this->job->getTtr());
    }

    public function testCanTry()
    {
        $this->assertTrue($this->job->canRetry(5, ''));
        $this->assertFalse($this->job->canRetry(6, ''));
    }

    public function testSuccesfulSend()
    {
        $this->assertArrayNotHasKey('mailRetryAttempt', $this->job->message->variables);
        Craft::$app->getMailer()->expects($this->exactly(1))->method('send')->willReturn(true);
        $this->job->execute(Craft::$app->getQueue());
        $this->assertTrue($this->job->message->variables['mailRetryAttempt']);
    }

    public function testFailedSend()
    {
        $this->assertArrayNotHasKey('mailRetryAttempt', $this->job->message->variables);
        Craft::$app->getMailer()->expects($this->exactly(1))->method('send')->willReturn(false);
        $this->expectException(MailRetryException::class);
        $this->job->execute(Craft::$app->getQueue());
        $this->assertTrue($this->job->message->variables['mailRetryAttempt']);
    }
}
