<?php

namespace nerdsandcompany\mailretry\models;

use craft\base\Model;

/**
 * Class Settings
 *
 * @author    Nerds & Company
 * @package   MailRetry
 * @since     1.0.0
 *
 */
class Settings extends Model
{
    /**
     * @var number
     */
    public $maxAttempts = 5;

    /**
     * @var number
     */
    public $ttr = 60 * 15;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['maxAttempts', 'ttr'], 'required'],
            [['maxAttempts', 'ttr'], 'integer', 'min' => 1]
        ];
    }
}
