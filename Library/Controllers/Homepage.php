<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 24/03/2016
 * Time: 14:04
 */

namespace Cve\Controllers;

class Homepage extends CoreController
{
    public function index() {
        echo 'Server up and running';
    }

    protected function setModels() {
    }
}