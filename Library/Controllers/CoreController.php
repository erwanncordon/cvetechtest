<?php
namespace Cve\Controllers;

use Monolog\Logger;
use SimpleXMLElement;

abstract class CoreController
{
    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $responseType;

    /**
     * CoreController constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger) {

        $acceptHeader = $this->getHeader('Accept');
        //it will default to xml if it isn't json
        $this->responseType = stristr($acceptHeader, 'application/json') ? 'json' : 'xml';
        $this->logger = $logger;
        $this->setModels();
    }

    abstract protected function setModels();

    /**
     * @param $expected
     * @throws \Exception if request method does not match expected
     */
    public function checkRequestMethod($expected) {
        if (strtolower($_SERVER['REQUEST_METHOD']) !== strtolower($expected)) {
            throw new \Exception('Request method should be: ' . strtoupper($expected) . ' and not: ' . $_SERVER['REQUEST_METHOD']);
        }
    }

    public function getHeader($header) {
        return getallheaders()[$header];
    }
}