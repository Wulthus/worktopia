<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController {

    protected $database;

    public function __construct(){
        $config = require basePath("config/database.php");
        $this-> database = new Database($config);
    }

    public function index(){
        $listings = $this->database->query("SELECT * FROM listings")->fetchAll();
        loadView("home", ["listings"=> $listings]); 
    }

    public function create(){
        loadView("listings/create/create");
    }

    public function details(){
        $id = $_GET['id'] ?? '';

        $params = [
            'id' => $id,
        ];

        $job = $this->database->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        loadView("listings/show/details", [
            "job"=> $job,
        ]);
    }
};