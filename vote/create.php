<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/vote.php';
 
$database = new Database();
$db = $database->getConnection();
 
$vote = new Vote($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->user_id) &&
    !empty($data->service_id) &&
    !empty($data->vote) 
){
 
    // set product property values
    $vote->user_id = $data->user_id;
    $vote->service_id = $data->service_id;
    $vote->vote = $data->vote;
    // create the product
    if($vote->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => true, "message" => "Vote was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("status" => false, "message" => "Unable to create product."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Unable to create product. Data is incomplete."));
}
?>