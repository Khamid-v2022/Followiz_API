<?php
error_reporting(E_ALL); 

date_default_timezone_set('America/New_York');
$datenow = date("Y-m-d h:i:s");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// get database connection
include_once 'client_js/api/config/database.php';
 
// instantiate product object
include_once 'client_js/api/objects/update.php';

$database = new Database();
$db = $database->getConnection();
 
$update = new Update($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));
$data = [];

$dstatus = $_POST['dstatus'];
$dsid = $_POST['dsid'];
$dprice = $_POST['dprice'];

if($dstatus == 'enabled'){
    $ds = 0;
    $dstatus1 = 'disabled';
    $update_message = 'Service Disabled';
    $ds1 = 1;
                $update->service_id = $dsid;
                $update->update_message = $update_message;
                //$update->service_details = mysqli_real_escape_string($u["name"]);
                //$update->old_price =  $old_price ;
                //$update->price = $u["rate"];
                $update->status = $ds1;
                $update->domain = 'main';
                $update->update();
    http_response_code(200);   
    echo json_encode(array("status"=> $dstatus1 ,"service"=>$dsid, "message" => "service disabled successfully.","date"=>$datenow));
}

if($dprice != ''){
    $ds = 0;
    $dstatus1 = 'update';
    $update_message = 'Service Price Updated';
    
    $update->service_id = $dsid;
    $update->update_message = $update_message;
    //$update->service_details = mysqli_real_escape_string($u["name"]);
    //$update->old_price =  $old_price ;
    $update->price = $dprice;
    //$update->status = $ds1;
    $update->domain = 'main';
    $update->update();
    
    http_response_code(200);   
    echo json_encode(array("status"=> $dstatus1 ,"service"=>$dsid, "message" => "service updated successfully.","uprice"=>$dprice));
}
    
   

?>