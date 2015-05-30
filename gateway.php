<?php

/**
 * Report everything
 */
error_reporting(E_ALL);

// use Core Namespace
use Core\Application;
use Core\Migration;

/**
 * Autoload a class name
 * @param $className
 */
function __autoload($className) {

    // find the class path
    $classPath = str_replace("\\", "/", $className . ".php");

    // if file exists then include $classPath
    if (file_exists($classPath)) {
        include $classPath;
    }
}

// do a migration
$migration = new Migration();
$migration->execute();

// initialize an application
$application = new Application();
$application->render();

$scanner = new \Core\Service\ServiceManager();
$scanner->scan();

$scanner->processRequest();