<?php

/**
 * Report everything
 */
error_reporting(E_ALL);

// use Core Namespace
use Core\Application;
use Core\Migration;

require_once 'Core/Autoload.php';

// do a migration
$migration = new Migration();
$migration->execute();

// initialize an application
$application = new Application();
$application->render();

$scanner = new \Core\Service\ServiceManager();
$scanner->scan();

$scanner->processRequest();