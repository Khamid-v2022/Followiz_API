<?php
// if(empty($_SERVER['HTTP_ORIGIN'])) {
//  /* special ajax here */
//     die("Invalid access");
// }
// // required headers
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/bestseller.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// products array
$response=array();
$response["data"]=array();

$bestSeller = new Bestseller($db);

$stmt = $bestSeller->read_main_bestseller('main_categories');

$main_best = array();
$sub_best = array();
// retrieve our table contents
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   array_push($main_best, $row);
}

$stmt = $bestSeller->read_main_bestseller('sub_categories');

// retrieve our table contents
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   array_push($sub_best, $row);
}


$response["data"]['main'] = $main_best;
$response["data"]['sub'] = $sub_best;


// set response code - 200 OK
http_response_code(200);
$response['status']  = true;
$response['message'] = "";
// show products data in json format
echo json_encode($response);
