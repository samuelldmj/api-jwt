<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: POST");
header("Content-type: application/json; charset=UTF-8");
// Including necessary files
include_once "../config/Database.php";
include_once "../classes/Users.php";

// Establishing database connection
$db = new Database();
$connection = $db->connect();

// Initializing the Users object
$user_obj = new Users($connection);

// Handling POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
}