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
    $data = json_decode(file_get_contents('php://input'));

    // Validate input
    if (!empty($data->user_email) && !empty($data->user_password)) {
        $user_obj->user_email = $data->user_email;
        $user_data = $user_obj->check_login();

        // Check if user data exists
        if (!empty($user_data)) {
            // Extract user data
            $stored_password = $user_data['user_password'];

            // Verify password
            if (password_verify($data->user_password, $stored_password)) {
                http_response_code(200);
                echo json_encode([
                    'status' => 1,
                    'message' => 'User has been logged in'
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid credentials'
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid credentials'
            ]);
        }
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 0,
            'message' => 'All data required'
        ]);
    }
} else {
    http_response_code(503);
    echo json_encode([
        'status' => 0,
        'message' => 'Access Denied!'
    ]);
}
