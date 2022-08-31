<?php
// required headers

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


$user_id = $_GET["user_id"];



if( $user_id != '' ){
    
        // set product property values
        $user->followiz_id = $user_id;
        // read the details of user to be edited
        $user->readOne();
        http_response_code(200);
        if($user->status == 1){
           echo 1;
        }else{
           echo 0;
        }
    die;
}     

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Please provide all data.1"));
}
?>