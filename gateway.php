<?php

/**
 * Report everything
 */
error_reporting(E_ALL);

// use Core Namespace
use Core\Application;
use Core\Migration;

echo "Calling Gateway";

require_once 'Core/Autoload.php';

echo "After Autoload";

function main() {
    // do a migration

    echo "Before Migration";

    $migration = new Migration();
    $migration->execute();

    echo "After migration";

    // initialize an application
    $application = new Application();
    $application->render();

    $serviceManager = new \Core\Service\ServiceManager();
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
