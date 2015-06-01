<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/31/2015
 *
 */

require_once 'Core/Autoload.php';

define('TESTING', true);


// do a migration
$migration = new \Core\Migration();
$migration->execute(true);
unset($migration);

