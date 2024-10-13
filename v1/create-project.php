<?php

//Including vendor file
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key; // Import the Key class

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

    // Initialize $data as an object to avoid the null error
    $data_jwt = new stdClass();

     //passing jwt to headers
     $all_headers = getallheaders();

    /*
    Retrieves the raw data sent in the request body
    (typically used in POST requests when the content is in JSON format).
    */
    $data_input = json_decode(file_get_contents("php://input"));

                if(!empty($data_input->project_name) && !empty($data_input->project_status) && !empty($data_input->project_description)){
                    try {
                        $data_jwt = $all_headers['Authorization'] ?? null;
                        $secret_key = 'owt123';

                        // Use Key object to specify algorithm and decode the JWT
                        $decoded_jwt = JWT::decode($data_jwt, new Key($secret_key, 'HS512'));

                        $user_obj->project_name = $data_input->project_name;
                        $user_obj->project_status = $data_input->project_status;
                        $user_obj->project_description = $data_input->project_description;
                        $user_obj->user_id = $decoded_jwt->data->id;

                        if($user_obj->create_project()){
                            http_response_code(200);
                            echo json_encode([
                                'status' => 1,
                                'message'=> 'Project has been created'
                            ]);
                        } else {
                                http_response_code(500); // server error
                                echo json_encode([
                                    'status' => 0,
                                    'message' => 'Failed to create project.',
                                ]);
                            }
                        
                    }
                    catch(Exception $e){

                        http_response_code(500);
                        echo json_encode([
                            'status' => 0,
                            'message'=> $e->getMessage(),
                        ]);
                }
            }else {
                http_response_code(404); // Bad request
                echo json_encode([
                    'status' => 0,
                    'message' => 'All that data required',
                ]);
            }
        } else {
            http_response_code(400); // Bad request
            echo json_encode([
                'status' => 0,
                'message' => 'JWT token not provided.',
            ]);
        }