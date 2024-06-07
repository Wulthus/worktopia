<?php

$getRoutes = [
    "/" => "HomeController@index",
    "/listings" => "ListingController@index",
    "/listings/create" => "ListingController@create",
    "/listing" => "ListingController@details",
    "/listing/edit" => "ListingController@edit",
    "/register" => "UserController@register",
    "/login" => "UserController@login",
];

$postRoutes = [
    "/listings" => "ListingController@store",
    "/register" => "UserController@store",

];

$deleteRoutes = [
    "/listing" => "ListingController@delete",
];

$putRoutes = [
    "/listing/update" => "ListingController@update",
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

//---------------------------------------------PUT ROUTES

foreach ($putRoutes as $uri => $controller){
    $router->put($uri, $controller);
}