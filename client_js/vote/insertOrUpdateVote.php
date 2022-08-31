<?php
// required headers
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/vote.php';
 
$database = new Database();
$db = $database->getConnection();
 
$vote = new Vote($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));
$data = [];

$data["user_id"] = $_POST["user_id"];
$data["service_id"] = $_POST["service_id"];
$data["vote"] = $_POST["vote"];
//print_r($data);die;
// make sure data is not empty
if(
    !empty($data["user_id"]) &&
    !empty($data["service_id"]) &&
    !empty($data["vote"]) 
){
 
    // set product property values
    $vote->user_id = $data["user_id"];
    $vote->service_id = $data["service_id"];
    

    // read the details of product to be edited
    $vote->readOne();
    
    if($vote->vote!=null){
        $vote->vote = $data["vote"];
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



        
    }else{
        $vote->user_id = $data["user_id"];
        $vote->service_id = $data["service_id"];
        $vote->vote = $data["vote"];
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
}     

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Please provide all data."));
}
?>