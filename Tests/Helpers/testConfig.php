<?php
use CveTests\Mocks\MockConfig;

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

    /**
     * @expectedException \Cve\Exceptions\MissingConfigException
     * @expectedExceptionMessage Config: doo could not be found
     */
    public function testGetConfigThrowsMissingConfigExceptionIfNoConfigFound(){
        \Cve\Helpers\Config::getConfig('doo');
    }
}