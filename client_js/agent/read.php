<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/agent.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$agent = new Agent($db);
// die('test31231255');
 

// set ID property of record to read
$agent->user_id = isset($_POST['user_id']) ? $_POST['user_id'] : ''; 
$agent->ticket_id = isset($_POST['ticket_id']) ? $_POST['ticket_id'] : '';
// query products
$stmt = $agent->read();

 
// products array
$response=array();
$response["data"]=array();
 
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // extract row
    // this will make $row['name'] to
    // just $name only
    extract($row);

    $agent_item=array(
        "user_id" => $user_id,
        "msg_id" => $msg_id,
        "ticket_id" => $ticket_id,
        "rating" => $rating,
        "agent_id" => $msg_id  
    );

    array_push($response["data"], $agent_item);
}

// set response code - 200 OK
http_response_code(200);
 $response['status']  = true;
 $response['message'] = "Vote found.";
// show products data in json format
echo json_encode($response);
