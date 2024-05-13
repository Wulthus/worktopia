<?php

$config = require basePath("config/database.php");
$database = new Database($config);

$listings = $database->query("SELECT * FROM listings LIMIT 6")->fetchAll();

inspectValues($listings);

loadView("home");