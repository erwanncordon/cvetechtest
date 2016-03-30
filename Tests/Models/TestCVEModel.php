<?php

/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 30/03/2016
 * Time: 16:44
 */
class TestCVEModel extends PHPUnit_Framework_TestCase
{
    public function testSaveCVERecordWithMocks() {
        $x = array(
            'name' => 'foo',
            'votes' => 'votes',
            'comments' => 'comments',
            'references' => 'references'
        );
        $mockCVEModel = $this->getMock('MockCVEModel', ['insertCVERecord', 'insertCVEComments', 'insertCVEVotes', 'insertCVEReferences'], [$x]);
        $mockCVEModel->expects($this->once())
            ->method('insertCVERecord')
            ->with($x);
        $mockCVEModel->expects($this->once())
            ->method('insertCVEComments')
            ->with($x['name'], $x['comments']);
        $mockCVEModel->expects($this->once())
            ->method('insertCVEVotes')
            ->with($x['name'], $x['votes']);
        $mockCVEModel->expects($this->once())
            ->method('insertCVEReferences')
            ->with($x['name'], $x['references']);
        $mockCVEModel->saveCVERecord($x);
    }

    public function testSaveCVERecord() {
        $record = require(__DIR__ . '/../Data/recordArray.php');
        $cveModel = new MockCVEModel();
        $cveModel->saveCVERecord($record);
        $dbDriver = $cveModel->getDbDriver();
        $this->assertSame(
            array(
                array(
                    'cve_records',
                    array
                    (
                        'name' => array
                        (
                            'CVE-1999-0002',
                            'string',
                        ),
                        'status' => array
                        (
                            'Candidate',
                            'string',
                        ),
                        'description' => array
                        (
                            'Some Description',
                            'string',
                        ),
                        'phase' => array
                        (
                            'some Phaze',
                            'string',
                        )
                    )
                ),
                array(
                    'cve_comments',
                    array
                    (
                        'cve_record_id' => array
                        (
                            'CVE-1999-0002',
                            'string',
                        ),
                        'author' => array
                        (
                            'Commenter',
                            'string',
                        ),
                        'user_comment' => array
                        (
                            'comment1',
                            'string',
                        )
                    )
                ),

                array(
                    'cve_comments',
                    array
                    (
                        'cve_record_id' => array
                        (
                            'CVE-1999-0002',
                            'string',
                        ),
                        'author' => array
                        (
                            'scondCommenter',
                            'string',
                        ),
                        'user_comment' => array
                        (
                            'comment2',
                            'string',
                        )
                    )
                ),
                array(
                    'cve_votes',
                    array
                    (
                        'cve_record_id' => array
                        (
                            'CVE-1999-0002',
                            'string',
                        ),
                        'vote' => array
                        (
                            'some vote1',
                            'string',
                        )
                    )
                ),
                array(
                    'cve_votes',
                    array
                    (
                        'cve_record_id' => array
                        (
                            'CVE-1999-0002',
                            'string',
                        ),
                        'vote' => array
                        (
                            'somesecondvote',
                            'string',
                        )
                    )
                ),
                array(
                    'cve_references',
                    array
                    (
                        'cve_record_id' => array
                        (
                            'CVE-1999-0002',
                            'string',
                        ),
                        'reference' => array
                        (
                            'Some References',
                            'string',
                        )
                    )
                ),
                array(
                    'cve_references',
                    array(
                        'cve_record_id' => array
                        (
                            'CVE-1999-0002',
                            'string',
                        ),
                        'reference' => array
                        (
                            'some second reference',
                            'string',
                        )
                    )
                )
            ),
            $dbDriver->insert
        );
        $this->assertEquals(1, $dbDriver->beginTransaction);
        $this->assertEquals(1, $dbDriver->commit);
    }
}


class MockCVEModel extends \Cve\Models\CVEModel
{
    public function __construct() {
        $logger = new \CveTests\Mocks\MockLogger();
        $dbDriver = new \CveTests\Mocks\MockDBDriver();
        parent::__construct($logger, $dbDriver);
    }

    /**
     * @return \CveTests\Mocks\MockDBDriver
     */
    public function getDbDriver() {
        return $this->dbDriver;
    }
}