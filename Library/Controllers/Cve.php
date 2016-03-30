<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 24/03/2016
 * Time: 14:04
 */

namespace Cve\Controllers;

use Cve\DB\PdoDriver;
use Cve\Helpers\Config;
use Cve\Models\CVEModel;

class Cve extends CoreController
{
    /**
     * @var \Cve\Models\CVEModel
     */
    protected $cveModel;


    public function index($arguments) {
        $cveNumber = !empty($arguments[0]) ? $arguments[0] : null;
        if ($cveNumber) {
            $this->getCVE($cveNumber);
        } else {
            $this->getCVEs();
        }
    }

    public function getCVEs() {
        $this->checkRequestMethod('GET');
        $limit = (int)(isset($_GET['limit']) && $_GET['limit']) ? $_GET['limit'] : Config::getConfig('default_get_limit');
        $offset = (int)(isset($_GET['offset']) && $_GET['offset']) ? $_GET['offset'] : 0;
        $year = (int)(isset($_GET['year']) && $_GET['year']) ? 'CVE-' . $_GET['year'] . '%' : null;
        $result = $this->cveModel->getRecords($limit, $offset, $year);
        $this->outputData($result);
    }

    public function getCVE($cveNumber) {
        $this->checkRequestMethod('GET');
        $result = $this->cveModel->getRecord($cveNumber);
        $this->outputData($result, true);
    }

    /**
     * Allows for mocking for unit testing
     */
    protected function setModels() {
        $this->cveModel = new CVEModel($this->logger, PdoDriver::getInstance());
    }
}