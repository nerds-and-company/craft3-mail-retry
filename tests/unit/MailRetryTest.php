<?php

namespace nerdsandcompany\mailretry\tests;

use Craft;
use Codeception\Test\Unit;
use UnitTester;
use nerdsandcompany\mailretry\MailRetry;
use nerdsandcompany\mailretry\models\Settings;
use yii\base\Event;
use craft\mail\Message;
use yii\mail\MailEvent;

class MailRetryTest extends Unit
{
    protected MailRetry $plugin;
    protected UnitTester $tester;

    protected function _before()
    {
        $this->plugin = $this->tester->getPlugin($this);
    }

    public function testSettings()
    {
        $this->assertTrue($this->plugin->hasCpSettings);
        $this->assertFalse($this->plugin->hasCpSection);
        $this->assertInstanceOf(Settings::class, $this->plugin->getSettings());

        Craft::$app->controller->expects($this->exactly(1))->method('renderTemplate');
        $this->plugin->getSettingsResponse();
    }

    public function testWatchSuccesfulMail()
    {
        $mailerClass = get_class(Craft::$app->getMailer());
        $message = new Message();
        $event = new MailEvent(['message' => $message, 'isSuccessful' => true]);
        Craft::$app->getQueue()->expects($this->exactly(0))->method('push')->willReturn(null);
        Event::trigger($mailerClass, $mailerClass::EVENT_AFTER_SEND, $event);
    }

    public function testWatchFailedMail()
    {
        $mailerClass = get_class(Craft::$app->getMailer());
        $message = new Message();
        $event = new MailEvent(['message' => $message, 'isSuccessful' => false]);
        Craft::$app->getQueue()->expects($this->exactly(1))->method('push')->willReturn(null);
        Event::trigger($mailerClass, $mailerClass::EVENT_AFTER_SEND, $event);
    }

    public function testWatchFailedRetriedMail()
    {
        $mailerClass = get_class(Craft::$app->getMailer());
        $message = new Message(['variables' => ['mailRetryAttempt' => true]]);
        $event = new MailEvent(['message' => $message, 'isSuccessful' => false]);
        Craft::$app->getQueue()->expects($this->exactly(0))->method('push')->willReturn(null);
        Event::trigger($mailerClass, $mailerClass::EVENT_AFTER_SEND, $event);
    }
}
