<?php

namespace CveTests\Mocks;

use Cve\Models\CVEModel;

class MockCVEModel extends CVEModel
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

    public $savedData = [];
    public function saveCVERecord($data) {
        $this->savedData[] = $data;
    }
}