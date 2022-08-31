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
include_once '../config/update.php';
 
$database = new Database();
$db = $database->getConnection();

$dstatus = $_POST['dstatus'];
$dsid = $_POST['dsid'];

if( !empty($dsid) ){
   
    
             if($dstatus == 'enabled'){
                 $rep_state = 0;
                 $rep_statn = 'disabled';
                 $update_message = 'Service Enabled';
             }
    
            if($dstatus == 'disabled'){
                 $rep_state = 1;
                 $rep_statn = 'enabled';
                $update_message = 'Service Disabled';
             }

       $res = $db->update('updates',array('status'=>1),'service_id = '.$dsid.'');
    
       //echo $res; 
//update status



     
                
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("service"=>  $dsid,"status" => $rep_statn));
}

//update status