<?php
require "../helpers.php";

$routes = [
    "/" => 'controllers/home.php',
    "/listings" => "controllers/listings/listings.php",
    "/listings/create" => "controllers/listings/create/create.php",
    "404" => "controllers/errors/404.php",
];

$uri = $_SERVER['REQUEST_URI'];

if (array_key_exists($uri, $routes)) {
    require basePath($routes[$uri]);
} else {
    require basePath($routes["404"]);
};
