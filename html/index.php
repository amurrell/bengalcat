<?php

define('APP_DIR', dirname(__FILE__) . '/../app/');

/**
 * Autoload Classes
 * 
 * @note All classes must match file name
 * @note Classes only go in app or classes folder
 * @note Do not name custom classes the same as app classes
 */
spl_autoload_register( function ($className) {
    $classFile = preg_replace('/.*'. preg_quote('\\') .'/', '', $className) . '.php';
    
    $classDirs = array_filter(
        array_diff(scandir(APP_DIR), ['..', '.', 'config']),
        function($dir){
            return is_dir(APP_DIR . $dir);
        }
    );
    
    $coreClass = file_exists(APP_DIR . $classFile);
    $customClass = false;
    foreach ($classDirs as $customClassDir) {
        if (file_exists(APP_DIR . $customClassDir . '/' . $classFile)) {
            $customClass = $customClassDir;
            break;
        }
    }
    
    if ($coreClass || $customClass) {
        $requireFile = APP_DIR . ( (!$coreClass) ? $customClass . '/' : ''  ) . $classFile;
        require $requireFile;
    }
});

// Instantiate Core
$bc = new \Bc\App\Core(dirname(__FILE__));