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
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));
$data = [];


$users = $_POST["users"];



if( !empty($users) ){
    
    foreach($users as $u){
        // set product property values
        $user->followiz_id = $u["followiz_id"];
        // read the details of user to be edited
        $user->readOne();
        
        if($user->email!=null){
            $user->username = $u["username"];
            $user->first_name = $u["first_name"];
            $user->last_name = $u["last_name"];
            $user->email = $u["email"];
            $user->followiz_id = $u["followiz_id"];
            $user->update();
        }else{
            $user->username = $u["username"];
            $user->first_name = $u["first_name"];
            $user->last_name = $u["last_name"];
            $user->email = $u["email"];
            $user->followiz_id = $u["followiz_id"];
            $user->create();
        }
    }
    
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("status"=> true , "message" => "user successfully synchronized."));
}     

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Please provide all data.1"));
}
?>