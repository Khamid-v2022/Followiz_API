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

$username = $_POST["username"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$email = $_POST["email"];
$followiz_id = $_POST["followiz_id"];
$other_detail = $_POST["other_detail"];

if( !empty($username) ){
    
    // set product property values
    $user->followiz_id = $followiz_id;
    // read the details of user to be edited
    $user->readOne();
    
    $user->username = $username;
    $user->first_name = $first_name;
    $user->last_name = $last_name;
    $user->email = $email;
    $user->followiz_id = $followiz_id;
    $user->other_detail = $other_detail;

    if($user->email != null){
        $user->update();
    }else{
        $user->create();
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