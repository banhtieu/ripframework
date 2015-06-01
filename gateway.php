<?php

/**
 * Report everything
 */
error_reporting(E_ALL);

// use Core Namespace
use Core\Application;
use Core\Migration;

require_once 'Core/Autoload.php';

function main() {
    // do a migration
    $migration = new Migration();
    $migration->execute();

    // initialize an application
    $application = new Application();
    $application->render();

    $serviceManager = new \Core\Service\ServiceManager();
    $serviceManager->scan();

    $serviceManager->processRequest();
}

main();
