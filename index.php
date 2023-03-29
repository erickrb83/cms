<?php
session_start();

use \Core\{Config, Router};
//or use \Core\Config;
// user \Core\Route;

// define constants that are Global
// Get root where the index.php resides.
define('PROOT', __DIR__);
// Makes it compatibility with whichever OS is used. 
define('DS', DIRECTORY_SEPARATOR);

// PHP library function Standard Php Library
spl_autoload_register(function($className){
    $parts = explode('\\', $className);
    $class = end($parts);
    array_pop($parts);
    $path = strtolower(implode(DS, $parts));
    $path = PROOT . DS . $path . DS . $class . '.php';
    if(file_exists($path)){
        include($path);
    }
});

$rootDir = Config::get('root_dir');
// Defines a constant of Root to call on anywhere in app. Best for Config
define('ROOT', $rootDir);

$url = $_SERVER['REQUEST_URI'];
$url = str_replace(ROOT, '', $url);
// RegEx search and replace to get rid of the '?' query string
// Starts and ends '/.../', () groups expression
// '\' is used in front of symbol to show it's a symbol
// '\?' -> find symbol
// '.+' -> '.' find any character '+' all instance
// replace instances with empty string, and assigns it to $url
$url = preg_replace('/(\?.+)/', '', $url);
Router::route($url);