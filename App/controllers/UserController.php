<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController {

    protected $database;
    
    /**
     * Helper Function that extracts variables from POST request and returns them in an array
     * 
     * @return array
     */

    protected function getPostValues(){
        $values = [];
        foreach ($_POST as $name => $value) {
            $values[$name] = $value;
        };
        return $values;
    }

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

        $values = $this->getPostValues();

        // $values = [];
        // foreach ($_POST as $name => $value) {
        //     $values[$name] = $value;
        // };


        //---------------------------------------------------------------------------------FORM VALIDATION

        $errors = [];
        if (!Validation::validateEmail($values["email"])){
            $errors["email"] = "Please enter valid email adress.";
        }
        if (!Validation::validateString($values["name"], 2, 50)){
            $errors["name"] = "Name must be between 2 and 50 characters long.";
        }
        if (!Validation::validateString($values["password"], 6, 50)){
            $errors["password"] = "Password must be between 6 and 50 characters long.";
        }
        if (!Validation::compareStrings($values["password"], $values["password_confirmation"])){
            $errors["parrword_confirmation"] = "Passwords do not match.";
        }


        //---------------------------------------------------------------------------------REDIRECT IF ERRORS PRESENT

        if (!empty($errors)){
            loadView("users/register/register", [
                "user" => $values,
                "errors" => $errors,
            ]);
            exit;
        };

        //---------------------------------------------------------------------------------DB QUERY TO CHECK IF EMAIL ALREDY EXISTS

        $dbParams = [
            'email' => $values['email'],
        ];

        $query = "SELECT * FROM users WHERE email = :email";
        $user = $this->database->query($query, $dbParams)->fetchAll();

        if ($user){
            $errors['email'] = "User with that email adress alredy exists.";
            loadView("users/register/register", [
                "user" => $values,
                "errors" => $errors,
            ]);
            exit;
        };

        //---------------------------------------------------------------------------------PUSH NEW USER INTO DB

        $dbParams = [];
        foreach ($values as $name => $value) {
            if ($name === 'password'){
                $dbParams[$name] = password_hash($value, PASSWORD_DEFAULT);
            } else if ($name !== 'password_confirmation'){
                $dbParams[$name] = $value;
            };
        };

        $query = "INSERT INTO users (email, name, password, city, state) VALUES (:email, :name, :password, :city, :state)";
        $this->database->query($query, $dbParams);

        //--------------------------------------------------------------------------------START SESSION WITH NEW USER

        $userID = $this->database->connection->lastInsertId();
        Session::setValue('user', [
            'id' => $userID,
            'name'=> $values['name'],
            'email'=> $values['email'],
            'city' => $values['city'],
            'state'=> $values['state'],
        ]);        
        //--------------------------------------------------------------------------------REDIRECT NEW USER

        redirect("/");

    
    }

    /**
     * Logout user and destroy session
     * 
     * @return void
     */
    public function logout(){
        Session::dropSession();

        $cookieParams = session_get_cookie_params();
        setcookie("PHPSESSID", '', time()- 86400, $cookieParams['path'], $cookieParams['domain']);

        redirect("/login");
    }

    /**
     * Authenticate and log in user.
     * 
     * @return void
     */

     public function authenticate(){
        
        $values = $this->getPostValues();
        
        $errors = [];
        
        if (!Validation::validateEmail($values["email"])){
            $errors["email"] = "Please enter valid email adress.";
        }
        if (!Validation::validateString($values["password"], 6, 50)){
            $errors["password"] = "Password must be between 6 and 50 characters long.";
        }

        //--------------------------------------------------------------------------------REDIRECT IF ERRORS PRESENT

        if (!empty($errors)){
            loadView("users/login/login", [
                "user" => $values,
                "errors" => $errors,
            ]);
            exit;
        };

        //-------------------------------------------------------------------------------VALIDATE CREDENTIALS

        $dbParams = [
            'email' => $values['email'],
        ];

        $user = $this->database->query("SELECT * FROM users WHERE email = :email", $dbParams)->fetch();

        if(!$user || !password_verify($values['password'], $user->password)){
            $errors["email"] = "User with provided cfedentials was not found";
            loadView("users/login/login", [
                "user" => $values,
                "errors" => $errors,
            ]);
            exit;
        };

        //-----------------------------------------------------------------------------SET SESSION
        

        Session::setValue('user', [
            'id' => $user->id,
            'name'=> $user->name,
            'email'=> $user->email,
            'city' => $user->city,
            'state'=> $user->state,
        ]);        
        
        //--------------------------------------------------------------------------------REDIRECT USER

        redirect("/");

    }



}