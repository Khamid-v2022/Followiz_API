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
include_once '../objects/vote1.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$vote = new Vote($db);
// die('test31231255');
 

// set ID property of record to read

$uid = $_POST['user_id'];
$tid = $_POST['ticket_id'];

//echo "select * from agent_ratings where user_id = ".$uid." and ticket_id = ".$tid."";
//$stmt = $vote->read_con($uid,$tid);
$stmt = $vote->read($uid,$tid);
$num = $stmt->rowCount();
 //echo json_encode($num);
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
        //echo '<pre>'; print_r($row); die;
        extract($row);

        $vote_item=array(
            "ticket_id" => $row['ticket_id'],
            "msg_id" => $row['msg_id'],
            "vote" => $row['rating'],
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
