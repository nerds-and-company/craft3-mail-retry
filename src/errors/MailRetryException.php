<?php

namespace nerdsandcompany\mailretry\errors;

use Craft;
use Exception;

/**
 * Class MailRetryException
 *
 * @author    Nerds & Company
 * @package   MailRetry
 * @since     1.0.0
 *
 */
class MailRetryException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return Craft::t('mail-retry', 'Retry of sending mail failed');
    }
}
