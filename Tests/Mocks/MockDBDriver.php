<?php

namespace CveTests\Mocks;

use Cve\DB\DBInterface;

class MockDBDriver implements DBInterface
{
    /**
     * @var int
     */
    public $beginTransaction = 0;
    /**
     * @var int
     */
    public $commit = 0;
    /**
     * @var int
     */
    public $rollBackTransaction = 0;
    /**
     * @var array
     */
    public $fetchAll = [];
    /**
     * @var array
     */
    public $fetch = [];
    /**
     * @var array
     */
    public $truncate = [];
    /**
     * @var array
     */
    public $insert = [];

    /**
     * @return mixed
     */
    public function beginTransaction() {
        $this->beginTransaction++;
    }

    /**
     * @return mixed
     */
    public function commit() {
        $this->commit++;
    }

    /**
     * @return mixed
     */
    public function rollBackTransaction() {
        $this->rollBackTransaction++;
    }

    /**
     * @param $table
     * @param $where
     * @param null $limit
     * @param int $offset
     * @param null $class
     * @param string $fields
     * @return mixed
     */
    public function fetchAll($table, $where, $limit = null, $offset = 0, $class = null, $fields = '*') {
        $this->fetchAll[] = func_get_args();
    }

    /**
     * @param $table
     * @param $where
     * @param null $class
     * @return mixed
     */
    public function fetch($table, $where, $class = null) {
        $this->fetch[] = func_get_args();
    }

    /**
     * @param $table
     * @return mixed
     */
    public function truncate($table) {
        $this->truncate[] = func_get_args();
    }

    public function insert($table, $parameters) {
        $this->insert[] = func_get_args();
    }
}