<?php

$config = require basePath("config/database.php");
$database = new Database($config);

$id = $_GET['id'] ?? '';

$params = [
    'id' => $id,
];

$listing = $database->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

inspectValues($listing);

loadView("listings/show/details");