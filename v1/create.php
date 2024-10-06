<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: POST");
header("Content-type: application/json; charset=UTF-8");

//INCLUDING FILES
include_once "../config/Database.php";
include_once "../classes/Users.php";

// Create a database object and establish connection
$db = new Database();
$connection = $db->connect(); // Get the PDO connection

// Pass the PDO connection to the Users object
$user_obj = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    Retrieves the raw data sent in the request body
    (typically used in POST requests when the content is in JSON format).
    */
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->user_name) && !empty($data->user_email) && !empty($data->user_password)) {

        $user_obj->user_name = $data->user_name;
        $user_obj->user_email = $data->user_email;
        $user_obj->user_password = password_hash($data->user_password, PASSWORD_DEFAULT);

        if ($user_obj->create_user()) {
            http_response_code(200);
            echo json_encode([
                'status' => 1,
                'message' => "User has been created"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 0,
                'message' => 'Failed to Save user data'
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
