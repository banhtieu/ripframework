<?php
/**
 * Created by PhpStorm.
 * User: banh tieu
 * Date: 2/1/15
 * Time: 10:53 AM
 */

namespace Core\Database;

/**
 * Class Repository
 * @property Collection Post
 * @package Core\Database
 */
class Repository {


    /**
     * @var Collection[] cached collections
     */
    static $collections = array();

    /**
     * @param $name
     * @return Collection
     */
    public static function get($name) {

        if (isset(self::$collections[$name])) {
            $collection = self::$collections[$name];
        } else {
            $collection = new Collection($name);
            self::put($name, $collection);
        }

        return $collection;
    }


    /**
     * Push a collections
     * @param $name
     * @param $collection
     */
    public static function put($name, $collection) {
        self::$collections[$name] = $collection;
    }
}