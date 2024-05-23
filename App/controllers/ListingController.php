<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController {

    protected $database;
    protected $allowedFields = [
        'title',
        'description',
        'salary',
        'requirements',
        'benefits',
        'company',
        'address',
        'city',
        'state',
        'phone',
        'email',
    ];

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

    /**
     * Method called to store data into database
     * 
     * @return void
     */

     public function store(){

        $cleanData = array_intersect_key($_POST, array_flip($this->allowedFields));
        //-----------------------------------------------------------------------------HARD CODED USER ID, DELETE LATER
        $cleanData["user_id"] = 1;
        //-----------------------------------------------------------------------------
        $cleanData = array_map('sanitizeInput', $cleanData);
        inspectValueAndHold($cleanData);
     }
     

};