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
include_once '../objects/sorting.php';
 
$database = new Database();
$db = $database->getConnection();
 
$sort = new Sort($db);
// query update
$stmt = $sort->read();

// products array
$response=array();
$response["data"]=array();
 
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    extract($row);

    $agent_item=array(
        "service_id" => $service_id,
        "sort_order" => $sort_order
    );

    array_push($response["data"], $agent_item);
}

// set response code - 200 OK
http_response_code(200);

// show products data in json format
echo json_encode($response);
die;