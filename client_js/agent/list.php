<?php
include("../config/database.php");
$database = new Database();
$db = $database->getConnection();
include_once '../objects/agentRating.php';

$agent = new Agent($db);
$startDate =  date('m-d-Y', strtotime('-7 days'));  //"02/26/2021";
$endDate   =  date('m-d-Y');  //"03/04/2021";

if(isset($_GET['start-date']) && isset($_GET['end-date']) ){
    $startDate =  $_GET['start-date'];
    $endDate   =  $_GET['end-date'];  
}

$agent->start_date =  date('Y-m-d',strtotime($startDate)).' 00:00:00';
$agent->end_date   = date('Y-m-d',strtotime($endDate)).' 23:59:59';

$agentsRating =  $agent->getAgentRating();
?>

<!doctype html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>List of PDF</title>
        <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href='' rel='stylesheet'>
        <style>body {
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-size: .88rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                text-align: left;
                background-color: #eef1f3
            }
            
            .mt-100 {
                margin-top: 80px
            }
            
            .card {
                box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, 0.03), 0 0.9375rem 1.40625rem rgba(4, 9, 20, 0.03), 0 0.25rem 0.53125rem rgba(4, 9, 20, 0.05), 0 0.125rem 0.1875rem rgba(4, 9, 20, 0.03);
                border-width: 0;
                transition: all .2s
            }
            
            .card-header:first-child {
                border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0
            }
            
            .card-header {
                display: flex;
                align-items: center;
                border-bottom-width: 1px;
                padding-top: 0;
                padding-bottom: 0;
                padding-right: .625rem;
                height: 3.5rem;
                background-color: #fff;
                border-bottom: 1px solid rgba(26, 54, 126, 0.125)
            }
            
            .btn-primary.btn-shadow {
                box-shadow: 0 0.125rem 0.625rem rgba(63, 106, 216, 0.4), 0 0.0625rem 0.125rem rgba(63, 106, 216, 0.5)
            }
            
            .btn.btn-wide {
                padding: .375rem 1.5rem;
                font-size: .8rem;
                line-height: 1.5;
                border-radius: .25rem
            }
            
            .btn-primary {
                color: #fff;
                background-color: #3f6ad8;
                border-color: #3f6ad8
            }
            
            .form-control {
                display: block;
                width: 100%;
                height: calc(2.25rem + 2px);
                padding: .375rem .75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out
            }
            
            .card-body {
                flex: 1 1 auto;
                padding: 1.25rem
            }
            
            .flex-truncate {
                min-width: 0 !important
            }
            
            .d-block {
                display: block !important
            }
            
            a {
                color: #E91E63;
                text-decoration: none !important;
                background-color: transparent
            }
            
            .media img {
                width: 40px;
                height: auto
            }
            
            .panel-sectionfor-userrating {
                display: flex;
                justify-content: space-between;
                padding: 20px;
                background-color: #fff;
                border-bottom: 1px solid #efefef;
            }
            
            .panel-sectionfor-userrating h3 {
                display: inline-block;
                font-size: 20px;
            }
            
            .form-inline .form-control {
                width: 220px;
                margin-right: 1px;
            }
                        
            
            </style>

<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'></script>
<!-- Include Required Prerequisites -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" /> -->
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<script type='text/javascript'></script>
</head>

<body oncontextmenu='return false' class='snippet-body'>
    <div class="container-fluid mt-100">
        <div class="panel-sectionfor-userrating">
        <h3>User Rating</h3>
        <div class="d-flex flex-wrap justify-content-between">
           
            <div class="col-12"> 
                <form action="<?php echo str_replace('index.php','',$_SERVER['PHP_SELF']); ?>" method="POST" class="form-inline">
                    <input type="text" class="form-control"  name="daterange" id="daterangepicker" placeholder="Search..." size="80">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        
        </div>
        <div class="card mb-3">
            <div class="card-header pl-0 pr-0">
                <div class="row no-gutters w-100 align-items-center">
                	<div class="col-md-1 col-lg-1 col-sm-1 pl-4">ID</div>
                    <div class="col-md-8 col-lg-8 col-sm-8">Agent Name</div>
                    <div class="col-md-2 col-lg-2 col-sm-2">Rating</div>
                </div>
            </div>
           
    
            <?php if(count($agentsRating) > 0) { ?>
            <?php foreach($agentsRating as $key => $ar) { ?>
            <div class="card-body pl-0 pr-0">
                <div class="row no-gutters align-items-center">
                	<div class="col-md-1 col-lg-1 col-sm-1 pl-4"><?php echo $key+1; ?></div>
                    <div class="col-md-8 col-lg-8 col-sm-8"> <?php echo $ar['AgentName']; ?> </div>
                    <div class="col-md-2 col-lg-2 col-sm-2"><?php echo $ar['AVGRating']; ?></div>
               </div>
            </div>
            <?php } ?>
            <?php }else{ ?>
            <div class="card-header pl-0 pr-0">
                <div class="row no-gutters w-100 align-items-center">
                	<div class="col-md-12 col-lg-12 col-sm-12 pl-4 text-center">Sorry No Record Found.</div>
                 </div>
            </div>
             <div></div>
            <?php } ?>
        </div>
    </div>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $('#daterangepicker').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "startDate":  "<?php echo $startDate; ?>",// "02/26/2021",
            "endDate": "<?php echo $endDate; ?>"//"03/04/2021"
        }, function(start, end, label) {
          //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
          //console.log(window.location.href+"?startDate="+start.format('MM/DD/YYYY')+'&endDate='+end.format('MM/DD/YYYY'));
          
          window.location.href = window.location.origin + window.location.pathname+"?start-date="+start.format('MM/DD/YYYY')+'&end-date='+end.format('MM/DD/YYYY');
        });
    });
    </script>
    </body>
</script>
</html>