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

    public function testOutputData() {
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), '', false, false, true, array('setheader', 'writeOutput', 'setModels', 'getheader'));
        $mockCoreController->expects($this->once())
            ->method('setModels');
        $mockCoreController->expects($this->once())
            ->method('getheader')
            ->willReturn('application/json');
        $mockCoreController->expects($this->once())
            ->method('setheader');
        $mockCoreController->expects($this->once())
            ->method('writeOutput')
            ->with('["foo"]');
        $data = new \Cve\Models\genericData(array('foo'));
        $mockCoreController->__construct(new \CveTests\Mocks\MockLogger());
        $mockCoreController->outputData($data);
    }

    public function testOutputDataWithCVERecordData() {
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), '', false, false, true, array('setheader', 'writeOutput', 'setModels', 'getheader'));
        $mockCoreController->expects($this->once())
            ->method('setModels');
        $mockCoreController->expects($this->once())
            ->method('getheader')
            ->willReturn('application/json');
        $mockCoreController->expects($this->once())
            ->method('setheader');
        $mockCoreController->expects($this->once())
            ->method('writeOutput')
            ->with('{"name":"some Record","description":"some status","status":"some description","phase":"some phase","comments":[{"author":"first author","user_comment":"some comment"},{"author":"second author","user_comment":"some commentsss"}],"votes":["I made a vote"],"references":["some reference","some second reference","some third reference"]}');
        $record = $this->getRecordData();

        $mockCoreController->__construct(new \CveTests\Mocks\MockLogger());
        $mockCoreController->outputData($record);
    }

    public function testOutputDataWithXml() {
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), '', false, false, true, array('setheader', 'writeOutput', 'setModels', 'getheader'));
        $mockCoreController->expects($this->once())
            ->method('setModels');
        $mockCoreController->expects($this->once())
            ->method('getheader')
            ->willReturn('application/xml');
        $mockCoreController->expects($this->once())
            ->method('setheader');
        $mockCoreController->expects($this->once())
            ->method('writeOutput')
            ->with('<?xml version="1.0"?>
<record><something>foo</something></record>
');
        $data = new \Cve\Models\genericData(array('something' => 'foo'));
        $mockCoreController->__construct(new \CveTests\Mocks\MockLogger());
        $mockCoreController->outputData($data);
    }

    public function testOutputDataWithXmlAndRecordData() {
        $mockCoreController = $this->getMockForAbstractClass('mockCoreController', array(), '', false, false, true, array('setheader', 'writeOutput', 'setModels', 'getheader'));
        $mockCoreController->expects($this->once())
            ->method('setModels');
        $mockCoreController->expects($this->once())
            ->method('getheader')
            ->willReturn('application/xml');
        $mockCoreController->expects($this->once())
            ->method('setheader');
        $mockCoreController->expects($this->once())
            ->method('writeOutput')
            ->with('<?xml version="1.0"?>
<record><name>some Record</name><description>some status</description><status>some description</status><phase>some phase</phase><comments><comment><author>first author</author><user_comment>some comment</user_comment></comment><comment><author>second author</author><user_comment>some commentsss</user_comment></comment></comments><votes><vote>I made a vote</vote></votes><references><reference>some reference</reference><reference>some second reference</reference><reference>some third reference</reference></references></record>
');
        $mockCoreController->__construct(new \CveTests\Mocks\MockLogger());
        $mockCoreController->outputData($this->getRecordData());
    }

    private function getRecordData() {
        $record = new \Cve\Models\CVERecord(new \CveTests\Mocks\MockLogger(), new \CveTests\Mocks\MockDBDriver(), 'some Record', 'some status', 'some description', 'some phase');
        $comment1 = new \Cve\Models\CVEComment();
        $comment1->author = 'first author';
        $comment1->user_comment = 'some comment';
        $comment2 = new \Cve\Models\CVEComment();
        $comment2->author = 'second author';
        $comment2->user_comment = 'some commentsss';
        $record->comments = [$comment1, $comment2];

        $ref1 = new \Cve\Models\CVEReference();
        $ref1->reference = 'some reference';
        $ref2 = new \Cve\Models\CVEReference();
        $ref2->reference = 'some second reference';
        $ref3 = new \Cve\Models\CVEReference();
        $ref3->reference = 'some third reference';
        $record->references = [$ref1, $ref2, $ref3];

        $vote1 = new \Cve\Models\CVEVote();
        $vote1->vote = 'I made a vote';
        $record->votes = [$vote1];
        return $record;
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