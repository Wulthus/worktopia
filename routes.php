<?php

$getRoutes = [
    "/" => 'controllers/home.php',
    "/listings" => "controllers/listings/index.php",
    "/listings/create" => "controllers/listings/create/create.php",
    "404" => "controllers/errors/404.php",
];


foreach ($getRoutes as $uri => $controller){
    $router->get($uri, $controller);
}
