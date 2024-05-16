<?php

$getRoutes = [
    "/" => "HomeController@index",
    // "/" => 'App/controllers/home.php',
    // "/listings" => "App/controllers/listings/listings.php",
    // "/listings/create" => "App/controllers/listings/create/create.php",
    // "/listing" => "App/controllers/listings/show/details.php",
    // "404" => "App/controllers/errors/404.php",
];


foreach ($getRoutes as $uri => $controller){
    $router->get($uri, $controller);
}
