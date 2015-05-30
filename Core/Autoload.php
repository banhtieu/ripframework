<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/30/2015
 * Time: 11:46 PM
 */

/**
 * @param $className string the class name
 */
function rip_auto_load($className) {
    // find the class path
    $classPath = str_replace("\\", "/", $className . ".php");

    // if file exists then include $classPath
    if (file_exists($classPath)) {
        include $classPath;
    }
}

spl_autoload_register("rip_auto_load");