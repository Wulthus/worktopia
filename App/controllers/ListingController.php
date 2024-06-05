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
        'tags',
        'company',
        'address',
        'city',
        'state',
        'phone',
        'email',
    ];

    protected $requiredFields = [
        'title',
        'description',
        'email',
        'city',
        'state',
        'salary',
    ];

    public function __construct(){
        $config = require basePath("config/database.php");
        $this-> database = new Database($config);
    }

    public function index(){
        $listings = $this->database->query("SELECT * FROM listings")->fetchAll();
        loadView("listings/listings", ["listings"=> $listings]); 
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

        $errors = [];

        $cleanData = array_intersect_key($_POST, array_flip($this->allowedFields));
        //-----------------------------------------------------------------------------HARD CODED USER ID, DELETE LATER
        $cleanData["user_id"] = 1;
        //-----------------------------------------------------------------------------
        $cleanData = array_map('sanitizeInput', $cleanData);
        
        foreach ($this->requiredFields as $field) {
            if (empty($cleanData[$field]) && !Validation::validateString($cleanData[$field])) {
                $errors[] = '"' . ucfirst($field) . '"' . ' field is required';

            }
        }

        if (!empty($errors)){
            loadView('/listings/create/create', [
                'errors'=> $errors,
                'fieldData' => $cleanData,
            ]);
        } else {
            
            $fields = [];
            $values = [];

            foreach ($cleanData as $field => $value) {
                $fields[] = $field;
                if ($value === ''){
                    $cleanData[$field] = null;
                }
                $values[] = ":" . $field;


            };

            $fields = implode(", ", $fields);
            $values = implode(", ", $values);

            $query = "INSERT INTO listings({$fields}) VALUES ({$values})";

            $this->database->query($query, $cleanData);

            redirect("/listings");
        };

     }

     /**
      *  Function to delete listing from the database
      *
      * @return void
      *
      */
      public function delete(){

        $id = $_GET['id'] ?? '';

        $dbParams = [
            'id' => $id
        ];

        $listing = $this->database->query('SELECT * FROM listings WHERE id= :id', $dbParams)->fetch();

        if(!$listing){
            ErrorController::notFound("Listing not found");
            return;
        }

        $this->database->query("DELETE FROM listings WHERE id = :id", $dbParams);

        $_SESSION['success_message'] = "Listing deleted succesfully.";

        redirect("/listings");

      }
      
      /**
       * Function to edit listing
       * 
       * @return void
       */

      public function edit(){
        $id = $_GET['id'] ?? '';

        $params = [
            'id' => $id,
        ];

        $job = $this->database->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if(!$job){
            ErrorController::notFound("Listing not found");
            return;
        }

        inspectValueAndHold($job);

        loadView("listings/show/details", [
            "job"=> $job,
        ]);

    }
     

};