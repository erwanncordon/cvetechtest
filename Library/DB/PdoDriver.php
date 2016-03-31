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


class PdoDriver implements DBInterface
{
    /**
     * @var PdoDriver The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * @var PDO
     */
    public $db;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return PdoDriver
     */
    public static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct() {
        $dbConfig = Config::getConfig('db');
        $dsn = 'mysql:dbname=' . $dbConfig['dbname'] . ';host=' . $dbConfig['host'] . ';port=' . $dbConfig['port'] . '';
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        try {
            $this->db = new PDO($dsn, $username, $password); // also allows an extra parameter of configuration
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Could not connect to the database:<br/>' . $e);
        }
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone() {
    }

    public function beginTransaction() {
        $this->db->beginTransaction();
    }

    /**
     * @throws PDOException
     */
    public function commit() {
        if ($this->db->inTransaction()) {
            $this->db->commit();
        } else {
            throw new PDOException('Cannot commit, not in Transaction');
        }
    }

    /**
     * @throws PDOException
     */
    public function rollBackTransaction() {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        } else {
            throw new PDOException('Cannot rollback, not in Transaction');
        }
    }


    /**
     * Fetch List of data of database
     * @param $table
     * @param array $where formate: array(array('AND|OR', operator, mysqlField, Value))
     * @param mixed $limit
     * @param int $offset
     * @param mixed $class
     * @param string $fields
     * @return mixed
     */
    public function fetchAll($table, $where, $limit = null, $offset = 0, $class = null, $fields = '*') {
        $class = $class ?: null;
        $sql = "SELECT $fields From $table";
        if ($where) {
            $sql .= ' WHERE ';
            $whereClause = '';

            foreach ($where as $value) {
                $whereClause .= " " . strtoupper($value[0]) . " $value[2] $value[1] :$value[2]";
            }
            //remove any remaining ANDs or ORs
            $whereClause = trim($whereClause, ' AND ');
            $whereClause = trim($whereClause, ' OR ');
            $sql .= $whereClause;
        }
        if ($limit !== null) {
            $sql .= ' limit :limit';
        }
        if ($offset) {
            $sql .= ' offset :offset';
        }

        if ($where || $limit !== null || $offset) {
            $statement = $this->db->prepare($sql);
            foreach ($where as $value) {
                $type = null;
                //attempt to get correct Type, default to null
                if (!empty($value[4])) {
                    $type = $this->convertTypeToPDOType($value[4]);
                }
                $statement->bindValue(":$value[2]", $value[3], $type);
            }

            if ($limit !== null) {
                $statement->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
            }
            if ($offset) {
                $statement->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
            }

            $statement->execute();
            $results = $statement;
        } else {
            $results = $this->db->query($sql);
        }
        //binds results to a class and returns the new class
        if ($class) {
            return $results->fetchAll(PDO::FETCH_CLASS, $class);
        }
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * @param $table
     * @param array $where format: array(array('AND|OR', operator, mysqlField, Value))
     * @param null $class
     * @return mixed
     * @internal param null $limit
     * @internal param int $offset
     */
    public function fetch($table, $where, $class = null) {
        return $this->fetchAll($table, $where, 1, 0, $class);
    }

    /**
     * @param string $table
     * @param array $parameters [field=>[value, type]]
     * ß*/
    public function insert($table, $parameters) {
        $sql = "INSERT INTO $table(";
        $inserts = '';
        foreach ($parameters as $field => $value) {
            $inserts .= "$field,";
        }
        $inserts = rtrim($inserts, ',');
        $sql .= $inserts . ') VALUES (';
        $values = '';
        foreach ($parameters as $field => $value) {
            $values .= ":$field,";
        }
        $values = rtrim($values, ',');
        $sql .= $values . ')';
        $statement = $this->db->prepare($sql);
        foreach ($parameters as $field => $value) {
            $statement->bindValue(":$field", $value[0], $this->convertTypeToPDOType($value[1]));
        }
        $statement->execute();
    }

    /**
     * @param string $table
     */
    public function truncate($table) {
        $sql = "TRUNCATE TABLE " . $table;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    /**
     * @param $type
     * @return int|null
     */
    protected function convertTypeToPDOType($type) {
        return $type === 'string' ? PDO::PARAM_STR : $type === 'int' ? PDO::PARAM_INT : null;
    }
}