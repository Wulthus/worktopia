<?php
require __DIR__ . "/../vendor/autoload.php";
require "../helpers.php";

use Framework\Router;



//-----------------------------------------------AUTOLOADER

// spl_autoload_register(function($class){
//     $path = basePath("Framework" . $class . ".php");
//     if(file_exists($path)){
//         require $path;
//     };
// });

//----------------------------------------------------ROUTER

$config = require basepath("config/database.php");
$router = new Router();
$routes = require"../routes.php";

//------------------------------------------GETTING SERVER INFO

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

//------------------------------------------ROUTING REQUEST
$router->route($uri, $method);