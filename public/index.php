<?php
require "../helpers.php";

//------------------------------------------DATABASE
require basePath("Database.php");
$config = require basepath("config/database.php");

//------------------------------------------ROUTER & ROUTES
require basePath("Router.php");
$router = new Router();
$routes = require"../routes.php";

//------------------------------------------GETTING SERVER INFO
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

//------------------------------------------ROUTING REQUEST
$router->route($uri, $method);