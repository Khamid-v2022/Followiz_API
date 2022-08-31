<?php
if(empty($_SERVER['HTTP_ORIGIN'])) {
 /* special ajax here */
    die("Invalid access");
}
// required headers
// reference /home3/startde1/public_html/client_js/agent/read.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/update.php';
 
$database = new Database();
$db = $database->getConnection();
 
$update = new Update($db);
// query update
$stmt = $update->read();
$stmttotal = $update->total();

// products array
$response=array();
$response["data"]=array();
 
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    extract($row);

    $agent_item=array(
        "id" => $id,
        "created_at" => $created_at,
        "linked_service" => array('service'=>  $service_id, 'name'=> $service_details),
        "service" => $service_id,
        "sname" => $service_details,
        "price" => $price,  
        "old_price" => $old_price,  
        "update" => $update_message,  
        "updated_at" => $updated_at , 
        "status" => $status , 
    );

    array_push($response["data"], $agent_item);
}

// set response code - 200 OK
http_response_code(200);
 $response['current_page']  = 1;
 $response['from']  = 1;
 $response['last_page']  = 11;
 $response['next_page_url']  = 'https://www.startdesigns.com/client_js/updates/';
 $response['path']  = 'https://www.startdesigns.com/client_js/updates/';
 $response['per_page']  = 1;
 $response['prev_page_url']  = 'https://www.startdesigns.com/client_js/updates/';
 $response['to']  = 1;
 $response['total']  = $stmttotal->rowCount();
 $response['totalPage']  = ceil($stmttotal->rowCount()/100);
// show products data in json format
echo json_encode($response);




die;