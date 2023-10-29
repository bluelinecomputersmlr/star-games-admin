<?php
include "connection/config.php";

if (!checks("admin"))
{
    redirect("login.php");
}

$time = date("H:i",$stamp);
$day = strtoupper(date("l",$stamp));
if(!isset($_REQUEST['date'])){
    $date = date("d/m/Y");
}

$sn = $_REQUEST['sn'];


 
 


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
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/horizontal-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    
    <style>
        input {
            padding: 0.875rem 0.5rem !important;
            text-align: center;
        }
    </style>
</head>

<body class="sidebar-dark" style="font-family: 'Oxygen', sans-serif;">
  <div class="container-fluid page-body-wrapper">
    <?php include "include/header.php"; ?>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    
                    <div class="col-sm-12">
                        <h4>Manual Market Results</h4>
                    </div>
                    
                    <?php if(isset($_REQUEST['error'])){ ?>
                        
                        <div class="alert alert-danger" role="alert">
                          <?php echo $_REQUEST['error']; ?>
                        </div>
                    
                    <?php } ?>
                    
                    <?php $get = query("select * from gametime_manual");
                        while($xc = fetch($get))
                        {
                            $market = $xc['market'];
                            
                            $chk_if_query = query("select open,open_panna,close_panna,close from manual_market_results where market='$market' AND date='$date'");
                            if(rows($chk_if_query) > 0){
                                $chk_if_updated = fetch($chk_if_query);
                               
                                if($chk_if_updated['close'] != "" && $chk_if_updated['close_panna'] != ""){
                                    continue;
                                } else {
                                    $open[$market] = $chk_if_updated['open'];
                                    $opanna[$market] = $chk_if_updated['open_panna'];
                                }
                            }
                           
                            if($xc['days'] == "ALL" || substr_count($xc['days'],$day) == 0){
                                if(strtotime($time)<strtotime($xc['open']))
                                {
                                    $xc['is_open'] = "1";
                                }
                                else
                                {
                                    $xc['is_open'] = "0";
                                }
                                
                                if(strtotime($time)<strtotime($xc['close']))
                                {
                                    $xc['is_close'] = "1";
                                }
                                else
                                {
                                    $xc['is_close'] = "0";
                                }
                            } else if(substr_count($xc['days'],$day."(CLOSE)") > 0){
                                $xc['is_open'] = "0";
                                $xc['is_close'] = "0";
                                $xc['open'] = "CLOSE";
                                $xc['close'] = "CLOSE";
                            } else {
                                $time_array = explode(",",$xc['days']);
                                for($i =0;$i< count($time_array);$i++){
                                    if(substr_count($time_array[$i],$day) > 0){
                                        $day_conf = $time_array[$i];
                                    }
                                }
                                
                                $day_conf = str_replace($day."(","",$day_conf);
                                $day_conf = str_replace(")","",$day_conf);
                                
                                $mrk_time = explode("-",$day_conf);
                                
                                
                                $xc['open'] = $mrk_time[0];
                                $xc['close'] = $mrk_time[1];
                                
                                if(strtotime($time)<strtotime($mrk_time[0]))
                                {
                                    $xc['is_open'] = "1";
                                }
                                else
                                {
                                    $xc['is_open'] = "0";
                                }
                                
                                if(strtotime($time)<strtotime($mrk_time[1]))
                                {
                                    $xc['is_close'] = "1";
                                }
                                else
                                {
                                    $xc['is_close'] = "0";
                                }
                            }
                        
                                    if($xc['is_close'] == "1" || $xc['is_open'] == "0"){
                                        
                                        $available2 = true;
                                        
                                        $mrk_name = str_replace(" ","_",$xc['market']);
                                        $mrk_name2 = str_replace(" ","_",$xc['market'].' OPEN');
                                        $mrk_name3 = str_replace(" ","_",$xc['market'].' CLOSE');
                                        
                                        $check_games = query("select sn from games where date='$date' AND ( bazar='$mrk_name' OR bazar='$mrk_name2' OR bazar='$mrk_name3')");
                                        $check_games2 = query("select sn from games where date='$date' AND ( bazar='$mrk_name' OR bazar='$mrk_name2' OR bazar='$mrk_name3') AND is_loss='0' AND status='0'");
                    
                    ?>

                    <div class="col-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $xc['market'].' ( '.$xc['open'].' - '.$xc['close'].' )'; ?><br>Game Played = <?php echo rows($check_games); ?><br>Pending Games = <?php echo rows($check_games2); ?></h4>
                                
                                
                                <form class="forms-sample" method="post" enctype="multipart/form-data">
                                     
                                     
                                    <input type="hidden" name="market" value="<?php echo $xc['market']; ?>" required>
                                      
                                     <div class="form-group">
                                        <label for="exampleInputName1">Date ( Format must be DD/MM/YYYY ex. 26/06/2021 )</label>
                                        <input type="text" class="form-control" name="date" value="<?php echo date("d/m/Y"); ?>" required>
                                    </div>
                                    
                                    
                                   <div class="row">
                                       <div class="col-sm-4">
                                           <div class="form-group">
                                                <label for="opanna">Open Panna</label>
                                                <input type="text" <?php if(isset($opanna)){ echo 'value="'.$opanna[$xc['market']].'"'; } ?> maxlength = "3" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" min="100" max="999"  class="form-control" id="opanna" name="opanna">
                                            </div>
                                       </div>
                                       <div class="col-sm-2">
                                           <div class="form-group">
                                                <label for="open">Open</label>
                                                <input type="text" <?php if(isset($open)){ echo 'value="'.$open[$xc['market']].'"'; } ?> maxlength = "1" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"  min="0" max="9" class="form-control" id="open" name="open">
                                            </div>
                                       </div>
                                        <div class="col-sm-2">
                                           <div class="form-group">
                                                <label for="close">Close</label>
                                                <input type="text" maxlength = "1" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"  min="0" max="9"  class="form-control" id="close" name="close">
                                            </div>
                                       </div>
                                        <div class="col-sm-4">
                                           <div class="form-group">
                                                <label for="cpanna">Close Panna</label>
                                                <input type="text" maxlength = "3" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"  min="100" max="999"  class="form-control" id="cpanna" name="cpanna">
                                            </div>
                                       </div>
                                   </div>
                                    
                                    
                                    
                                    <button type="submit" class="btn btn-primary mr-2 mt-4" name="submit_manual" style="width: 100%">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <?php } else {
                        unset($open);
                        unset($opanna);
                    } }  
                    if(!isset($available2)){ ?>
                        
                        <h4 style="padding:20px;text-align: center;
    position: relative;
    width: 100%;">No Results Pending</h4>
                        
                   <?php }
                    
                    ?>
                    
                    
                    
                    
                    <div class="col-sm-12">
                        <h4>Auto Market Results</h4>
                    </div>
                    
                    
                    <?php 
                    $market = query("select * from gametime_new");
                    
                    while($xc = fetch($market)){
                                            
                                        if($xc['days'] == "ALL" || substr_count($xc['days'],$day) == 0){
                                            if(strtotime($time)<strtotime($xc['open']))
                                            {
                                                $xc['is_open'] = "1";
                                            }
                                            else
                                            {
                                                $xc['is_open'] = "0";
                                            }
                                            
                                            if(strtotime($time)<strtotime($xc['close']))
                                            {
                                                $xc['is_close'] = "1";
                                            }
                                            else
                                            {
                                                $xc['is_close'] = "0";
                                            }
                                        } else if(substr_count($xc['days'],$day."(CLOSED)") > 0){
                                            $xc['is_open'] = "2";
                                            $xc['is_close'] = "2";
                                        } else {
                                            $time_array = explode(",",$xc['days']);
                                            for($i =0;$i< count($time_array);$i++){
                                                if(substr_count($time_array[$i],$day) > 0){
                                                    $day_conf = $time_array[$i];
                                                }
                                            }
                                            
                                            $day_conf = str_replace($day."(","",$day_conf);
                                            $day_conf = str_replace(")","",$day_conf);
                                            
                                            $mrk_time = explode("-",$day_conf);
                                            
                                             $xc['open'] = $mrk_time[0];
                                                $xc['close'] = $mrk_time[1];
                                                
                                                if(strtotime($time)<strtotime($mrk_time[0]))
                                                {
                                                    $xc['is_open'] = "1";
                                                }
                                                else
                                                {
                                                    $xc['is_open'] = "0";
                                                }
                                                
                                                if(strtotime($time)<strtotime($mrk_time[1]))
                                                {
                                                    $xc['is_close'] = "1";
                                                }
                                                else
                                                {
                                                    $xc['is_close'] = "0";
                                                }
                                        }
                        
                                    if($xc['is_close'] == "0" || $xc['is_open'] == "0"){
                                        
                                        $available = true;
                                        
                                        $mrk_name = str_replace(" ","_",$xc['market']);
                                        $mrk_name2 = str_replace(" ","_",$xc['market'].' OPEN');
                                        $mrk_name3 = str_replace(" ","_",$xc['market'].' CLOSE');
                                        
                                        $check_games = query("select sn from games where date='$date' AND ( bazar='$mrk_name' OR bazar='$mrk_name2' OR bazar='$mrk_name3')");
                                        $check_games2 = query("select sn from games where date='$date' AND ( bazar='$mrk_name' OR bazar='$mrk_name2' OR bazar='$mrk_name3') AND is_loss='0' AND status='0'");
                    
                    ?>

                    <div class="col-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $xc['market'].' ( '.$xc['open'].' - '.$xc['close'].' )'; ?><br>Game Played = <?php echo rows($check_games); ?><br>Pending Games = <?php echo rows($check_games2); ?></h4>
                                
                                
                                <form class="forms-sample" method="post" enctype="multipart/form-data">
                                     
                                     
                                    <input type="hidden" name="market" value="<?php echo $xc['market']; ?>" required>
                                      
                                     <div class="form-group">
                                        <label for="exampleInputName1">Date ( Format must be DD/MM/YYYY ex. 26/06/2021 )</label>
                                        <input type="text" class="form-control" name="date" value="<?php echo date("d/m/Y"); ?>" required>
                                    </div>
                                    
                                   <div class="row">
                                       <div class="col-sm-4">
                                           <div class="form-group">
                                                <label for="opanna">Open Panna</label>
                                                <input type="text" maxlength = "3" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" min="100" max="999"  class="form-control" id="opanna" name="opanna">
                                            </div>
                                       </div>
                                       <div class="col-sm-2">
                                           <div class="form-group">
                                                <label for="open">Open</label>
                                                <input type="text" maxlength = "1" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"  min="0" max="9" class="form-control" id="open" name="open">
                                            </div>
                                       </div>
                                        <div class="col-sm-2">
                                           <div class="form-group">
                                                <label for="close">Close</label>
                                                <input type="text" maxlength = "1" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"  min="0" max="9"  class="form-control" id="close" name="close">
                                            </div>
                                       </div>
                                        <div class="col-sm-4">
                                           <div class="form-group">
                                                <label for="cpanna">Close Panna</label>
                                                <input type="text" maxlength = "3" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)"  min="100" max="999"  class="form-control" id="cpanna" name="cpanna">
                                            </div>
                                       </div>
                                   </div>
                                    
                                    
                                    
                                    <button type="submit" class="btn btn-primary mr-2 mt-4" name="submit" style="width: 100%">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <?php } } 
                    
                    if(!isset($available)){ ?>
                        
                          <h4 style="padding:20px;text-align: center;
    position: relative;
    width: 100%;">No Results Pending</h4>
                        
                   <?php }
                    
                    ?>

                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:../../partials/_footer.html -->
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
<!-- Plugin js for this page -->
<script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="vendors/select2/select2.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="js/file-upload.js"></script>
<script src="js/typeahead.js"></script>
<script src="js/select2.js"></script>
<!-- End custom js for this page-->

<script>
  function maxLengthCheck(object) {
    if (object.value.length > object.max.length)
      object.value = object.value.slice(0, object.max.length)
  }
    
  function isNumeric (evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode (key);
    var regex = /[0-9]|\./;
    if ( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }
    
</script>

</body>

</html>