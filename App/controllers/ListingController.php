<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Verification;

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
        $listings = $this->database->query("SELECT * FROM listings ORDER BY created_at DESC")->fetchAll();
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

        $cleanData["user_id"] = Session::getValue('user')['id'];

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

        //---------------------------------------------------------------------------------------------FETCH LISTING

        $listing = $this->database->query('SELECT * FROM listings WHERE id= :id', $dbParams)->fetch();

        //---------------------------------------------------------------------------------------------HANDLE NONEXISTENT LISTING

        if(!$listing){
            ErrorController::notFound("Listing not found");
            return;
        }

        //---------------------------------------------------------------------------------------------VERIFY USER ID


        if(!Verification::isOwner($listing->user_id)) {
            $message = 'You are not authorised to delete this listing';
            $key = Session::$errorKey;
            Session::setMessage($message, $key);
            redirect("/listing?id=" . $id);
        }

        //---------------------------------------------------------------------------------------------DELETE LISTING


        $this->database->query("DELETE FROM listings WHERE id = :id", $dbParams);

        $message = "Listing deleted succesfully.";
        $key = Session::$successKey;
        Session::setMessage($message, $key);

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

        //----------------------------------------------------------------------VERIFY USER ID

        if(!Verification::isOwner($job->user_id)) {
            $message = 'You are not authorised to edit this listing';
            $key = Session::$errorKey;
            Session::setMessage($message, $key);
            redirect("/listing?id=" . $id);
        }

        //----------------------------------------------------------------------LOAD THE EDIT FORM

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

        if(!Verification::isOwner($listing->user_id)) {
            $message = 'You are not authorised to edit this listing';
            $key = Session::$errorKey;
            Session::setMessage($message, $key);
            redirect("/listing?id=" . $id);
        }

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

            $message = 'Listing Updated Succesfully';
            $key = Session::$successKey;
            Session::setMessage($message, $key);
            
            redirect("/listing?id=" . $id);
        }

     }

     /**
      * Method to search listings by given keywords and location
      * @return void
      */

      public function search(){
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR title LIKE :keywords OR company LIKE :keywords OR salary LIKE :keywords)
        AND (address LIKE :location OR city LIKE :location OR state LIKE :location)";
        
        $dbParams = [
            "keywords" => "%{$keywords}%",
            "location"=> "%{$location}%",
        ];
        $listings = $this->database->query($query, $dbParams)->fetchAll();

        loadView("listings/listings", [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location,
        ]);

      }
     

};