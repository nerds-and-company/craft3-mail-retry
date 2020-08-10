<?php

namespace nerdsandcompany\mailretry\errors;

use Exception;

/**
 * Class MailRetry
 *
 * @author    Nerds & Company
 * @package   MailRetry
 * @since     1.0.0
 *
 */
class MailRetryException extends Exception
{
    // TODO:
    // Translate error message

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Retry of sending mail failed';
    }
}
