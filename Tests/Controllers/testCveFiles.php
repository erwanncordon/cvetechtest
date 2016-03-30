<?php

use CveTests\Mocks\MockConfig;

class testCveFiles extends PHPUnit_Framework_TestCase
{
    public function testIndexChecksRequestTypeAndSendOutPut() {
        $mockCveFiles = $this->getMock('mockCveFiles', array('checkRequestMethod', 'parseFromCsv', 'outPutData'), [], '', false);
        $mockCveFiles->expects($this->once())
            ->method('checkRequestMethod')
            ->with('POST');
        $mockCveFiles->expects($this->once())
            ->method('parseFromCsv');
        $mockCveFiles->index();
    }

    public function testIndexParsesData() {
        $MockConfig = new MockConfig();
        //test file should produce one fail('CVE-1999-0003'), and ignore the line with the regex unmatched names ('CVE-nope-0002')
        $MockConfig::setConfig(
            array(
                'cve_file_location' => __DIR__ . '/../Data/testData.csv'
            )
        );

        $mockCveModel = $this->getMock('\CveTests\Mocks\MockCVEModel', ['clearCVEData'], [], '', false);
        $mockCveModel->expects($this->once())
            ->method('clearCVEData');
        $mockCveFiles = $this->getMock('mockCveFiles', array('checkRequestMethod', 'outPutData'), [true], '', true);
        $mockCveFiles->setCVEModelMock($mockCveModel);
        $mockCveFiles->index();
        //two records processed
        $this->assertEquals(
            array(
                'Parsing file: /Users/eco06/api/cvetechtest/Tests/Controllers/../Data/testData.csv',
                'File finished parsing, 2 records have been saved to the database'
            ),
            $mockCveFiles->logger->info
        );
        //no errors in processing file
        $this->assertEquals(
            array('Failed to save the records: CVE-1999-0003'),
            $mockCveFiles->logger->warn
        );
        $this->assertEquals(
            array(
                array(
                    'name' => 'CVE-1999-0001',
                    'status' => 'Candidate',
                    'description' => 'Some Description',
                    'references' => array
                    (
                        'Some References',
                        'some second reference'
                    ),

                    'phase' => 'some Phaze',
                    'votes' => array
                    (
                        'some vote1',
                        'somesecondvote'
                    ),

                    'comments' => array
                    (
                        array
                        (
                            'author' => 'Commenter',
                            'comment' => 'comment1'
                        ),

                        array
                        (
                            'author' => 'scondCommenter',
                            'comment' => 'comment2'
                        )

                    )

                ),
                array(
                    'name' => 'CVE-1999-0002',
                    'status' => 'Candidate',
                    'description' => 'Some Description',
                    'references' => array
                    (
                        'Some References',
                        'some second reference'
                    ),

                    'phase' => 'some Phaze',
                    'votes' => array
                    (
                        'some vote1',
                        'somesecondvote'
                    ),

                    'comments' => array
                    (
                        array
                        (
                            'author' => 'Commenter',
                            'comment' => 'comment1'
                        ),

                        array
                        (
                            'author' => 'scondCommenter',
                            'comment' => 'comment2'
                        )

                    )

                )
            ),
            $mockCveModel->savedData);
    }

    public function getLoggerMock() {
        return $this->getMock('\Monolog\Logger', array(), array(), '', false);
    }
}

abstract class mockCveFiles extends \Cve\Controllers\CveFiles
{
    public $logger;

    public function __construct($dontRunParnet = false) {
        $logger = new \CveTests\Mocks\MockLogger();
        $this->logger = $logger;
        if (!$dontRunParnet) {
            parent::__construct($logger);
        }
    }

    public function setCVEModelMock($mockCVEModel) {
        return $this->cveModel = $mockCVEModel;
    }

    public function getHeader($header) {
        return $header;
    }
}