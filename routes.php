<?php

$getRoutes = [
    "/" => 'controllers/home.php',
    "/listings" => "controllers/listings/listings.php",
    "/listings/create" => "controllers/listings/create/create.php",
    "/listing" => "controllers/listings/show/details.php",
    "404" => "controllers/errors/404.php",
];


foreach ($getRoutes as $uri => $controller){
    $router->get($uri, $controller);
}
