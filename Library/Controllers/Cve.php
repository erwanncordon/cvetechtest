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

    /**
     * index either gets on record or a set of records depending on if a cveNumber is supplied or not
     * @param array $arguments
     */
    public function index($arguments) {
        $cveNumber = !empty($arguments[0]) ? $arguments[0] : null;
        if ($cveNumber) {
            $this->getCVE($cveNumber);
        } else {
            $this->getCVEs();
        }
    }

    /**
     * Gets a list of cveRecord objects and sends them to outputData
     * @throws \Cve\Exceptions\IncorrectContentTypeException
     * @throws \Cve\Exceptions\MissingConfigException
     */
    public function getCVEs() {
        $this->checkRequestMethod('GET');
        $limit = (int)(isset($_GET['limit']) && $_GET['limit']) ? $_GET['limit'] : Config::getConfig('default_get_limit');
        $offset = (int)(isset($_GET['offset']) && $_GET['offset']) ? $_GET['offset'] : 0;
        $year = (int)(isset($_GET['year']) && $_GET['year']) ? 'CVE-' . $_GET['year'] . '%' : null;
        $result = $this->cveModel->getRecords($limit, $offset, $year);
        $this->outputData($result);
    }

    /**
     * Gets a single cveRecord objects and sends them to outputData
     * @param $cveNumber
     * @throws \Cve\Exceptions\IncorrectContentTypeException
     */
    public function getCVE($cveNumber) {
        $this->checkRequestMethod('GET');
        $result = $this->cveModel->getRecord($cveNumber);
        $this->outputData($result);
    }

    /**
     * sets the cveModel
     */
    protected function setModels() {
        $this->cveModel = new CVEModel($this->logger, PdoDriver::getInstance());
    }
}