<?php

session_start();
header('Content-Type: text/html; charset=utf-8');

spl_autoload_register('auto_load');

/** SITE CONSTANTS */
define('APP_PATH', dirname(__FILE__));
define('PATH_CLASSES', APP_PATH . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR);
define('PATH_VIEWS', APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('URL_ROOT', 'http://' . $_SERVER['SERVER_NAME'] . '/index.php');
define('URL_CSS', 'http://' . $_SERVER['SERVER_NAME'] . '/css/');


function find_file($class){
    if (is_file(PATH_CLASSES . $class . '.php'))
        return PATH_CLASSES . $class . '.php';
    else
        return false;
}

function auto_load($class){

    $file = str_replace('_', DIRECTORY_SEPARATOR, $class);

    if ($path = find_file($class)) {
        // Load the class file
        require $path;

        // Class has been found
        return TRUE;
    }

    // Class is not in the filesystem
    return FALSE;
}

