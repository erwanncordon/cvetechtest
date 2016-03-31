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
    /**
     * @var string|array $data
     */
    public $data;

    /**
     * genericData constructor.
     * @param array|string $data
     */
    public function __construct($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }
}