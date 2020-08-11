<?php
/**
 * Mail Retry plugin for Craft CMS 3.x
 *
 * Retry mails in background when they fail
 *
 * @link      https://nerds.company
 * @copyright Copyright (c) 2020 Nerds & Company
 */
namespace nerdsandcompany\mailretry;

use Craft;
use craft\base\Plugin;
use yii\base\Event;
use yii\mail\MailEvent;
use nerdsandcompany\mailretry\jobs\MailRetryJob;
use nerdsandcompany\mailretry\models\Settings;

/**
 * Class MailRetry
 *
 * @author    Nerds & Company
 * @package   MailRetry
 * @since     1.0.0
 *
 */
class MailRetry extends Plugin
{
    /**
     * @var MailRetry
     */
    public static $plugin;

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;
        $this->watchMailer();
    }

    /**
     * Push a mail retry job to the queue when sending of mail fails
     */
    private function watchMailer()
    {
        $mailerClass = get_class(Craft::$app->mailer);
        Event::on(
            $mailerClass,
            $mailerClass::EVENT_AFTER_SEND,
            function (MailEvent $event) {
                $message = $event->message;
                $isMailRetryAttempt = $message->variables['mailRetryAttempt'] ?? false;
                if ($event->isValid && !$event->isSuccessful && !$isMailRetryAttempt) {
                    $retryJob = new MailRetryJob(['message' => $event->message]);
                    Craft::$app->getQueue()->push($retryJob);
                }
            }
        );
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'mail-retry/settings',
            ['settings' => $this->getSettings()]
        );
    }
}
