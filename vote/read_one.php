<?php
if(empty($_SERVER['HTTP_ORIGIN'])) {
 /* special ajax here */
    die("Invalid access");
}
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/vote.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object

$data = [];

$data["user_id"] = $_POST["user_id"];
$data["service_id"] = $_POST["service_id"];

$vote = new Vote($db);
 
if( !empty($data["user_id"]) && !empty($data["service_id"])){
    
    $vote->user_id = $data["user_id"];
    $vote->service_id = $data["service_id"];

    $stmt = $vote->read_with_me();
    $num = $stmt->rowCount();
    
    if($num > 0){
        $response=array();
        $response["data"]=array();
       
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            extract($row);

            if($service_id == $data["service_id"]){            
                $response["data"] = array(
                    "service_id" => $service_id,
                    "vote" => $voteavg,
                    "my_vote" => $my_rate
                );
                break;
            }
        }
     
        // set response code - 200 OK
        http_response_code(200);
        $response['status']  = true;
        $response['message'] = "Vote found.";
        // show products data in json format
        echo json_encode($response);
    }else{
     
        // set response code - 404 Not found
        http_response_code(404);
     
        // tell the user no products found
        $response['status']  = false;
        $response['data'] = [];
        $response['message'] = "No Vote found.";
        echo json_encode($response);
    }

}else{    
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    $response['status']  = false;
    $response['data'] = [];
    $response['message'] = "No Vote found.";
    echo json_encode($response);
  
}
