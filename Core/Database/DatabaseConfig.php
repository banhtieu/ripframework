<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 1/25/15
 * Time: 12:09 AM
 */

namespace Core\Database;

/**
 * Class DatabaseConfig
 * @package Application\Config
 */
class DatabaseConfig {

    /**
     * @var string data source name
     */
    public $dsn;

    /**
     * @var string username
     */
    public $username;

    /**
     * @var string password
     */
    public $password;

    /**
     *
     * Create a database configuration
     *
     * @param $dsn
     * @param $username
     * @param $password
     */
    public function __construct($dsn, $username, $password) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
    }

} 