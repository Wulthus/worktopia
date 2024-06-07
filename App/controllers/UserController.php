<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class UserController {

    protected $database;

    public function __construct(){
        $config = require basepath("config/database.php");
        $this->database = new Database($config);
    }

    /**
     * Function to log user in
     * 
     * @return void
     */
    public function login(){
        loadview("users/login/login");
    }

    /**
     * Function to register new user
     * 
     * @return void
     */
    public function register(){
        loadview("users/register/register");
    }

    /**
     * Function to stor newly registered user in the database
     * 
     * @return void
     */
    public function store(){

        $values = [];
        foreach ($_POST as $name => $value) {
            $values[$name] = $value;
        };

        $errors = [];
        if (!Validation::validateEmail($values["email"])){
            $errors["email"] = "Please enter valid email adress.";
        }

        if (!empty($errors)){
            loadView("users/register/register", $user = $values);
            exit;
        };
        inspectValueANdHold("Stored");

    }
}