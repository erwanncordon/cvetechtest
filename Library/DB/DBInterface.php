<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 25/03/2016
 * Time: 19:44
 */

namespace Cve\DB;

interface DBInterface
{
    public function beginTransaction();

    public function commit();

    /**
     * @return mixed
     */
    public function rollBackTransaction();

    /**
     * Fetch List of data of database
     * @param $table
     * @param $where
     * @param null $limit
     * @param int $offset
     * @param null $class
     * @param string $fields
     * @return mixed
     */
    public function fetchAll($table, $where, $limit = null, $offset = 0, $class = null, $fields = '*');

    /**
     * Fetch single item from database
     * @param $table
     * @param $where
     * @param null $class
     * @return mixed
     */
    public function fetch($table, $where, $class = null);

    /**
     * Truncate a specified table
     * @param $table
     */
    public function truncate($table);

    /**
     * Insert Data into Database
     * @param $table
     * @param $parameters
     */
    public function insert($table, $parameters);


}