<?php

namespace App\Controllers;

use Framework\Database;

class HomeController {

    protected $database;

    public function __construct(){
        $config = require basePath("config/database.php");
        $this-> database = new Database($config);
    }

    public function index(){
        $listings = $this->database->query("SELECT * FROM listings ORDER BY created_at DESC LIMIT 6")->fetchAll();
        loadView("home", ["listings"=> $listings]); 
    }
};