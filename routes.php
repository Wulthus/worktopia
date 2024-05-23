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

$postRoutes = [
    "/listings" => "ListingController@store"
];

//---------------------------------------------GET ROUTES

foreach ($getRoutes as $uri => $controller){
    $router->get($uri, $controller);
}

//---------------------------------------------POST ROUTES

foreach ($postRoutes as $uri => $controller){
    $router->post($uri, $controller);
}