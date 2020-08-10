<?php

namespace nerdsandcompany\mailretry\jobs;

use Craft;
use craft\queue\BaseJob;
use craft\mail\Message;
use yii\queue\RetryableJobInterface;
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
    // TODO:
    // Configurable max attempts
    // Configurable ttr (time to reserve)
    // Translate description
    // phpunit?

    /**
     * @var Message
     */
    public $message;

    /**
     * @var number
     */
    public $maxAttempts = 5;

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
     * @inheritdoc
     */
    public function getTtr()
    {
        return 5;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < $this->maxAttempts;
    }

    /**
     * @inheritdoc
     */
    protected function defaultDescription()
    {
        return 'Retry mail job "'.$this->message->getSubject().'"';
    }
}
