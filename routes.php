<?php

// $getRoutes = [
//     "/" => "HomeController@index",
//     "/listings" => "ListingController@index",
//     "/listings/create" => "ListingController@create",
//     "/listing" => "ListingController@details",
//     "/listing/edit" => "ListingController@edit",
//     "/register" => "UserController@register",
//     "/login" => "UserController@login",
// ];

$getRoutes = [
    ["/", "HomeController@index",],
    ["/listings", "ListingController@index",],
    ["/listings/create", "ListingController@create", ["auth"]],
    ["/listing", "ListingController@details",],
    ["/listing/edit", "ListingController@edit", ["auth"]],
    ["/register", "UserController@register", ["guest"]],
    ["/login", "UserController@login", ["guest"]],
];

$postRoutes = [
    ["/listings", "ListingController@store", ["auth"]],
    ["/register", "UserController@store",],
    ["/logout", "UserController@logout", ["auth"]],
    ["/login", "UserController@authenticate", ["guest"]],

];

$deleteRoutes = [
    ["/listing", "ListingController@delete", ["auth"]],
];

$putRoutes = [
    ["/listing/update", "ListingController@update", ["auth"]],
];

//---------------------------------------------GET ROUTES

foreach ($getRoutes as $route){
    $router->get($route[0], $route[1], $route[2] ??= []);
}

//---------------------------------------------POST ROUTES

foreach ($postRoutes as $route){
    $router->post($route[0], $route[1], $route[2] ??= []);
}

//---------------------------------------------DELETE ROUTES

foreach ($deleteRoutes as $route){
    $router->destroy($route[0], $route[1], $route[2] ??= []);
}

//---------------------------------------------PUT ROUTES

foreach ($putRoutes as $route){
    $router->put($route[0], $route[1], $route[2] ??= []);
}