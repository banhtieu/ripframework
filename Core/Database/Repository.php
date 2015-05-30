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
     * @param $name
     * @return Collection
     */
    public static function get($name) {
        return new Collection($name);
    }

}