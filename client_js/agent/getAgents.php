<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/getAgents.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$agent = new Agent($db);

//print_r($agent);die;
$stmt = $agent->readAll();

// products array
$response=array();
$response["data"]=array();


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 
    extract($row);

    $response["data"][$id] = $name;

}

// set response code - 200 OK
http_response_code(200);
 $response['status']  = true;
// show products data in json format
echo json_encode($response);
