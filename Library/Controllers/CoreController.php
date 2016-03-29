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

    public function getHeader($header) {
        return getallheaders()[$header];
    }
}