<?php

namespace CveTests\Mocks;

use Cve\Models\CVEModel;

class MockCVEModel extends CVEModel
{
    public $savedData = [];
    public function saveCVERecord($data) {
        $this->savedData[] = $data;
    }
}