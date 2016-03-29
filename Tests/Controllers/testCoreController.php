<?php

class testCoreController extends PHPUnit_Framework_TestCase
{

    public function testConstructor() {
        $logger = $this->getLoggerMock();
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), 'coreController', false, false, true, array('setModels', 'getheader'));
        $mockCoreController->expects($this->once())
            ->method('setModels');
        $mockCoreController->expects($this->once())
            ->method('getheader')
            ->with('Accept')
            ->willReturn('application/json');
        /** @var mockCoreController $mockCoreController */
        $mockCoreController->__construct($logger);
        $this->assertEquals($logger, $mockCoreController->getLogger());
        $this->assertEquals('json', $mockCoreController->getResponseType());
    }

    public function testConstructorDefaultsToXML() {
        $logger = $this->getLoggerMock();
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), 'coreController', false, false, true, array('setModels', 'getheader'));
        $mockCoreController->expects($this->once())
            ->method('setModels');
        $mockCoreController->expects($this->once())
            ->method('getheader')
            ->with('Accept')
            ->willReturn('not json');
        /** @var \Cve\Controllers\CoreController $mockCoreController */
        $mockCoreController->__construct($logger);

        $this->assertEquals($logger, $mockCoreController->getLogger());
        $this->assertEquals('xml', $mockCoreController->getResponseType());
    }

    public function getLoggerMock() {
        return $this->getMock('\Monolog\Logger', array(), array(), '', false);
    }
}

abstract class mockCoreController extends \Cve\Controllers\CoreController
{
    public function getLogger() {
        return $this->logger;
    }

    public function getResponseType() {
        return $this->responseType;
    }


}