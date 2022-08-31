<?php
// required headers

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// get database connection
include_once '../config/database.php';
include ('vendor/autoload.php'); 
// instantiate product object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));
$data = [];


$payment_info = $_POST["payment_info"];

$invoice_obj = new Database();
$invoice_obj->getConnection();

if( !empty($payment_info) ){
    
    foreach($payment_info as $payment){
        
        
        //map info for invoice
        
        $Invoice  = $payment['payment_id'];
        $username = $payment['username'];
        $payment_date = $payment['payment_date'];
        $payment_method = $payment['payment_method'];
        $payment_amount = $payment['payment_amount'];
        
        $user->username = $payment['username'];
        $user->getUserByUserName();
        
        $email = isset($user->email)?$user->email:'';
        $first_name = isset($user->first_name)?$user->first_name:'';
        $last_name = isset($user->last_name)?$user->last_name:'';
       
        
        $date=date_create($payment_date);
        $invoice_date = date_format($date,"M d,Y");
        
        $mpdf = new \Mpdf\Mpdf();
        $stylesheet = file_get_contents('style.css');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        
        $mpdf->WriteHTML('<html>
    	<body>', \Mpdf\HTMLParserMode::HTML_BODY, true, false);
    		$mpdf->WriteHTML('<div class="invoice-box">
    			<table cellpadding="0" cellspacing="0">
    				<tr class="top">
    					<td colspan="4"> 
    						<table>
    							<tr>
    								<td class="title">
    									<img src="followiz-logo.png" style="width:28%; max-width:300px;">
    								</td>
    								<td>
    									<h2> Invoice #'.$Invoice.'.</h2>
    									Date: '.$payment_date.'<br>
    								</td>
    							</tr>
    						</table>
    					</td>
    				</tr>
    				<tr class="information">
    					<td colspan="4">
    						<table>
    							<tr>
    								<td>
    									Followiz Marketing Inc<br>
    									2115 st-nicolas<br>
    									drummondville QC j2b7a8<br>
    									Canada
    								</td>
    								<td> 
    									<h5> Bill To:</h5>
    									'.$username.'<br>
    									'.$first_name.'<br>
    									'.$last_name.'<br>
    									'.$email.'<br>
    									 
    								</td>
    							</tr>
    						</table>
    					</td>
    				</tr>
    				<tr class="heading">
    				    <td>Item	</td>					
    					<td>Quantity</td>
    					<td>Rate</td>
    					<td>Price</td>
    				</tr>
    				
    				<tr class="item">
    					<td>
    						<h4>  Social Media Marketing Services </h4>
    						 Payment Method : '.$payment_method.' 
    
    					</td>
    					<td style="text-align:left;">1</td>
    					<td>US$ '.$payment_amount.'	</td>
    					<td>US$ '.$payment_amount.'	</td>
    				</tr>
    				<tr class="total">
    				<td colspan="4" style="text-align:right;">
    					Total: US$ '.$payment_amount.'
    					</td>
    				</tr>
    			</table>
    		</body>
    	</html>', \Mpdf\HTMLParserMode::HTML_BODY, false, true);
        $user_id  = isset($user->followiz_id) ? $user->followiz_id : '0';
        
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        
    	
    	// if (!file_exists( "invoices/$user_id/$year/$month/$day")) {
    	    // mkdir("invoices/$user_id/$year/$month/$day", 0777, true);
    	// }
    	 
    	//$folder_path = "invoices/$user_id/$year/$month/$day/";
		$folder_path = "invoices/";
    	$file_name =  $folder_path.$Invoice.'.pdf';
    	$mpdf->Output($file_name , \Mpdf\Output\Destination::FILE);
    
    	//array of all data
    	$followiz_data['payment_id'] = $Invoice;
    	$followiz_data['user_name'] = $username;
    	$followiz_data['payment_date'] = $payment_date;
    	$followiz_data['payment_method'] = $payment_method;
    	$followiz_data['payment_amount'] = $payment_amount ;
    	$followiz_data['first_name'] = $first_name;
    	$followiz_data['last_name'] = $last_name;
    	$followiz_data['email'] = $email ;
    	$followiz_data['user_id']  = $user_id;
    	$followiz_data['invoice_name'] = $file_name;
    	
    
    	
	

    	if($invoice_obj->store_details($followiz_data)){
    		$data_saved = "data is stored.";
    	}else{
    		$data_saved = "data is not stored";
    	}
        
        
    }
    
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("status"=> true , "message" => "Invoice is generated."));
}     

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("status" => false, "message" => "Please provide all data.1"));
}
?>