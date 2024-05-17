<?php

namespace App\Controllers;


class ErrorController {

    public function __construct(){
    }

    public static function notFound($message = "Requested page could not be found on the server"){
        http_response_code(404);
        loadView("error", [
            "status"=> '404',
            "message"=> $message,
        ]); 
    }

    public static function unauthorised($message = "You are not authorised to view this page"){
        http_response_code(403);
        loadView("error", [
            "status"=> '403',
            "message"=> $message,
        ]); 
    }
};