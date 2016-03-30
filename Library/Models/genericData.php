<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 30/03/2016
 * Time: 09:29
 */

namespace Cve\Models;


class genericData implements DataInterface
{
    public $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }
}