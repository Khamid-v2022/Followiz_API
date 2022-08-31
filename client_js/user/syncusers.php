<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
//https://followizdev.com/admin/api/users/list

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,'https://followizdev.com/admin/api/users/list');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
  $response = curl_exec($ch);
  $result = json_decode($response);
  curl_close($ch); // Close the connection
 echo "<pre>";
 print_r($res);
  
