<?php
include "connection/config.php";

if (!checks("admin"))
{
    redirect("login.php");
}

$time = date("H:i",$stamp);
$day = strtoupper(date("l",$stamp));
$date = date("d/m/Y");

$sn = $_REQUEST['sn'];



if (isset($_REQUEST['submit']))
{
    extract($_REQUEST);
    
        $xvm = query("select * from rate where sn='1'");
        $xv = fetch($xvm);
  
    $mrk = str_replace(" ","_",$market.' OPEN');
    $mrk2 = str_replace(" ","_",$market.' CLOSE');
  
 $open = $jodi[0];
 $close = $jodi[1];
  
  query("INSERT INTO `manual_market_results`( `market`, `date`, `open_panna`, `open`, `close`, `close_panna`, `created_at`) VALUES ('$market','$date','','$open','$close','','$stamp')");
  
    
        $xx = query("select * from games where bazar='$mrk' AND game='single' AND date='$date' AND number='$open' AND status='0' AND is_loss='0'");
        
        while($x = fetch($xx))
        {
            $sn = $x['sn'];
            $user = $x['user'];
            $amount = $x['amount']*$xv[$x['game']];
            
            $remrk = $x['game']." ".$x['bazar']." Winning";
        
            query("update games set status='1' where sn='$sn'");
        
            query("update users set winning=winning+'$amount' where mobile='$user'");
            
            query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at`,`batch_id`,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$batch_id','$sn')");
            
        }
        
        query("UPDATE games set is_loss='1' where bazar='$mrk' AND game='single' AND date='$date' AND number!='$open' AND is_loss='0'");
  
  
  
        $xx = query("select * from games where bazar='$mrk2' AND game='single' AND date='$date' AND number='$close' AND status='0' AND is_loss='0'");
        
        while($x = fetch($xx))
        {
            $sn = $x['sn'];
            $user = $x['user'];
            $amount = $x['amount']*$xv[$x['game']];
            
            $remrk = $x['game']." ".$x['bazar']." Winning";
        
            query("update games set status='1' where sn='$sn'");
        
            query("update users set winning=winning+'$amount' where mobile='$user'");
            
            query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at`,`batch_id`,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$batch_id','$sn')");
            
        }
        
        query("UPDATE games set is_loss='1' where bazar='$mrk2' AND game='single' AND date='$date' AND number!='$close' AND is_loss='0'");
  
  
        
        $get_games = query("SELECT * FROM `games` where bazar='$market' AND game='jodi' AND date='$date' AND number='$jodi'");
        
        
        while($x = fetch($get_games))
        {
            $sn = $x['sn'];
            $user = $x['user'];
            $amount = $x['amount']*$xv[$x['game']];
            
            $remrk = $x['game']." ".$x['bazar']." Winning";
        
            query("update games set status='1' where sn='$sn'");
        
            query("update users set winning=winning+'$amount' where mobile='$user'");
            
            query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at`) VALUES ('$user','$amount','1','$remrk','$stamp')");
        }
  
    query("UPDATE games set is_loss='1' where bazar='$bazar' AND game='jodi' AND date='$date' AND number!='$jodi' AND status='0' AND is_loss='0'");
        
    }
    
    // if(isset($_REQUEST['redirect_back'])){
    //     header("location:".$redirect_back);
    // }




$market = query("select * from gametime_delhi");

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
</head>

<body class="sidebar-dark" style="font-family: 'Oxygen', sans-serif;">
  <div class="container-fluid page-body-wrapper">

    <?php include "include/header.php"; ?>

    <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                
                   <?php if(isset($error)) { ?>
                        
                            <div class="alert alert-danger" role="alert">
                              <?php echo $error; ?>
                            </div>
                        
                        <?php } ?>
                        
                <div class="row">
                    
              
                    
                    <?php while($mrk = fetch($market)){
                        
                        $mrk_sn = $mrk['sn'];
                        
                        $mrk_name = $mrk['market'];
                    
                    ?>

                    <div class="col-sm-12 col-md-6 col-lg-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $mrk['market']; ?></h4>
                                
                                
                                <form class="forms-sample" method="post" enctype="multipart/form-data">
                                     
                                     
                                    <input type="hidden" name="market" value="<?php echo $mrk['market']; ?>" required>
                                    
                                    
                                   <div class="row">
                                    
                                       <div class="col-sm-12">
                                           <div class="form-group">
                                                <label for="open">Jodi</label>
                                                <input type="text" pattern="(.){2,2}"  maxlength="2"  class="form-control" id="open" name="jodi" required>
                                            </div>
                                       </div>
                                   </div>
                                    
                                    
                                    
                                    <button type="submit" class="btn btn-primary mr-2 mt-4" name="submit" style="width: 100%">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <?php  }
                    
                   
                    ?>

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
<!-- End custom js for this page-->
</body>

</html>
