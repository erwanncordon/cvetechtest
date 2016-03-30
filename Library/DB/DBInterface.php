<?php
/**
 * Created by PhpStorm.
 * User: eco06
 * Date: 25/03/2016
 * Time: 19:44
 */

namespace Cve\DB;

use Cve\Helpers\Config;
use PDO;
use PDOException;


interface DBInterface
{
    /**
     * @return mixed
     */
    public function beginTransaction();

    /**
     * @return mixed
     */
    public function commit();

    /**
     * @return mixed
     */
    public function rollBackTransaction();

    /**
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
     * @param $table
     * @param $where
     * @param null $class
     * @return mixed
     */
    public function fetch($table, $where, $class = null);

    /**
     * @param $table
     * @return mixed
     */
    public function truncate($table);

    public function insert($table, $parameters);


}