<?php
use CveTests\Mocks\MockCVEModel;

/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 30/03/2016
 * Time: 20:15
 */
class testCVE extends PHPUnit_Framework_TestCase
{
    public function testIndex() {
        $cve = $this->getMock('\Cve\Controllers\Cve', ['getCVEs'], [], '', false);
        $cve->expects($this->once())
            ->method('getCVEs');
        $cve->index([]);
    }

    public function testIndexWithYear() {
        $cve = $this->getMock('\Cve\Controllers\Cve', ['getCVE'], [], '', false);
        $cve->expects($this->once())
            ->method('getCVE');
        $cve->index(['someyear']);
    }

    public function testGetCVEs() {
        $mockConfig = new \CveTests\Mocks\MockConfig();
        $mockConfig->setConfig(array('default_get_limit' => 40));
        $mockCVEModel = $this->getMock('\CveTests\Mocks\MockCVEModel', ['getRecords'], [], '', false);
        $mockCVEModel->expects($this->once())
            ->method('getRecords')
            ->with(40, 0, null)
            ->willReturn('some results');
        $cve = $this->getMock('mockCVE', ['checkRequestMethod','outputData'], [], '', false);
        $cve->expects($this->once())
            ->method('checkRequestMethod')
            ->with('GET');
        $cve->expects($this->once())
            ->method('outputData')
            ->with('some results');
        $cve->setCVEModelMock($mockCVEModel);
        $cve->getCVEs();
    }

    protected function getCVE($cveNumber) {
        $mockConfig = new \CveTests\Mocks\MockConfig();
        $mockConfig->setConfig(array('default_get_limit' => 40));
        $mockCVEModel = $this->getMock('\CveTests\Mocks\MockCVEModel', ['getRecords'], [], '', false);
        $mockCVEModel->expects($this->once())
            ->method('getRecord')
            ->with('some number')
            ->willReturn('some results');
        $cve = $this->getMock('mockCVE', ['checkRequestMethod','outputData'], [], '', false);
        $cve->expects($this->once())
            ->method('checkRequestMethod')
            ->with('GET');
        $cve->expects($this->once())
            ->method('outputData')
            ->with('some results');
        $cve->setCVEModelMock($mockCVEModel);
        $cve->getCVEs('some number');
    }
}

abstract class mockCVE extends \Cve\Controllers\Cve
{
    public $logger;

    public function __construct() {
        $logger = new \CveTests\Mocks\MockLogger();
        $this->logger = $logger;
        parent::__construct($logger);
    }

    public function setCVEModelMock($mockCVEModel) {
        return $this->cveModel = $mockCVEModel;
    }

    public function getHeader($header) {
        return $header;
    }
}