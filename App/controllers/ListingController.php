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

    /**
    * Helper function that extracts listing ID from the URL
    *
    * @return array 
    */

    protected function getID(){
        if (isset($_GET['id'])){
            $id = $_GET['id'];
                return $id;
        } else {
            throw new Exception("No ID was passed into URL");
        }
    }

    /**
     * This function prepares database query and fetches listing based on given id
     * 
     * @param array $dbParams
     * @return array
     * 
     */

    protected function getListing($dbParams){
        $listing = $this->database->query("SELECT * FROM listings WHERE id = :id", $dbParams)->fetch();

        if(!$listing){
            ErrorController::notFound("Listing not found");
            return;
        }

        return $listing;
    }

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
        $id = $this->getID();

        $dbParams = [
            'id' => $id,
        ];

        $job = $this->getListing($dbParams);

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
      *  Delete listing from the database
      *
      * @return void
      *
      */
      public function delete(){

        $id = $this->getID();

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
       * Get listing from the database and open edit page
       * 
       * @return void
       */

      public function edit(){
        $id = $this->getID();

        $dbParams = [
            'id' => $id,
        ];

        $job = $this->database->query("SELECT * FROM listings WHERE id = :id", $dbParams)->fetch();

        if(!$job){
            ErrorController::notFound("Listing not found");
            return;
        }

        loadView("listings/edit/edit", [
            "job"=> $job,
        ]);

    }

    /**
     * Update edited liting in the database
     * 
     * @return void
     */

    public function update(){
        $id = $this->getID();

        $dbParams = [
            'id' => $id,
        ];

        $listing = $this->getListing($dbParams);

        $updatedValues = array_intersect_key($_POST, array_flip($this->allowedFields));


        $cleanData = array_map('sanitizeInput', $updatedValues);
        $errors = [];

        foreach($this->requiredFields as $field){
            if (empty($cleanData[$field]) && !Validation::validateString($cleanData[$field])) {
                $errors[] = '"' . ucfirst($field) . '"' . ' field is required';

            }
        };

        if (!empty($errors)){
            loadView('/listings/edit/edit', [
                'errors'=> $errors,
                'job' => $listing,
            ]);
            exit;
        } else {
            $newValues = [];
            foreach (array_keys($updatedValues) as $field) {
                $newValues[] = "{$field} = :{$field}";
            };

            $newValues = implode(", ", $newValues);

            $query = "UPDATE listings SET $newValues WHERE id = :id";
            $updatedValues["id"] = $dbParams['id'];
            $this->database->query($query, $updatedValues);

            $_SESSION["success_message"] = "Listing updated successfully";

            redirect("/listing?id=". $id);
        }

     }
     

};