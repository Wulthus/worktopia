<?php

use Framework\Database;

$config = require basePath("config/database.php");
$database = new Database($config);

$listings = $database->query("SELECT * FROM listings")->fetchAll();

loadView("listings/listings", ["listings"=> $listings]);