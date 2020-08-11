<?php

namespace nerdsandcompany\mailretry\jobs;

use Craft;
use craft\queue\BaseJob;
use craft\mail\Message;
use yii\queue\RetryableJobInterface;
use nerdsandcompany\mailretry\MailRetry;
use nerdsandcompany\mailretry\errors\MailRetryException;

/**
 * Class MailRetryJob
 *
 * @author    Nerds & Company
 * @package   MailRetry
 * @since     1.0.0
 *
 */
class MailRetryJob extends BaseJob implements RetryableJobInterface
{
    /**
     * @var Message
     */
    public $message;

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function execute($queue)
    {
        $variables = $this->message->variables ?? [];
        $variables['mailRetryAttempt'] = true;
        $this->message->variables = $variables;
        if (Craft::$app->mailer->send($this->message) === false) {
            throw new MailRetryException();
        }
    }

    /**
     * Get maximum number of attempts
     */
    public function getMaxAttempts() {
        return MailRetry::getInstance()->getSettings()->maxAttempts;
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return MailRetry::getInstance()->getSettings()->ttr;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < $this->getMaxAttempts();
    }

    /**
     * @inheritdoc
     */
    protected function defaultDescription()
    {
        return Craft::t('mail-retry', 'Retry sending mail: {subject}', ['subject' => $this->message->getSubject()]);
    }
}
