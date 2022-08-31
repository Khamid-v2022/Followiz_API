<?php
error_reporting(E_ALL); 

date_default_timezone_set('America/New_York');
$datenow = date("Y-m-d h:i:s");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// get database connection
include_once 'api/config/database.php';
 
// instantiate product object
include_once 'api/objects/update.php';

function getCurlData($url){
	$curl = curl_init(); 
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);	 
	$data = curl_exec($curl);	 
	curl_close($curl);
	return $data;
}
 
$database = new Database();
$db = $database->getConnection();
 
$update = new Update($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));
$data = [];


$url = "https://followiz.com/api/v2?action=services&key=6cc2cfdf87c2430af5e7bd00f8c2290e";

$apiData = getCurlData($url);
//file_put_contents("api-datatest.json",$apiData);
$updates = json_decode($apiData,true);

if( !empty($updates) ){	
	
    foreach($updates as $key => $u){
        // set product property values
    	
	    $data[] = $u["service"];
		$updateService = new Update($db);
        if(is_int($u["service"]) == false){
            continue;
        }
        $updateService->service_id = $u["service"];
        // read the details of user to be edited
        $updateService->readOne();
          //print_r($u);
		  //print_r($update->update_message);
        if($updateService->update_message){
           
            $old_price = $update->price;
            
            if($u["rate"] < $update->price){

                $newPrice = $update->price - $u["rate"];
                //$update_message = 'Price Reduced to '.$newPrice;
                if($update->status == 0){
                    $update_message = 'Service Enabled';
                }else{
                    $update_message = 'Price Reduced';
                }                
                
                $update->service_id = $u["service"];
                $update->update_message = $update_message;
                $update->service_details = mysqli_real_escape_string($u["name"]);
                $update->old_price =  $old_price ;
                $update->price = $u["rate"];
                $update->status = 1;
                $update->domain = 'dev';
                $update->update();
                
            } else if($u["rate"] > $update->price){
                $newPrice =  $u["rate"] - $update->price;   
                if($update->status == 0){
                    $update_message = 'Service Enabled';
                }else{
                    $update_message = 'Price Increased';
                } 
                
                $update->service_id = $u["service"];
                $update->update_message = $update_message;
                $update->service_details = $u["name"];
                $update->old_price =  $old_price ;
                $update->price = mysqli_real_escape_string($u["name"]);
                $update->status = 1;
                $update->domain = 'dev';
                $update->update();
                
            } else if($u["rate"] == $update->price && $update->status == 0){               
                $update_message = 'Service Enabled';
				
                //$update->service_id = $u["service"];
                $update->update_message = $update_message;
                $update->service_details = mysqli_real_escape_string($u["name"]);
                $update->price = $u["rate"];
                $update->old_price =  $u["rate"];
                $update->status = 1;
                $update->domain = 'dev';
                $update->update();  
            }

        }else{
			//echo "create In";
            $update_message = 'Service Enabled'; 
            $update->service_id = $u["service"];
            $update->update_message = $update_message;
            $update->service_details = mysqli_real_escape_string($u["name"]);
            $update->price = $u["rate"];
            $update->old_price =  $u["rate"];
            $update->status = 1;
            $update->domain = 'dev';
            $update->lasttime = time();
            $update->create();
        }               
    }
    
    if (!empty($data)) {
    	$dataStr = implode(",", $data); 
    	$update_message = 'Service Disabled';
        $update->update_message = $update_message;        
        $update->status = 0;
        $update->updateAll($dataStr);  /// for Service Disabled
    }
    // set response code - 200 ok
    http_response_code(200);
   

    $myfile = fopen("/var/www/html/hitTime.txt", "a");
    $txt = date("Y-m-d h:i:sa")."\n";    
    fwrite($myfile, $txt);
    fclose($myfile);
 
    // tell the user
    echo json_encode(array("status"=> true , "message" => "service successfully synchronized.","date"=>$datenow));
}     

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Please provide all service data"));
}
?>