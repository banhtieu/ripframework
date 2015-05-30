<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 1/25/15
 * Time: 12:13 AM
 */


namespace Core;

use Application\Config\DatabaseConfig;

/**
 * Class Database
 * Manage all operation related to database
 * @package Core
 */
class Database {

    /**
     * @var \PDO the pdo connection
     */
    private $pdo;


    /**
     * The static instance
     * @var Database
     */
    private static $instance = null;


    /**
     * The default constructor - setup connection
     */
    public function __construct() {

        // set up the connection
        $this->pdo = new \PDO(DatabaseConfig::CONNECTION_STRING,
                            DatabaseConfig::USERNAME,
                            DatabaseConfig::PASSWORD);
    }


    /**
     * Get an instance of a database
     * @return Database
     */
    public static function getInstance() {

        // create an instance if there is no instance
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }


    /**
     * Execute query
     * @param string $query the query
     * @param string className
     * @return mixed Query result
     */
    public function executeQuery($query, $className = "stdClass") {
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * Execute an update query
     * @param $queryString
     * @param $values
     * @return int number of affected rows
     */
    public function executeUpdate($queryString, $values = array()) {
        $query = $this->pdo->prepare($queryString);
        $statement = $query->execute($values);
        return $statement;
    }

    /**
     * Escape a string
     * @param $value
     * @return string
     */
    public function escapeString($value) {
        return $this->pdo->quote($value);
    }


    /**
     * Get last insert Id
     * @return string
     */
    public function getInsertId() {
        return $this->pdo->lastInsertId();
    }
} 