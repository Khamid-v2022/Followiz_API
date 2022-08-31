<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$vote = new Vote($db);
// die('test31231255');
 
$uid = $_GET['user_id'];
$tid = $_GET['ticket_id'];

$drm11 = $vote->fetch_all("select * from agent_ratings where user_id = ".$uid." and ticket_id = ".$tid."" );

    $response['status']  = True;
 	$response['data'] = $drm11;
    echo json_encode($response);
?>