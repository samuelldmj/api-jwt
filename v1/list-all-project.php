<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: GET");

// Including necessary files
include_once "../config/Database.php";
include_once "../classes/Users.php";

// Establishing database connection
$db = new Database();
$connection = $db->connect();

// Initializing the Users object
$user_obj = new Users($connection);

// Handling POST request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $projects = $user_obj->display_all_projects();

    if(count($projects) > 0) {
        http_response_code(200);
        echo json_encode([
            'status' => 1,
            'message' => $projects
        ]);
    }else {
    http_response_code(404);
    echo json_encode([
        'status'=> 0,
        'message'=> 'No Project records found'
    ]) ;
}

} 
