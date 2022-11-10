<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

include ("../config/database.php");
include ('vendor/autoload.php'); 
include_once '../objects/paymentList.php';
include_once '../objects/user.php';

$list = isset($_POST['paymentList']) ? $_POST['paymentList'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

$database = new Database();
$db = $database->getConnection();

$payment = new PaymentList($db);
$added = [];

function generatePDF($item){

	$Invoice = $item['payment_id'];
	
	$username = isset($_POST['user_name']) ? $_POST['user_name'] : '';
	$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
	$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
	$email = isset($_POST['email']) ? $_POST['email'] : '';

	$payment_date = $item['date'];
	$payment_method = $item['method'];
	$payment_amount = $item['amount'];

	global $db, $user_id, $payment;
	
	$user = new User($db);
	$user->followiz_id = $user_id;
	$user->readOne();

	$other_name = $user->other_name;
	$other_phone = $user->other_phone;
	$other_address = $user->other_address;
	$other_city = $user->other_city;
	$other_country = $user->other_country;
	$other_province = $user->other_province;
	$other_postal = $user->other_postal;
	$other_detail = $user->other_detail;

	$pieces = explode(PHP_EOL, $other_detail);
	$other_detail_str = "";
	foreach($pieces as $item){
		$other_detail_str .= $item . "<br/>";
	}

	// $date = date_create($payment_date);
	// $invoice_date = date_format($date,"M d,Y");

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
										<h2> Invoice #' . $Invoice . '.</h2>
										Date: ' . $payment_date . '<br>
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
										'.$email.'<br>' 
										. ($other_name ? $other_name . '<br>' : '')
										. ($other_phone ? $other_phone . '<br>' : '')
										. ($other_address ? $other_address . '<br>' : '')
										. ($other_city ? $other_city . '<br>' : '')
										. ($other_country ? $other_country . '<br>' : '')
										. ($other_province ? $other_province . '<br>' : '')
										. ($other_postal ? $other_postal . '<br>' : '')
										. ($other_detail_str ? $other_detail_str : '')
										. '
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
	    
		
		if (!file_exists( "invoices/new/$user_id")) {
		    mkdir("invoices/new/$user_id", 0777, true);
		}
		 
		$folder_path = "invoices/new/$user_id/";
		$file_name =  $folder_path . strtotime($payment_date) . '.pdf';
		$mpdf->Output($file_name , \Mpdf\Output\Destination::FILE);

		$payment->user_id = $user_id;
		$payment->payment_date = $payment_date;
		$payment->invoice_path = "/client_js/invoice/" . $file_name;
		$payment->update();

}

if(count($list) > 0){
	foreach($list as $item){
		$payment->user_id = $user_id;
		$payment->payment_date = $item['date'];
		if(!$payment->readOne()){
			$payment->user_id = $user_id;
			$payment->payment_id = $item['id'];
			$payment->payment_date = $item['date'];
			$payment->amount = $item['amount'];
			$payment->type = $item['method'];
			$payment->original_amount = $item['original_amount'];
			$payment->original_currency = $item['original_currency'];
			$payment->create();
			array_push($added, $item);
		}
	}
}

// generate PDFs
foreach($added as $item){
	generatePDF($item);
}



http_response_code(201);
	// tell the user
echo json_encode(array("status" => true, "list" => $added));
?>