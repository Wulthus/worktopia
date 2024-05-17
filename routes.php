<?php

$getRoutes = [
    "/" => "HomeController@index",
    "/listings" => "ListingController@index",
    "/listings/create" => "ListingController@create",
    "/listing" => "ListingController@details",
    // "/" => 'App/controllers/home.php',
    // "/listings/create" => "App/controllers/listings/create/create.php",

    // "404" => "App/controllers/errors/404.php",
];


foreach ($getRoutes as $uri => $controller){
    $router->get($uri, $controller);
}
