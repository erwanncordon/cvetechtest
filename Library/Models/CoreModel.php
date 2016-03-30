<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 25/03/2016
 * Time: 19:22
 */

namespace Cve\Models;


use Cve\DB\DBInterface;
use Monolog\Logger;

class CoreModel
{
    protected $dbDriver;
    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * Sets which db drive will be used.
     * CoreModel constructor.
     * @param $logger
     * @param DBInterface $dbDriver
     */
    public function __construct($logger, $dbDriver = null) {
        $this->logger = $logger;
        $this->dbDriver = $dbDriver;
    }
}