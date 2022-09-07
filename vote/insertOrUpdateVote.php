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
$data["check_service_id"] = $_POST["type"];
//print_r($data);die('test');
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
            // get AVG rate
            $stmt = $vote->readSelectedService();

            $num = $stmt->rowCount();

            // set response code - 200 ok
            http_response_code(200);
            if($num>0){
                $response['status']  = true;
                $response['message'] = "AVG found.";
                $response["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
                // show products data in json format
                echo json_encode($response);
            }else{
                // tell the user
                echo json_encode(array("data"=>$data,"status"=> false , "message" => "Product was updated."));
            }
           
        }
         
        // if unable to update the product, tell the user
        else{
         
            // set response code - 503 service unavailable
            http_response_code(503);
         
            // tell the user
            echo json_encode(array( "status"=> false ,"message" => "Unable to update product."));
        }
    }else{
        $vote->user_id = $data["user_id"];
        $vote->service_id = $data["service_id"];
        $vote->vote = $data["vote"];
        // create the product
        if($vote->create()){
            // get AVG rate
            $stmt = $vote->readSelectedService();

            $num = $stmt->rowCount();

            // set response code - 201 created
            http_response_code(201);
            if($num>0){
                $response['status']  = true;
                $response['message'] = "AVG found.";
                $response["data"] = $stmt->fetch(PDO::FETCH_ASSOC);
                // show products data in json format
                echo json_encode($response);
            }else{
                // tell the user
                echo json_encode(array("data"=>$data,"status"=> false , "message" => "Vote was created."));
            }

        }
     
        // if unable to create the product, tell the user
        else{
     
            // set response code - 503 service unavailable
            http_response_code(503);
     
            // tell the user
            echo json_encode(array("status" => false, "message" => "Unable to create product."));
        }

    }
} else if($data["check_service_id"] == 'check_service_id' && !empty($data["service_id"]) ) {
 
    // set product property values
        //echo 'enter'; die;
        $vote->service_id = $data["service_id"];
        $vote->vote = '';
       
        // reset vote of the disable product
        if($vote->reset()){
            // set response code - 200 ok
            http_response_code(200);
            echo json_encode(array("status" => true, "message" => "Vote was reset."));
        }        
        else{     
            // set response code - 503 service unavailable
            http_response_code(503);     
            // tell the user
            echo json_encode(array("status" => false, "message" => "Unable to reset vote of disable product."));
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
