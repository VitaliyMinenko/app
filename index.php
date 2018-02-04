<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 2018-01-18
 * Time: 23:11
 */

use Prt\app\classes;
require __DIR__.'/vendor/autoload.php';

/**
 * Initialization of router.
 */


$router = new Prt\app\classes\Router();
$router->start();


