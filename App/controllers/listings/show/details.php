<?php

use Framework\Database;

$config = require basePath("config/database.php");
$database = new Database($config);

$id = $_GET['id'] ?? '';

$params = [
    'id' => $id,
];

$job = $database->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

inspectValues($job);

loadView("listings/show/details", [
    "job"=> $job,
]);