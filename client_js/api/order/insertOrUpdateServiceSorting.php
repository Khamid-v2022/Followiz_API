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
include_once '../objects/sorting.php';
 
$database = new Database();
$db = $database->getConnection();
 
$sort = new Sort($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));
$data = [];


$serviceOrder = $_POST["serviceOrder"];

if( !empty($serviceOrder) ){
    
    foreach($serviceOrder as $u){
        // set product property values
        $sort->service_id = $u["service_id"];
        // read the details of user to be edited
        $sort->readOne();
       
        if($sort->sort_order>0){
           
            $sort->sort_order = $u["sort_order"];
            $sort->update();

        }else{
            $sort->service_id = $u["service_id"];
            $sort->sort_order = $u["sort_order"];
            $sort->create();
        }
    }
    
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("status"=> true , "message" => "service order successfully synchronized."));
}     

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Please provide all service data" , "data" => $serviceOrder));
}
?>