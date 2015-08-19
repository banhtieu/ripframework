<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/31/2015
 * Time: 4:26 AM
 */

namespace Application\Config;
use Core\Database\DatabaseConfig;

/**
 * Class Configuration
 * @package Application\Config
 */
class ApplicationConfig {

    /**
     * @return DatabaseConfig[] the database configuration
     */
    public static function databaseConfiguration() {
        echo "Get database configuration";

        return array(
            'dev' => new DatabaseConfig('mysql:host=127.0.0.1;dbname=ripframework_dev', 'root', ''),
            'test' => new DatabaseConfig('mysql:host=127.0.0.1;dbname=ripframework_test', 'root', ''),
            'production' => new DatabaseConfig('mysql:host=localhost;dbname=dailypla_ripframework', 'dailypla_root', 'fbnd?u&ONH26')
        );
    }
}