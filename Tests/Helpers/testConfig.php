<?php

/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 24/03/2016
 * Time: 14:48
 */
class testConfig extends PHPUnit_Framework_TestCase
{
    public function testSetConfig() {
        $MockConfig = new MockConfig();
        $MockConfig::setConfig(array('test'));
        $this->assertEquals(array('test'), $MockConfig->getConfigVar());
    }

    public function testGetConfig() {
        $MockConfig = new MockConfig();
        $MockConfig::setConfig(array('sometestKey' => 'test'));
        $this->assertEquals('test', $MockConfig::getConfig('sometestKey'));
    }
}

class MockConfig extends \Cve\Helpers\Config {

    public function __construct() {
        //clear config
        parent::$config = null;
    }

    public function getConfigVar() {
        return parent::$config;
    }
}