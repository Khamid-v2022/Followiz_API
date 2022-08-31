 <?php
$servername = "localhost";
$username = "FOLLOWIZ";
$password = "x9=Kxn9cCy";
$dbname = "followiz_new_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$dstatus = $_POST['dstatus'];
$dsid = $_POST['dsid'];

if($dstatus == 'enabled'){
    $ds = 0;
    $dstatus1 = 'disabled';
    $update_message = 'Service Disabled';
    $ds1 = 1;
}

$sql = "UPDATE updates SET status='".$ds1."', update_message = '".$update_message."' WHERE service_id='".$dsid."' and domain = 'main' ";

if ($conn->query($sql) === TRUE) {
  // set response code - 200 ok
    http_response_code(200);   
    // tell the user
    echo json_encode(array("status"=> $dstatus1 , "message" => "service disabled successfully."));
} else {
    http_response_code(500);   
    // tell the user
    echo json_encode(array("message" => "Error"));
}

$conn->close();
?> 