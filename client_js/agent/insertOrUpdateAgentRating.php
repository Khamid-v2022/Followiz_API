<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/agent.php';
 
$database = new Database();
$db = $database->getConnection();
 
$agent = new Agent($db);

// get posted data

$data = [];

$data["user_id"] = $_POST["user_id"];
$data["msg_id"] = $_POST["msg_id"];
$data["agent_id"] = $_POST["agent_id"];
$data["ticket_id"] = $_POST["ticket_id"];
$data["rating"] = $_POST["rating"];
$data["response_time"] = $_POST["response_time"] == "NaN" ? 0 : $_POST["response_time"];
//print_r($data);die;
// make sure data is not empty
if(
    !empty($data["user_id"]) &&
    !empty($data["msg_id"]) &&
    !empty($data["agent_id"]) &&
    !empty($data["ticket_id"]) &&
    !empty($data["rating"]) &&
    (!empty($data["response_time"]) || $data["response_time"]==0)
){
 
    // set product property values
    $agent->user_id = $data["user_id"];
    $agent->msg_id = $data["msg_id"];
    $agent->ticket_id = $data["ticket_id"];
    

    // read the details of product to be edited
    $agent->readOne();
  
    if($agent->id){
        $agent->rating = $data["rating"];
        $agent->response_time = $data["response_time"];
        if($agent->update()){
 
            // set response code - 200 ok
            http_response_code(200);
         
            // tell the user
            echo json_encode(array("status"=> true , "message" => "Rating update successfully."));
        }
         
        // if unable to update the product, tell the user
        else{
         
            // set response code - 503 service unavailable
            http_response_code(503);
         
            // tell the user
            echo json_encode(array("status"=> false ,"message" => "Unable to update rating."));
        }



        
    }else{
        $agent->user_id = $data["user_id"];
        $agent->agent_id = $data["agent_id"];
        $agent->ticket_id = $data["ticket_id"];
        $agent->msg_id = $data["msg_id"];
        $agent->response_time = $data["response_time"];
        $agent->rating = $data["rating"];
        // create the product
        if($agent->create()){
     
            // set response code - 201 created
            http_response_code(201);
     
            // tell the user
            echo json_encode(array("status" => true, "message" => "Rating update successfully."));
        }
     
        // if unable to create the product, tell the user
        else{
     
            // set response code - 503 service unavailable
            http_response_code(503);
     
            // tell the user
            echo json_encode(array("status" => false, "message" => "Unable to create rating."));
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