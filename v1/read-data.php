<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key; // Import the Key class

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: POST");
header("Content-type: application/json; charset=UTF-8");

// Including necessary files
include_once "../config/Database.php";
include_once "../classes/Users.php";

// Including vendor file
require '../vendor/autoload.php';

// Establishing database connection
$db = new Database();
$connection = $db->connect();

// Initializing the Users object
$user_obj = new Users($connection);

// Handling POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //passing token in the body as json input
    // $data = json_decode(file_get_contents('php://input'));

    // Initialize $data as an object to avoid the null error
    $data = new stdClass();

    //passing jwt to headers
    $all_headers = getallheaders();

    //using tenary operator
    // $data->jwt = isset($all_headers['Authorization']) ? $all_headers['Authorization'] : null;
    
    //using null coalescing 
    $data->jwt = $all_headers['Authorization'] ?? null;
   // Check if JWT is present
   if(!empty($data->jwt)) {
    $secret_key = 'owt123';

    try {
        // Use Key object to specify algorithm and decode the JWT
        $decoded_jwt = JWT::decode($data->jwt, new Key($secret_key, 'HS512'));
        
        http_response_code(200);

        // Using decoded JWT token to get user id.
        $user_id = $decoded_jwt->data->id;
        echo json_encode([
            'status' => 1,
            'jwt' => $decoded_jwt,
            'message'=> 'We got JWT Token',
            'user_id' => $user_id,
        ]);
    } catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode([
            'status' => 0,
            'message' => 'Invalid or expired JWT token.',
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
}
