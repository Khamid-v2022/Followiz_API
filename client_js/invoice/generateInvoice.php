<?php
include ("../config/database.php");
include ('vendor/autoload.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$Invoice = isset($_POST['payment_id']) ? $_POST['payment_id'] : '';
$username = isset($_POST['user_name']) ? $_POST['user_name'] : '';
$payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : '';
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
$payment_amount = isset($_POST['payment_amount']) ? $_POST['payment_amount'] : '';

$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$other_detail = isset($_POST['other_detail']) ? strip_tags($_POST['other_detail']) : '';
$pieces = explode(PHP_EOL, $other_detail);
$other_detail_str = "";
foreach($pieces as $item){
	$other_detail_str .= $item . "<br/>";
}


$date=date_create($payment_date);
$invoice_date = date_format($date,"M d,Y");
$mpdf = new \Mpdf\Mpdf(['tempDir' => '/tmp']);
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
									'.$other_detail_str.'<br>
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
    
    $user_id  = isset($_POST['user_id']) ? $_POST['user_id'] : '0';
   
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    
	
	if (!file_exists( "invoices/$user_id/$year/$month/$day")) {
	    mkdir("invoices/$user_id/$year/$month/$day", 0777, true);
	}
	 
	$folder_path = "invoices/$user_id/$year/$month/$day/";
	$file_name =  $folder_path.strtotime("now").'.pdf';
	$mpdf->Output($file_name , \Mpdf\Output\Destination::FILE);

	//array of all data
	$followiz_data['payment_id'] = isset($_POST['payment_id']) ? $_POST['payment_id'] : '';
	$followiz_data['user_name'] = isset($_POST['user_name']) ? $_POST['user_name'] : '';
	$followiz_data['payment_date'] = isset($_POST['payment_date']) ? $_POST['payment_date'] : '';
	$followiz_data['payment_method'] = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
	$followiz_data['payment_amount'] = isset($_POST['payment_amount']) ? $_POST['payment_amount'] : '';
	$followiz_data['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
	$followiz_data['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
	$followiz_data['email'] = isset($_POST['email']) ? $_POST['email'] : '';
	$followiz_data['other_detail'] = isset($_POST['other_detail']) ? $_POST['other_detail'] : '';
	$followiz_data['user_id']  = isset($_POST['user_id']) ? $_POST['user_id'] : '0';
	$followiz_data['invoice_name'] = $file_name;

	
	$invoice_obj = new Database();
	$invoice_obj->getConnection();
	

	if($invoice_obj->store_details($followiz_data)){
		$data_saved = "data is stored.";
	}else{
		$data_saved = "data is not stored";
	}

	// set response code - 201 created
	http_response_code(201);
	// tell the user
	echo json_encode(array("status" => true,"payment_id" => $followiz_data['payment_id'], "file_path" => $file_name, "data"=> $data_saved));
	?> 