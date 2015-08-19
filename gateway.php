<?php

/**
 * Report everything
 */
error_reporting(E_ALL);

// use Core Namespace
use Core\Application;
use Core\Migration;
use Core\Service\ServiceManager;

require_once 'Core/Autoload.php';


function main() {
    // do a migration

    $migration = new Migration();
    $migration->execute();

    $serviceManager = new ServiceManager();
    $serviceManager->scan();

    $serviceManager->processRequest();
}

try {
    main();
}catch (Exception $e){
    echo "Exception ";
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
}
