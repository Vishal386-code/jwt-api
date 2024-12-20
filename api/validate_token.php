<?php
header("Access-Control-Allow-Origin: http://localhost/jwt-crud/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require('../config/database.php');
include_once ('../config/core.php');
require_once ('../vendor/autoload.php');
require('objects/user.php');
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));
$jwt=isset($data->jwt) ? $data->jwt : "";


    if($jwt){
        try {
           
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            http_response_code(200);

            echo json_encode(array(
                "message" => "Access granted.",
                "data" => $decoded->data
            ));
        }
        catch (Exception $e){

            http_response_code(401);

            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }

    }else{
        http_response_code(401);
        echo json_encode(array("message" => "Access denied."));
    }
