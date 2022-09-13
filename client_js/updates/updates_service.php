<?php
if(empty($_SERVER['HTTP_ORIGIN'])) {
 /* special ajax here */
    die("Invalid access");
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=UTF-8");
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/update_new.php';
 
$database = new Database();
$db = $database->getConnection();
 
$update = new Update($db);
// query update
$stmt = $update->read();

// products array
$response=array();
$response["data"]=array();
 
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   $item = $row;
   $item['service'] = cb($item['service']);
   array_push($response["data"], $row);
}

// set response code - 200 OK
http_response_code(200);

echo json_encode($response);


function cb($content){

   if(!mb_check_encoding($content, 'UTF-8')
      OR !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {

      $content = mb_convert_encoding($content, 'UTF-8');
   }
   return $content;
}


die;