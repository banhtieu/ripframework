<?php
/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 1/25/15
 * Time: 6:54 AM
 */

namespace Core\Database;


/**
 * Decorate the name in php
 * Class NamingDecorator
 * @package Core\Database
 */
class NameDecorator {

    /**
     * Get Table name from class name
     * @param $className
     * @return string name of the table
     */
    public static function getTableName($className) {
        return strtolower($className) . "s";
    }

} 