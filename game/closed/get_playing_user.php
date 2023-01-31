<?php
include "connection/config.php";
if (!checks("admin"))
{
    redirect("login.php");
}

if(isset($_REQUEST['from'])){
    $fromDate = $_REQUEST['from'];
    $toDate = $_REQUEST['to'];
    
    $From_explode = explode('/',$fromDate);
    $to_explode = explode('/',$toDate);
    
    $start_time = mktime(0, 0, 0, $From_explode[1], $From_explode[0], $From_explode[2]);
    $end_time = mktime(23, 59, 59, $to_explode[1], $to_explode[0], $to_explode[2]);
    
} else {
    
     $start_time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $end_time = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
}



$blog = query("select * from games where created_at > $start_time AND created_at < $end_time group by user");



?>

<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Panel</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/horizontal-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    
    <style>
        .ref {
                background: #4caf50;
                padding: 10px;
                color: white;
                font-size: 15px;
                border-radius: 5px;
                margin-top: 20px;
                display:none;
        }
    </style>
</head>


<body class="sidebar-dark" style="font-family: 'Oxygen', sans-serif;">
  <div class="container-fluid page-body-wrapper">

    <?php include "include/header.php"; ?>

        <div class="main-panel">
            <div class="content-wrapper">
                 
             <div class="card">
                 <div class="card-body">
                      <div class="row">
                        
                         
                                     
                                 
                                   <form method="post"  autocomplete="off">
                                        <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                        <div class="form-group" style="    margin-bottom: 10px !important; margin-right:20px;">
                                            <label for="exampleInputName1">From Date</label>
                                            <input type="datepicker" id="datepicker" class="form-control" name="from" value="<?php echo date('d/m/Y',$start_time); ?>" required>
                                        </div>
                                        
                                           
                                        <div class="form-group" style="    margin-bottom: 10px !important; margin-right:20px;">
                                            <label for="exampleInputName1">To Date</label>
                                            <input type="datepicker" id="datepicker2" class="form-control" value="<?php echo date('d/m/Y',$end_time); ?>" name="to" required>
                                        </div>
                                         
                                        <button style="margin-top: 32px;" class="btn btn-primary">Filter</button>
                                   </form>
                       
                        
                    </div>
                 </div>
             </div>
             
                <div class="card">
                    <div class="card-body">
                       
                                
                                 <h4 class="card-title" style="margin-top:20px;">User Playing history</h4>
                                 
                                 
                                 
                        <div class="row">
                            <div class="col-12">
                                
                                  
                                    
                               
                                
                                <div class="table-responsive">
                                    <table id="order-listing" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sn</th>
                                                    <th>User Mobile</th>
                                                    <th>User Name</th>
                                                    <th>Total Bets</th>
                                                    <th>Total Amount</th>
                                                    <th>Latest Bet</th>
                                                </tr>
                                            </thead>
                                        <tbody>

                                           
                                        <?php $i = 1; while ($bl = fetch($blog)) { 
                                        $user = $bl['user'];
                                        
                                        
                                        
                                        $blog_er = fetch(query("select count(sn) as total_bets, sum(amount) as total_amount from games where created_at > $start_time AND created_at < $end_time  AND user='$user'"));

                                        $csx = query("select * from users where mobile='$user'");
                                        $xs = fetch($csx);
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $bl['user']; ?></td>
                                                <td><?php echo $xs['name']; ?></td>
                                                <td><?php echo $blog_er['total_bets']; ?></td>
                                                <td><?php echo $blog_er['total_amount']; ?></td>
                                                <td><?php echo date("d/m/Y h:i A",$bl['created_at']); ?></td>
                                                
                                            </tr>
                                        <?php $i++; } ?>
                                        

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="w-100 clearfix">
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="js/data-table.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .datepicker {
            z-index: 99999 !important;
    }
</style>
<script>
$('#datepicker').datepicker({
    format: 'dd/mm/yyyy',
    endDate: '0d'
});
$('#datepicker2').datepicker({
    format: 'dd/mm/yyyy',
    endDate: '0d'
});
</script>


<!-- End custom js for this page-->
</body>

</html>
