<?php

namespace CveTests\Mocks;

class MockConfig extends \Cve\Helpers\Config {

    public function __construct() {
        //clear config
        parent::$config = null;
    }

    public function getConfigVar() {
        return parent::$config;
    }
}