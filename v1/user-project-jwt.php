<?php
//This script get user_project via jwt from the header.

//Including vendor file
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key; // Import the Key class

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

     // Initialize $data as an object to avoid the null error
     $data_jwt = new stdClass();

     //passing jwt to headers
     $all_headers = getallheaders();

      //using null coalescing 
    $data_jwt = $all_headers['Authorization'] ?? null;

    try {

       
        $secret_key = 'owt123';
        // Use Key object to specify algorithm and decode the JWT
        $decoded_jwt = JWT::decode($data_jwt, new Key($secret_key, 'HS512'));

        // Using decoded JWT token to get user_id.
        $user_id = $decoded_jwt->data->id;

        //re-assigning the decoded_jwt id to user_id
        $user_obj->user_id = $user_id;


        $projects = $user_obj->get_user_all_projects();

    if(count($projects) > 0) {
        $project_arr = [];
        foreach ($projects as $project) {
            // Push each row into the 'project' array
            $project_arr[] = $project;
        }
        http_response_code(200);
        echo json_encode([
            'status' => 1,
            'projects' => $project_arr, 
        ]);
    }else {
        http_response_code(404);
        echo json_encode([
            'status'=> 0,
            'message'=> 'No Project records found'
        ]);
    }
    }catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode([
            'status' => 0, 
            'error' => $e->getMessage(),
        ]);
    }

} else {
    http_response_code(400); // Bad request
    echo json_encode([
        'status' => 0,
        'message' => 'JWT token not provided.',
    ]);
} 