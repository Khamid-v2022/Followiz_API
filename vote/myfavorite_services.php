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

$vote = new Vote($db);
 
if( !empty($data["user_id"])){
    $vote->user_id = $data["user_id"];

    // query products
    $stmt = $vote->read_favorites();
    $num = $stmt->rowCount();
    // check if more than 0 record found
    if($num > 0){
        // products array
        $response=array();
        $response["data"]=array();
     
        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            extract($row);

            $vote_item=array(
                "service_id" => $service_id
            );
     
            array_push($response["data"], $vote_item);
        }
     
        // set response code - 200 OK
        http_response_code(200);
        $response['status']  = true;
        $response['message'] = "Services found.";
        // show products data in json format
        echo json_encode($response);
    }else{
     
        // set response code - 404 Not found
        http_response_code(404);
     
        // tell the user no products found
        $response['status']  = false;
        $response['data'] = [];
        $response['message'] = "Not found.";
        echo json_encode($response);
    }

}else{     
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    $response['status']  = false;
    $response['data'] = [];
    $response['message'] = "No found.";
    echo json_encode($response);
}
