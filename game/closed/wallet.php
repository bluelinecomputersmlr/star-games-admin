<?php
include "connection/config.php";
if (!checks("admin"))
{
    redirect("login.php");
}

$user = $_REQUEST['user'];



if(isset($_REQUEST['submit']))
{
    verification();
    extract($_REQUEST);
    
    
    
    $owner = gets("email");
    
    if($type=="0")
    {
        query("update users set $wallet=$wallet-'$amount' where mobile='$user'");
        $title = $amount.' deducted from your wallet';
        
        $remark = "Withdraw";
    }
    else
    {
        query("update users set $wallet=$wallet+'$amount' where mobile='$user'");
        $title = $amount.' added to your wallet';
        
        if(isset($give_ref) && $give_ref == "on"){
            
            $commision = (int)$amount/10;
            query("update users set bonus=bonus+'$commision' where mobile='$ref_mobile'");
        }
        
        $remark = "Deposit";
    }
    
    if($wallet == "wallet"){
        $wallet_type = '1';
    } else if($wallet == "winning"){
        $wallet_type = '2';
    } else {
        $wallet_type = '3';
    }
    
    sendNotiicaton($remark,$title,$user);
    
    query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at`,`owner`,`wallet_type`) VALUES ('$user','$amount','$type','$remark','$stamp','$owner','$wallet_type')");
}

$blog = query("select * from transactions where user='$user'");

$us = query("select wallet from users where mobile='$user'");
$u = fetch($us);

$ref_q = query("select code from refers where user='$user'");
if(rows($ref_q) > 0){
    $ref_d = fetch($ref_q);
    $ref_code = $ref_d['code'];
    $ref_user = fetch(query("select name,mobile from users where code='$ref_code'"));
    
}

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
                        <h4 class="card-title">Manage Wallet ( Wallet Balance -  <?php echo $u['wallet']; ?> )</h4>
                         <form class="forms-sample" method="post" enctype="multipart/form-data">
                                    
                                    
                                     
                                    <div class="form-group" style="    margin-bottom: 10px !important">
                                        <label for="exampleInputName1">Amount</label>
                                        <input type="text" class="form-control" id="exampleInputName1" name="amount" placeholder="Enter amount">
                                    </div>
                                    
                                     <div class="form-group" style="    margin-bottom: 10px !important;display:none;">
                                        <label for="exampleInputName1">Remark</label>
                                        <input type="text" class="form-control" id="exampleInputName1" name="remark" placeholder="Enter Remark">
                                    </div>
                                    
                                    
                                    <div class="form-group" style="    margin-bottom: 10px !important">
                                        <label for="exampleFormControlSelect1">Select wallet type</label>
                                        <select class="form-control form-control-lg" name="wallet" id="exampleFormControlSelect1" onchange="ref_check(this.value)">
                                            
                                            <option value='wallet'>Deposit</option>
                                            <option value='winning'>Winning</option>
                                            <option value='bonus'>Bonus</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" style="    margin-bottom: 10px !important">
                                        <label for="exampleFormControlSelect1">Select transaction type</label>
                                        <select class="form-control form-control-lg" name="type" id="exampleFormControlSelect1" onchange="ref_check(this.value)">
                                            
                                            <option value='0'>Deduct</option>
                                            <option value='1'>Add</option>
                                        </select>
                                    </div>
                                    
                                    <?php if(isset($ref_user)){ ?>
                                    <div class="ref" id="ref_id">
                                        <p><?php echo $ref_user['name'].' ( '.$ref_user['mobile'].' )'; ?> will get 10% amount for this transaction</p>
                                        <span><input type="checkbox" name="give_ref" checked> Do you want to deposit reffereal bonus to this user</span>
                                        <input type="hidden" value="<?php echo $ref_user['mobile']; ?>" name="ref_mobile">
                                    </div>
                                    <?php } ?>
                                    
                                    <br>
                                    
                                    <button style="    margin-top: 0 !important" type="submit" class="btn btn-primary mr-2 mt-4" name="submit" style="width: 100%">Submit</button>
                                </form>
                                
                                <script>
                                    function ref_check(value){
                                        console.log("check")
                                        if($("#ref_id").length > 0) {;
                                            if(value === "1"){
                                               $("#ref_id").show();
                                            } else {
                                                $("#ref_id").hide();
                                            }
                                        }
                                        
                                    }
                                </script>
                                
                                 <h4 class="card-title" style="margin-top:20px;">Transaction history</h4>
                                 
                        <div class="row">
                            <div class="col-12">
                                
                                  
                                    
                               
                                
                                <div class="table-responsive">
                                    <table id="order-listing" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sn</th>
                                                    <th>Amount</th>
                                                    <th>Type</th>
                                                    <th>Remark</th>
                                                    <th>Created by</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                        <tbody>

                                            <?php $i = 1; while ($bl = fetch($blog)) {
                                            
                                            if($bl['owner']=="")
                                            {
                                                $owner = "User";
                                            }
                                            else if($bl['owner'] == "admin@gmail.com")
                                            {
                                                $owner = "Admin";
                                            }
                                            else
                                            {
                                                $owner= "Partner";
                                            }
                                            
                                            ?>
                                            <tr  <?php if($bl['wallet_type'] == "3"){ echo "class='rangeela'"; } ?>>
                                                <td><?php echo $i; ?></td>
                                                <td><?php out($bl['amount']); ?></td>
                                                <td><?php if($bl['type']=="0"){ echo "Deduct"; }else { echo "Added"; }  ?></td>
                                                <td><?php out($bl['remark']); ?></td>
                                                <td><?php out($owner); ?></td>
                                                <td><?php out(date('d/m/y h:i A',$bl['created_at'])); ?></td>
                                                
                                               
                                               
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
            <style>
                            .rangeela {
                                    background: #ffe8a4;
                            }
                        </style>
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
