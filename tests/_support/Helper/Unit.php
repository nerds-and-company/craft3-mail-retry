<?php
namespace Helper;

use Craft;
use Codeception\TestInterface;
use nerdsandcompany\mailretry\MailRetry;
use craft\i18n\I18n;
use craft\web\View;
use craft\web\Controller;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
    /**
     * Do setup so we can test the plugin
     */
    public function getPlugin(TestInterface $test) {
        $mockI18n = $test->makeEmpty(I18n::class);
        Craft::$app->expects($test->any())->method('getI18n')->willReturn($mockI18n);

        $mockView = $test->makeEmpty(View::class, ['getTemplateMode' => 'cp']);
        Craft::$app->expects($test->any())->method('getView')->willReturn($mockView);

        $mockController = $test->makeEmpty(Controller::class);
        Craft::$app->controller = $mockController;

        $plugin = new MailRetry('mail-retry');
        Craft::$app->loadedModules[MailRetry::class] = $plugin;

        return MailRetry::getInstance();
    }
}

