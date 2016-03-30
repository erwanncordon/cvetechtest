<?php

class testCoreController extends PHPUnit_Framework_TestCase
{

    public function testConstructor() {
        $logger = new \CveTests\Mocks\MockLogger();
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
        $logger = new \CveTests\Mocks\MockLogger();
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), 'coreController', false, false, true, array('setModels', 'getheader'));
        $mockCoreController->expects($this->once())
            ->method('setModels');
        $mockCoreController->expects($this->once())
            ->method('getheader')
            ->with('Accept')
            ->willReturn('not json');
        /** @var mockCoreController $mockCoreController */
        $mockCoreController->__construct($logger);

        $this->assertEquals($logger, $mockCoreController->getLogger());
        $this->assertEquals('xml', $mockCoreController->getResponseType());
    }

    public function testCheckRequestMethod() {
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), 'coreController', false, false, true, array());
        $_SERVER['REQUEST_METHOD'] = 'GET';
        /** @var mockCoreController $mockCoreController */
        $mockCoreController->checkRequestMethod('get');
    }

    public function testCheckRequestMethodWithPost() {
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), 'coreController', false, false, true, array());
        $_SERVER['REQUEST_METHOD'] = 'POST';
        /** @var mockCoreController $mockCoreController */
        $mockCoreController->checkRequestMethod('PoST');
    }

    /**
     * @throws \Exception
     * @expectedException \Exception
     * @expectedExceptionMessage Request method should be: POST and not: GET
     */
    public function testCheckRequestMethodThrowsExceptionIfIncorrectRequestMethod() {
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), 'coreController', false, false, true, array());
        $_SERVER['REQUEST_METHOD'] = 'GET';
        /** @var mockCoreController $mockCoreController */
        $mockCoreController->checkRequestMethod('PoST');
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