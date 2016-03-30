<?php
namespace Cve\Controllers;

use Cve\Exceptions\IncorrectContentTypeException;
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
            throw new IncorrectContentTypeException('Request method should be: ' . strtoupper($expected) . ' and not: ' . $_SERVER['REQUEST_METHOD']);
        }
    }

    /**
     * @param $results
     * @param bool $single
     * @return mixed
     */
    protected function convertResultData($results, $single = false) {
        if (empty($results)) {
            $results = array();
        }
        if ($results) {
            //foreach is needed for when multiple records are passed in, it will convert and array of objects to an array of arrays
            if ($single) {
                $results = $results->getData();
            } else {
                foreach ($results as &$result) {
                    $result = $result->getData();
                }
            }
        }
        if ($this->responseType === 'json') {
            //utf8 encode all the data, otherwise it causes errors when json_encode is performed, one example is 'Hübner' from a comment.
            //this could be done before the data is stored to mysql, but it would also mean that the xml output would be utf8 encoded when it doesn't need to be.
            array_walk_recursive($results, function (&$item, $key) {
                if (!mb_detect_encoding($item, 'utf-8', true)) {
                    $item = utf8_encode($item);
                }
            });
            return json_encode($results);
        } else {
            if ($single) {
                $xml = '<?xml version="1.0"?><record></record>';
            } else {
                $xml = '<?xml version="1.0"?><records></records>';
            }
            $xml_data = new SimpleXMLElement($xml);
            $this->array_to_xml($results, $xml_data, 'records');
            return $xml_data->asXML();
        }

    }

    public function array_to_xml($data, &$xml_data, $parentNode) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = rtrim($parentNode, 's'); //dealing with <0/>..<n/> issues
                }
                $subnode = $xml_data->addChild($key);
                $this->array_to_xml($value, $subnode, $key);
            } else {
                if (is_numeric($key)) {
                    $key = rtrim($parentNode, 's'); //dealing with <0/>..<n/> issues
                }
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * @param $data
     * @param $single
     */
    public function outputData($data, $single = false) {
        if ($this->responseType === 'json') {
            $this->setHeader('Content-Type: application/json;charset=utf-8');
        } else {
            $this->setHeader('Content-Type: application/xml');
        }
        $this->writeOutput($this->convertResultData($data, $single));
    }

    /**
     * used for mocking in test.
     * @param $data
     */
    protected function writeOutput($data) {
        echo $data;
    }

    protected function setHeader($header) {
        header($header);
    }

    public function getHeader($header) {
        return getallheaders()[$header];
    }
}