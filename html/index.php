<?php

define('APP_DIR', dirname(__FILE__) . '/../app/');
require APP_DIR . 'ClassLoader.php';

/**
 * Autoload Classes
 *
 * @note All classes must match file name
 * @note Classes only go in app or classes folder
 * @note Do not name custom classes the same as app classes
 */
spl_autoload_register( function ($className) {
    $loader = new ClassLoader($className, APP_DIR, ['..', '.', 'config']);
    $loader->doRequire();
});



// Instantiate Core
$bc = new \Bc\App\Core(dirname(__FILE__));