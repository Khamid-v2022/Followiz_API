<!DOCTYPE html>
<html lang="en">
<head>
  <title>Agent Rating</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    form.form-inline {
    width: 100%;
    text-align: end;
    margin-top: 40px;
    margin-bottom: 30px;
}

form.form-inline button.btn.btn-primary {
    height: 36px;
}
table.table.table-striped.mt-5 {
      border: 1px solid #cccc;
}
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }
  </style>
</head>
<body>
<?php
// include database and object files
include_once 'config/database.php';
include_once 'objects/agentrating.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$agentratings = new AgentRatings($db);
 
$agentsArr = [];
$agentstmt = $agentratings->getAgents();
while ($row = $agentstmt->fetch(PDO::FETCH_ASSOC)){
	extract($row);
    $agentsArr[$id] =  $name;
}

$dateRange = false;

$startDate = date('Y-m-d');
$endDate = date('Y-m-d', strtotime("+7 day"));

if(isset($_POST['daterange'])){
   $dateRange = explode(' - ',$_POST['daterange']);
   $startDate = date('Y-m-d', strtotime($dateRange[0]));
   $endDate = date('Y-m-d', strtotime($dateRange[1]));
}

$stmt = $agentratings->read($dateRange);
$num = $stmt->rowCount();
$rating = array();
if($num>0){
    $response["data"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $temp=array(
            "id" => $agent_id,
            "agaent_name" => $agentsArr[$agent_id],
            "rating" => $agent_ratings,
            "response_time" => $response_time,
        );
        array_push($rating, $temp);
    }
}


// $stmt = $agentratings->read();
// $num = $stmt->rowCount();
// $rating = array();

// if($num>0){
//     $response["data"]=array();
//     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//         extract($row);
//         $temp=array(
//             "id" => $id,
//             "agaent_name" => $agaent_name,
//             "rating" => $agent_rating,
//             "response_time" => $response_time,
//             "response_date" => date('d-m-Y' , strtotime($response_date)),
//         );
//         array_push($rating, $temp);
//     }
// }



?>

<div class="container-fluid text-center"> 
<!--   <div class="row content">
    <div class="col-12 col-md-5 p-0 mb-3"> 
        <form action="<?php echo str_replace('index.php','',$_SERVER['PHP_SELF']); ?>" method="POST" class="form-inline">
            <input type="text" class="form-control"  name="daterange" placeholder="Search..." size="80">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>
  </div>   --> 
  <div class="row content">
    <div class="col-sm-2">
    
    </div>
    <div class="col-sm-8 text-left"> 
              <form action="<?php echo str_replace('index.php','',$_SERVER['PHP_SELF']); ?>" method="POST" class="form-inline">
            <input type="text" class="form-control"  name="daterange" placeholder="Search..." size="80">
            <button type="submit" class="btn btn-primary">
             <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </form>
     	<table class="table table-striped mt-5">
		  <thead>
		    <tr>
		      <th scope="col">Agent Id</th>
		      <th scope="col">Agent Name</th>
		      <th scope="col">Rating </th>
		      <th scope="col">Response Time <small>In Minutes</small></th>
		    </tr>
		  </thead>
		  <tbody><?php 
		  	if(!empty($response)){
		  		foreach($rating as $r){ ?>
		  			<tr>
				      <th scope="row"><?php echo $r['id']; ?></th>
				      <td><?php echo $r['agaent_name']; ?></td>
				      <td><?php echo $r['rating']; ?></td>
				      <td><?php echo $r['response_time']; ?></td>
				    </tr> <?php
		  		}
		  	} ?>
		  </tbody>
		</table>
    </div>
    <div class="col-sm-2">

    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    // $('input[name="daterange"]').daterangepicker();
    $('input[name="daterange"]').daterangepicker(
        {
            locale: {
              format: 'YYYY-MM-DD'
            }
            ,
            startDate: "<?php echo $startDate; ?>",
            endDate: "<?php echo $endDate; ?>",
        }
        , 
        function(start, end, label) {
            // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
});
</script>
</body>
</html>