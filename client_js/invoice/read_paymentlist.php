<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

include ("../config/database.php");
include ('vendor/autoload.php'); 
include_once '../objects/paymentList.php';

$user_id = $_GET["user_id"];

$database = new Database();
$db = $database->getConnection();

$payment = new PaymentList($db);

$payment->user_id = $user_id;
$stmt = $payment->read();

$num = $stmt->rowCount();

// check if more than 0 record found
if($num > 0){
 
    // products array
    $response=array();
    $response["data"]=array();
 
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        //echo '<pre>'; print_r($row); die;
        extract($row);

        $item = array(
            "id" => $payment_id,
            "payment_date" => $payment_date,
            "path" => $invoice_path
        );
 
        array_push($response["data"], $item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
    $response['status']  = true;
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

?>