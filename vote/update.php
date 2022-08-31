<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
// instantiate product object
include_once '../objects/vote.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$vote = new Vote($db);
 
// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of product to be edited
$vote->id = $data->id;
 
// set product property values
$vote->user_id = $data->user_id;
$vote->service_id = $data->service_id;
$vote->vote = $data->vote;
 
// update the product
if($vote->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("status"=> true , "message" => "Product was updated."));
}
 
// if unable to update the product, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("status"=> false ,"message" => "Unable to update product."));
}
?>