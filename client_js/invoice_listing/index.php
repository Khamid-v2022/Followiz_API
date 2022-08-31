<?php
include("../config/database.php");
$invoice = new Database();
$invoice->getConnection();
// print_r($invoice);
$invoice->get_all_records();
// print_r($invoice->number_of_records);
//print_r($invoice->details); die;
if($_POST['pid']){
    echo $_POST['pid'];
}

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
}</style>

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
    <div class="d-flex flex-wrap justify-content-between">
        <div> 
        </div>
        <div class="col-12 col-md-5 p-0 mb-3"> 
            <form action="<?php echo str_replace('index.php','',$_SERVER['PHP_SELF']); ?>" method="POST" class="form-inline">
                <input type="text" class="form-control"  name="daterange" placeholder="Search..." size="80">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header pl-0 pr-0">
            <div class="row no-gutters w-100 align-items-center">
            	<div class="col-md-1 col-lg-1 col-sm-1 pl-4">ID</div>
                <div class="col-md-8 col-lg-8 col-sm-8">Invoice No. <small>(Client Name)</small></div>
                <div class="col-md-2 col-lg-2 col-sm-2">Created At</div>
                <div class="col">PDF download </div>
            </div>
        </div>
        <?php 
        if($invoice->number_of_records == 0){?>

        <div class="card-body py-3">
            <div class="row no-gutters align-items-center">
                <h1>No records found.</h1>
            </div>
        </div>
        <hr class="m-0">

        <?php }else{
        	foreach($invoice->details as $row) {
        ?>
        <div class="card-body py-3">
            <div class="row no-gutters align-items-center">
            	<div class="col-md-1 col-lg-1 col-sm-1"><?php echo $row['id']; ?></div>
                <div class="col-md-8 col-lg-8 col-sm-8"> 
                	<a href="javascript:void(0)" class="text-big" data-abc="true">#<?php echo $row['payment_id'] ?> <small>(<?php echo $row['user_name'] ?>)</small></a>
                    <div class="text-muted small mt-1"><?php echo "Filename: ".str_replace('..pdf','.pdf',basename($row['invoice_name'])); ?>&nbsp;·&nbsp; 
                    	<!-- <a href="javascript:void(0)" class="text-muted" data-abc="true">Neon Mandela</a> -->
                    </div>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-2"><?php echo substr($row['created_at'],'0','10'); ?></div>
                <div class="col">
                	<a href="<?php echo "https://followizaddons.com/client_js/invoice/".str_replace('..pdf','.pdf',$row['invoice_name']); ?>" target="_blank" download>
                        <button type="button" class="btn btn-primary">Download</button>
                    </a>
                </div>
            </div>
        </div>
        <hr class="m-0">
    <?php } //end of foreach statement
    } //end of if else statement ?>
        
        
        
    </div>
    <?php 
    $invoice->conn = null;
        $invoice->pagination();
        $total_pages = $invoice->total_pages;
        // echo $invoice->page; 
        // echo "<br>".$total_pages; 
        // echo $_SERVER['PHP_SELF'];
        // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . ":
        // //$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    ?>
    <nav>
        <ul class="pagination mb-5">
            <?php if($invoice->page >= 2){  ?> 
            <li class="page-item"><a class="page-link" href="https://www.startdesigns.com/client_js/invoice_listing/?page=<?php echo $invoice->page-1; ?>" data-abc="true">«</a></li>
              <?php } ?>

              <?php 
                 for ($i=1; $i<=$total_pages; $i++) {   
                    if ($i == $invoice->page) {   
              ?>
            <li class="page-item active"><a class="page-link" href="https://www.startdesigns.com/client_js/invoice_listing/?page=<?php echo $i; ?>" data-abc="true"><?php echo $i; ?></a></li>
        <?php }else { ?>
            <li class="page-item"><a class="page-link" href="https://www.startdesigns.com/client_js/invoice_listing/?page=<?php echo $i; ?>" data-abc="true"><?php echo $i; ?></a></li>
        <?php }

        }?>
            <!-- <li class="page-item"><a class="page-link" href="javascript:void(0)" data-abc="true">3</a></li> -->
             <?php if($invoice->page < $total_pages ){  ?>
            <li class="page-item"><a class="page-link" href="https://www.startdesigns.com/client_js/invoice_listing/?page=<?php echo $invoice->page+1; ?>" data-abc="true">»</a></li>
             <?php } ?>
        </ul>
    </nav>
</div>
    </body>
    <script type="text/javascript">
    $(document).ready(function(){
        // $('input[name="daterange"]').daterangepicker();
        $('input[name="daterange"]').daterangepicker(
            {
                locale: {
                  format: 'YYYY-MM-DD'
                }
                ,
                startDate: '<?php echo date("Y-m-d" ,strtotime("-360 day"));?>',
                endDate: '<?php echo date("Y-m-d");?>'
            }
            , 
            function(start, end, label) {
                // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
    });
</script>
</html>