<?php

$getRoutes = [
    "/" => "HomeController@index",
    "/listings" => "ListingController@index",
    "/listings/create" => "ListingController@create",
    "/listing" => "ListingController@details",
    "/listing/edit" => "ListingController@edit",
];

$postRoutes = [
    "/listings" => "ListingController@store",
    "/listing/{id}" => "ListingController@update",
];

$deleteRoutes = [
    "/listing" => "ListingController@delete",
];

//---------------------------------------------GET ROUTES

foreach ($getRoutes as $uri => $controller){
    $router->get($uri, $controller);
}

//---------------------------------------------POST ROUTES

foreach ($postRoutes as $uri => $controller){
    $router->post($uri, $controller);
}

//---------------------------------------------DELETE ROUTES

foreach ($deleteRoutes as $uri => $controller){
    $router->destroy($uri, $controller);
}