<?php
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
$vote = new Vote($db);
// die('test31231255');
 
$uid = $_GET['user_id'];
$tid = $_GET['ticket_id'];

// set ID property of record to read
$vote->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : ''; 
// query products
$stmt = $vote->find_a_record($uid,$tid);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $response=array();
    $response["data"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $vote_item=array(
            "service_id" => $service_id,
            "vote" => $voteavg
        );
 
        array_push($response["data"], $vote_item);
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