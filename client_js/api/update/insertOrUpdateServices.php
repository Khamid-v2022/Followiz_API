<?php
error_reporting(E_ALL);
// required headers
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");*/
date_default_timezone_set('America/New_York');
$datenow = date("Y-m-d h:i:s");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/update.php';
 
$database = new Database();
$db = $database->getConnection();
 
$update = new Update($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));
$data = [];


$updates = $_POST["updates"];

if( !empty($updates) ){
    
    foreach($updates as $u){
        // set product property values
        $update->service_id = $u["service_id"];
        // read the details of user to be edited
        $update->readOne();
        //$update->updated_at = $datenow;
        if($update->update_message){
           
            $old_price = $update->price;
            
            if($u["price"] < $update->price){

                $newPrice = $update->price - $u["price"];
                //$update_message = 'Price Reduced to '.$newPrice;
                $update_message = 'Price Reduced to '.$u["price"]." $ "; ;
                
                $update->service_id = $u["service_id"];
                $update->update_message = $update_message;
                $update->service_details = $u["service_details"];
                $update->old_price =  $old_price ;
                $update->price = $u["price"];
                $update->status = $u["status"];
                $update->update();
                
            } else if($u["price"] > $update->price){
                $newPrice =  $u["price"] - $update->price;
                //$update_message = 'Price Increased to '.$newPrice; 
                $update_message = 'Price Increased to '.$u["price"]." $ "; 
                
                $update->service_id = $u["service_id"];
                $update->update_message = $update_message;
                $update->service_details = $u["service_details"];
                $update->old_price =  $old_price ;
                $update->price = $u["price"];
                $update->status = $u["status"];
                $update->update();
                
            } else if($u["status"] != $update->status){
                if($u["status"]){
                   $update_message = 'Service Enabled'; 
                }else {
                    $update_message = 'Service Disabled';
                }
                
                //$update->service_id = $u["service_id"];
                $update->update_message = $update_message;
                $update->service_details = $u["service_details"];
                $update->price = $u["price"];
                $update->old_price =  $u["price"];
                $update->status = $u["status"];
                $update->update();  
                
            }

        }else{
            if($u["status"]){
               $update_message = 'Service Enabled'; 
            }else {
                $update_message = 'Service Disabled';
            }
            $update->service_id = $u["service_id"];
            $update->update_message = $update_message;
            $update->service_details = $u["service_details"];
            $update->price = $u["price"];
            $update->old_price =  $u["price"];
            $update->status = $u["status"];
            $update->create();
        }
    }
    
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("status"=> true , "message" => "service successfully synchronized.","date"=>$datenow));
}     

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Please provide all service data"));
}
?>