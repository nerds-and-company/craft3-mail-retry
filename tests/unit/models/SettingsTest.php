<?php

namespace nerdsandcompany\mailretry\tests\settings;

use Codeception\Test\Unit;
use UnitTester;

use nerdsandcompany\mailretry\models\Settings;

class SettingsTest extends Unit
{
    protected Settings $settings;
    protected UnitTester $tester;

    protected function _before()
    {
        $this->tester->getPlugin($this);
        $this->settings = new Settings();
    }

    public function testDefaults()
    {
        $this->assertEquals(5, $this->settings->maxAttempts);
        $this->assertEquals(900, $this->settings->ttr);
    }

    public function testRrules()
    {
        $this->assertTrue($this->settings->validate());

        $this->settings->maxAttempts = null;
        $this->assertFalse($this->settings->validate());

        $this->settings->maxAttempts = 0;
        $this->assertFalse($this->settings->validate());

        $this->settings->maxAttempts = 1;
        $this->assertTrue($this->settings->validate());

        $this->settings->ttr = null;
        $this->assertFalse($this->settings->validate());

        $this->settings->ttr = 0;
        $this->assertFalse($this->settings->validate());

        $this->settings->ttr = 1;
        $this->assertTrue($this->settings->validate());
    }
}
