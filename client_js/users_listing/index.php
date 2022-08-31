<?php 

include("../config/database.php");
$users = new Database();
$users->getConnection();
$users->get_all_users();

if ($_POST['key'] == "activeInactive"){
    $status = $_POST['status'];
    $recordId = $_POST['recordId'];
    $result = $users->update_status($status,$recordId);
    if ($result){
        echo "success"; die;
    }
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

</head>

<body oncontextmenu='return false' class='snippet-body'>
 
<div class="container-fluid mt-100">
    <div class="d-flex flex-wrap justify-content-between">
    </div>
    <div class="card mb-3">
        <div class="card-header pl-0 pr-0">
            <div class="row no-gutters w-100 align-items-center">
                <div class="col-md-1 col-lg-1 col-sm-1 pl-4">Id</div>
            	<div class="col-md-1 col-lg-1 col-sm-1 pl-4">Followiz-Id</div>
                <div class="col-md-4 col-lg-4 col-sm-4">UserName</div>
                <div class="col-md-4 col-lg-4 col-sm-4">email</div>
                <div class="col-md-2 col-lg-2 col-sm-2">Status</div>
            </div>
        </div>
        <?php 
        if($users->number_of_records == 0){?>

        <div class="card-body py-3">
            <div class="row no-gutters align-items-center">
                <h1>No records found.</h1>
            </div>
        </div>
        <hr class="m-0">

        <?php }else{
        	foreach($users->details as $row) {
        ?>
        <div class="card-body py-3">
            <div class="row no-gutters align-items-center">
                <div class="col-md-1 col-lg-1 col-sm-1"><?php echo $row['id']; ?></div>
            	<div class="col-md-1 col-lg-1 col-sm-1"><?php echo $row['followiz_id']; ?></div>
                <div class="col-md-4 col-lg-4 col-sm-4"><?php echo $row['username']; ?></div>
                <div class="col-md-4 col-lg-4 col-sm-4"><?php echo $row['email']; ?></div>
                <?php  $class = ($row['status'])?'fa-times':'fa-check'; ?>
                    <div data-recordId="<?php echo $row['id'] ?>" data-recordStatus="<?php echo ($row['status'])?0:1 ?>"  class="col-md-2 col-lg-2 col-sm-2 updateStatus" >
                        <i class="fa <?php echo $class; ?>" aria-hidden="status"></i>
                    </div>    
                
            </div>
        </div>
        <hr class="m-0">
    <?php } //end of foreach statement
    } //end of if else statement ?>
    </div>
    <?php 
        $users->conn = null;
        $users->users_pagination();
        $total_pages = $users->total_pages;
    ?>
    <nav>
        <ul class="pagination mb-5">
            <?php if($users->page >= 2){  ?> 
            <li class="page-item"><a class="page-link" href="https://www.startdesigns.com/client_js/users_listing/?page=<?php echo $users->page-1; ?>" data-abc="true">«</a></li>
              <?php } ?>

              <?php 
                 for ($i=1; $i<=$total_pages; $i++) {   
                    if ($i == $users->page) {   
              ?>
            <li class="page-item active"><a class="page-link" href="https://www.startdesigns.com/client_js/users_listing/?page=<?php echo $i; ?>" data-abc="true"><?php echo $i; ?></a></li>
        <?php }else { ?>
            <li class="page-item"><a class="page-link" href="https://www.startdesigns.com/client_js/users_listing/?page=<?php echo $i; ?>" data-abc="true"><?php echo $i; ?></a></li>
        <?php }

        }?>
            <!-- <li class="page-item"><a class="page-link" href="javascript:void(0)" data-abc="true">3</a></li> -->
             <?php if($users->page < $total_pages ){  ?>
            <li class="page-item"><a class="page-link" href="https://www.startdesigns.com/client_js/users_listing/?page=<?php echo $users->page+1; ?>" data-abc="true">»</a></li>
             <?php } ?>
        </ul>
    </nav>
</div>
<script>



 function activeInactive(recordId,status,element) {
    var message = ((status == 0?" inactive ":" Active "));
    if (confirm("Are you sure to"+ message+ "the user")){
        $.post("index.php",{key:"activeInactive",status:status,recordId:recordId},function (response) {
            if (response == "success"){
               /* if (status == 0){
                    element.find('i').removeClass('fa-times').addClass('fa-check');
                }else if (status == 1){
                    element.find('i').removeClass('fa-check').addClass('fa-times');
                }*/
                //alert("User is "+ message +"now");
                window.location.reload();
            }
        });
    }
}   
$(document).on('click','.updateStatus',function(){
    let recordId = $(this).attr('data-recordId');
    let status = $(this).attr('data-recordStatus');
    let element = $(this);
    activeInactive(recordId,status,element);
})

</script>
</body>
</html>