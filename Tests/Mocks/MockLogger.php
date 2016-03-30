<?php

namespace CveTests\Mocks;

use Monolog\Logger;

class MockLogger extends Logger
{
    public function __construct() {
        parent::__construct('testData');
    }

    public $info;

    public function info($message, array $context = Array()) {
        $this->info[] = $message;
    }

    public $warn;

    public function warn($message, array $context = Array()) {
        $this->warn[] = $message;
    }

    public $debug;

    public function debug($message, array $context = Array()) {
        $this->debug[] = $message;
    }

    public $err;

    public function err($message, array $context = Array()) {
        $this->err[] = $message;
    }
}