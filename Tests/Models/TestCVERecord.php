<?php

class TestCVERecord extends PHPUnit_Framework_TestCase
{
    public function testConstruct() {
        $logger = new \CveTests\Mocks\MockLogger();
        $dbDriver = new \CveTests\Mocks\MockDBDriver();
        $name = 'foo name';
        $description = 'foo description';
        $status = 'foo status';
        $phase = 'foo phase';
        $cveRecordMock = $this->getMock('\Cve\Models\CVERecord', [], [$logger, $dbDriver, $name, $description, $status, $phase]);
        $this->assertEquals('foo name', $cveRecordMock->name);
        $this->assertEquals('foo description', $cveRecordMock->description);
        $this->assertEquals('foo status', $cveRecordMock->status);
        $this->assertEquals('foo phase', $cveRecordMock->phase);
    }

    public function testDecorate() {
        $cveRecordMock = $this->getMock('\Cve\Models\CVERecord', ['getComments', 'getReferences', 'getVotes'], [], '', false);
        $cveRecordMock->name = 'foo name';
        $cveRecordMock->expects($this->once())
            ->method('getComments')
            ->with('foo name')
            ->willReturn(['comment1', 'comment2']);
        $cveRecordMock->expects($this->once())
            ->method('getReferences')
            ->with('foo name')
            ->willReturn(['ref1', 'ref2']);
        $cveRecordMock->expects($this->once())
            ->method('getVotes')
            ->with('foo name')
            ->willReturn(['vote1', 'vote2']);
        $cveRecordMock->decorate();
        $this->assertEquals(['comment1', 'comment2'], $cveRecordMock->comments);
        $this->assertEquals(['ref1', 'ref2'], $cveRecordMock->references);
        $this->assertEquals(['vote1', 'vote2'], $cveRecordMock->votes);
    }

    public function testGetData() {
        $cveRecordMock = $this->getMock('\Cve\Models\CVERecord', ['getComments', 'getReferences', 'getVotes'], [], '', false);
        $comment1 = new \Cve\Models\CVEComment();
        $comment1->author = 'first author';
        $comment1->user_comment = 'some comment';
        $comment2 = new \Cve\Models\CVEComment();
        $comment2->author = 'second author';
        $comment2->user_comment = 'some commentsss';
        $ref1 = new \Cve\Models\CVEReference();
        $ref1->reference = 'some reference';
        $ref2 = new \Cve\Models\CVEReference();
        $ref2->reference = 'some second reference';
        $ref3 = new \Cve\Models\CVEReference();
        $ref3->reference = 'some third reference';
        $vote1 = new \Cve\Models\CVEVote();
        $vote1->vote = 'I made a vote';

        $data = array(
            'name' => 'foo name',
            'description' => 'foo description',
            'status' => 'foo status',
            'phase' => 'foo phase',
            'comments' => [$comment1, $comment2],
            'references' => [$ref1, $ref2, $ref3],
            'votes' => [$vote1],
        );
        $cveRecordMock->name = $data['name'];
        $cveRecordMock->description = $data['description'];
        $cveRecordMock->status = $data['status'];
        $cveRecordMock->phase = $data['phase'];
        $cveRecordMock->comments = $data['comments'];
        $cveRecordMock->references = $data['references'];
        $cveRecordMock->votes = $data['votes'];

        $expected = $data;
        $expected['comments'] = [$comment1->getData(), $comment2->getData()];
        $expected['references'] = [$ref1->getData(), $ref2->getData(), $ref3->getData()];
        $expected['votes'] = [$vote1->getData()];
        $this->assertEquals($expected, $cveRecordMock->getData());
    }
//
//    public function getData() {
//        $record = array(
//            'name' => $this->name,
//            'description' => $this->description,
//            'status' => $this->status,
//            'phase' => $this->phase,
//            'comments' => array(),
//            'votes' => array(),
//            'references' => array()
//        );
//        foreach ($this->comments as $comment) {
//            $record['comments'][] = $comment->getData();
//        }
//        foreach ($this->references as $reference) {
//            $record['references'][] = $reference->getData();
//        }
//        foreach ($this->votes as $vote) {
//            $record['votes'][] = $vote->getData();
//        }
//        return $record;
//    }
}