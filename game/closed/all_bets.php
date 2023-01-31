<?php
include "connection/config.php";
if (!checks("admin"))
{
    redirect("login.php");
}

$mobile = $_REQUEST['mobile'];

$blog = query("select * from games where user='$mobile' order by sn desc"); 



?>

<!DOCTYPE html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Partner Panel</title>
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
    
</head>

<body class="sidebar-dark" style="font-family: 'Oxygen', sans-serif;">
  <div class="container-fluid page-body-wrapper">

    <?php include "include/header.php"; ?>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Manage Bets</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="order-listing" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sn</th>
                                                    <th>User Mobile</th>
                                                    <th>User Name</th>
                                                    <th>Number</th>
                                                    <th>Amount</th>
                                                    <th>Game</th>
                                                    <th>Market</th>
                                                    <th>Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        <tbody>

                                        <?php $i = 1; while ($bl = fetch($blog)) { 
                                        $user = $bl['user'];
                                        
                                        $csx = query("select * from users where mobile='$user'");
                                        $xs = fetch($csx);
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $bl['user']; ?></td>
                                                <td><?php echo $xs['name']; ?></td>
                                                <td><?php if($game=="jodi"){ echo sprintf("%02d", $bl['number']); } else {  echo $bl['number']; } ?></td>
                                                <td><?php echo $bl['amount']; ?></td>
                                                <td><?php echo $bl['game']; ?></td>
                                                <td><?php echo str_replace("_"," ",$bl['bazar']); ?></td>
                                                <td><?php echo date("d/m/Y h:i A",$bl['created_at']); ?></td>
                                                <td id="actionmenu<?php echo $i; ?>">
                                                    <?php if($bl['status'] == "1") { ?>
                                                        Won
                                                    <?php } else if($bl['status'] == "0" && $bl['date'] == date('d/m/Y')){ echo "Pending"; } else { echo "Not won"; } ?>
                                                </td>
                                            </tr>
                                        <?php $i++; } ?>
                                        
                                        <script>
                                            
                                            function win(sn,amount,user)
                                            {
                                                console.log("ajax/update.php?user="+user+"&amount="+amount+"&sn="+sn);
                                                $.LoadingOverlay("show");
                                                
                                                $.ajax({
                                                  url: "ajax/update.php?user="+user+"&amount="+amount+"&sn="+sn,
                                                  cache: false,
                                                  success: function(html){
                                                      
                                                      console.log(html);
                                                      
                                                     $.LoadingOverlay("hide");
                                                    
                                                    $('#actionbar'+sn).hide();
                                                    $('#msg'+sn).show();
                                                  }
                                                });
                                            }
                                            
                                            function lose(sn)
                                            {
                                                $.LoadingOverlay("show");
                                                
                                                $.ajax({
                                                  url: "ajax/lose.php?sn="+sn,
                                                  cache: false,
                                                  success: function(html){
                                                     $.LoadingOverlay("hide");
                                                    $('#actionbar'+sn).hide();
                                                    $('#msg'+sn).show();
                                                  }
                                                });
                                            }
                                            
                                        </script>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.js"></script>
<script src="js/data-table.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>
<!-- End custom js for this page-->
</body>

</html>
