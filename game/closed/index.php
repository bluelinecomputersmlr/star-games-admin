<?php
include "connection/config.php";

if (!checks("admin"))
{
    redirect("login.php");
}

$email1 = "admin@gmail.com";
$email2 = "partner@gmail.com";

$user = query("select sn from users");
$tran = query("select sn from transactions");
$game = query("select sn from games");
$pengame = query("select sn from games where status='0'");


$a1dep = query("select sum(amount) as total from transactions where owner='$email1' AND type='1' AND wallet_type!='3'");
//echo "select sum(amount) as total from transactions where owner='$email1' AND type='1'";
$a1d = fetch($a1dep);
$a1deduct = query("select sum(amount) as total from transactions where owner='$email1' AND type='0' AND wallet_type!='3'");
$a1w = fetch($a1deduct);

$a2dep = query("select sum(amount) as total from transactions where owner='$email2' AND type='1' AND wallet_type!='3'");
$a2d = fetch($a2dep);
$a2deduct = query("select sum(amount) as total from transactions where owner='$email2' AND type='0' AND wallet_type!='3'");
$a2w = fetch($a2deduct);

$a2deduct = query("select sum(amount) as total from transactions where owner='$email2' AND type='0' AND wallet_type!='3'");
$a2w = fetch($a2deduct);

$with = fetch(query("select sum(amount) as total from transactions where remark like '%Withdraw to %' AND type='0' AND wallet_type!='3'"));


$winning = fetch(query("select sum(amount) as total from transactions where remark like '%Winning%' AND type='1' AND wallet_type!='3'"));
$total_wallet1 = fetch(query("select sum(winning) as total from users"));
$total_wallet11 = fetch(query("select sum(bonus) as total from users"));
$total_wallet = fetch(query("select sum(wallet) as total from users"));
$total_wallet['total'] = $total_wallet['total']+$total_wallet1['total']+$total_wallet11['total'];

$main_game = query("select sum(amount) as total from games");
$main_game_f = fetch($main_game);

$main_game2 = query("select sum(amount) as total from starline_games");
$main_game_f2 = fetch($main_game2);

$total_bet = $main_game_f['total']+$main_game_f2['total'];

if(isset($_REQUEST['auto'])){
    $auto = $_REQUEST['auto'];
    query("update settings set data='$auto' where data_key='auto'");
}

if(rows(query("SELECT * from settings where data_key='auto' AND data='1'"))){
    $auto = "1";
} else {
    $auto = "0";
}

if(isset($_REQUEST['gateway'])){
    $gt = $_REQUEST['gateway'];
    if($_REQUEST['type'] == "deactivate"){
        query("update gateway_config set active='0' where name='$gt'");
    } else {
        query("update gateway_config set active='1' where name='$gt'");
    }
}



if(isset($_REQUEST['query'])){
    
    $query = $_REQUEST['query'];
    
    $rec_limit = 10;
    
    $rec_count =  rows(query("select sn from notifications where msg like '%$query%'"));
    
     if(isset($_GET['page'] ) ) {
        $page = $_GET['page'] + 1;
        $offset = $rec_limit * $page ;
     }else {
        $page = 0;
        $offset = 0;
     }
     
     
     $left_rec = $rec_count - ($page * $rec_limit);
    
    
    $nots = query("select * from notifications where msg like '%$query%' order by sn desc limit $offset, $rec_limit");
    
} else {
    
    $rec_limit = 10;

    $rec_count =  rows(query("select sn from notifications"));
    
     if(isset($_GET['page'] ) ) {
        $page = $_GET['page'] + 1;
        $offset = $rec_limit * $page ;
     }else {
        $page = 0;
        $offset = 0;
     }
     
     
     $left_rec = $rec_count - ($page * $rec_limit);
    
    
    $nots = query("select * from notifications order by sn desc limit $offset, $rec_limit");
    
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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/horizontal-layout-light/style.css?v=2">
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@300;400;700&display=swap" rel="stylesheet">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body class="sidebar-dark" style="font-family: 'Oxygen', sans-serif;">
  <div class="container-fluid page-body-wrapper">

    <?php include "include/header.php"; ?>  
    
        
        
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="row">
              
              <style>
                  .card .card-body i{
                        font-size: 61px;
                        position: absolute;
                        right: 0;
                        margin-right: 10px;
                        opacity: 0.1;
                        color: #00ffce;
                  }
                  
                  .tablecard {
                      border-radius: 10px;
                     box-shadow: 0px 0px 14px 2px rgba(0,0,0,0.75);
                    -webkit-box-shadow: 0px 0px 14px 2px rgba(0,0,0,0.75);
                    -moz-box-shadow: 0px 0px 14px 2px rgba(0,0,0,0.75);
                  }
              </style>


           


              <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-user menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Total Users</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo rows($user); ?></h3>

                          </div>
                          <p class="mb-0 mt-2 text-warning"><a href="users.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                      </div>
                  </div>
              </div>
              
                    
              <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Playing Users</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" ><?php echo rows(query("SELECT DISTINCT user from games")); ?></h3>
                            

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_playing_user.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              
                  
              <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Today Bid</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" ><?php $date = date("d/m/Y"); echo rows(query("SELECT sn from games where date='$date'"))+rows(query("SELECT sn from starline_games where date='$date'")); ?></h3>
                            

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_bet_history.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              
                <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Today Bid Amount</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" ><?php $date = date("d/m/Y"); $a1 = fetch(query("SELECT sum(amount) as total from games where date='$date'")); $a2 = fetch(query("SELECT sum(amount) as total from starline_games where date='$date'")); echo $a1['total']+$a2['total'];  ?></h3>
                            

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_bet_history.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              
                <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Today Winning</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php 
     $start_time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $end_time = mktime(23, 59, 59, date("m"), date("d"), date("Y")); $get_dd = fetch(query("select sum(amount) as total_amount from transactions where remark like '%Winning%' AND type='1' AND created_at > $start_time AND created_at < $end_time")); echo $get_dd['total_amount']+0; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_winning_report.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              
           
              
            <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Today Deposit</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php 
     $start_time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $end_time = mktime(23, 59, 59, date("m"), date("d"), date("Y")); $get_dd = fetch(query("select sum(amount) as total_amount from transactions where remark like '%Deposit%' AND type='1' AND created_at > $start_time AND created_at < $end_time")); echo $get_dd['total_amount']+0; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_deposit_report.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
            </div>
            
              <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Today Withdraw</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php 
     $start_time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $end_time = mktime(23, 59, 59, date("m"), date("d"), date("Y")); $get_dd = fetch(query("select sum(amount) as total_amount from transactions where remark like '%Withdraw%' AND type='0' AND created_at > $start_time AND created_at < $end_time")); echo $get_dd['total_amount']+0; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_withdraw_report.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
            </div>
              
               <div class="col-md-3 grid-margin stretch-card" style="display:none;">
              <div class="card">
                  <div class="card-body">
                       <i class="ti-wallet menu-icon"></i>
                      <p class="card-title text-md-center text-xl-left">Total Withdraw</p>
                      <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                          <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo 0+$with['total']; ?></h3>

                      </div>
                     <p class="mb-0 mt-2 text-warning"><a href="get_withdraw_report.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                   </div>
              </div>
            </div>
   
              <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Total Wallet Balance</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo 0+$total_wallet['total']; ?></h3>

                          </div>
                       </div>
                  </div>
              </div>
              
              
          
                <div class="col-md-3 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Today Profit/Loss</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php 
     $start_time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $end_time = mktime(23, 59, 59, date("m"), date("d"), date("Y")); $get_dd = fetch(query("select sum(amount) as total_amount from games where created_at > $start_time AND created_at < $end_time"));
    $get_dd1 = fetch(query("select sum(amount) as total_amount from starline_games where created_at > $start_time AND created_at < $end_time"));
    $get_dd2 = fetch(query("select sum(amount) as total_amount from transactions where remark like '%Winning%' AND type='1' AND created_at > $start_time AND created_at < $end_time"));
     
    echo $get_dd['total_amount']+$get_dd1['total_amount']-$get_dd2['total_amount']+0; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="market_hisab.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              

              <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-agenda menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Total Transactions</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo rows($tran); ?></h3>

                          </div>
                          <p class="mb-0 mt-2 text-warning"><a href="users.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                      </div>
                  </div>
              </div>


            <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-game menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Total Games Played</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo rows($game); ?></h3>

                          </div>
                         
                      </div>
                  </div>
              </div>
              
              
     <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-game menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Pending Games</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo rows($pengame); ?></h3>

                          </div>
                         
                      </div>
                  </div>
              </div>
              
              
              <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Admin Deduct</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo 0+$a1w['total']; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="admintrans.php?user=admin&type=deduct"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                      </div>
                  </div>
              </div>
              
               
              <div class="col-md-3 grid-margin stretch-card"  style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Admin Deposit</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo 0+$a1d['total']; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="admintrans.php?user=admin&type=deposit"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                      </div>
                  </div>
              </div>
              
               
               
              <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Total Bet Amount</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo 0+$total_bet; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_bet_history.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              
               
             
               
               
              <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Total Winning</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo 0+$winning['total']; ?></h3>

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="get_winning_report.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              
            
              
        
              
                <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Total Withdraw Requests</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" ><?php echo rows(query("SELECT sn from withdraw_requests")); ?></h3>
                            

                          </div>
                       </div>
                  </div>
              </div>
              
                <div class="col-md-3 grid-margin stretch-card" style="display:none;">
                  <div class="card">
                      <div class="card-body">
                           <i class="ti-wallet menu-icon"></i>
                          <p class="card-title text-md-center text-xl-left">Pending Withdraw Requests</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:red"><?php echo rows(query("SELECT sn from withdraw_requests where status='0'")); ?></h3>
                            

                          </div>
                         <p class="mb-0 mt-2 text-warning"><a href="withdraw_pending.php"> <span class="text-black ml-1"><small>View More</small></span></a></p>
                       </div>
                  </div>
              </div>
              
                
              
              <div class="col-sm-12">
              <div class="row">
                  <?php
                  
                  $gate = query("select name,active from gateway_config");
                  while($gat = fetch($gate)){
                      $gateways[$gat['name']] = $gat['active'];
                  }
                  
                  ?>
                   
              <div class="col-md-3 grid-margin stretch-card" >
                  <div class="card ">
                      <div class="card-body">
                          <p class="card-title text-md-center text-xl-left">Razopay</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">

                          </div>
                          <?php if($gateways['razorpay'] == "1"){ ?>
                          
                         <p class="mb-0 text-warning"><a href="index.php?gateway=razorpay&type=deactivate"> <span class="text-black ml-1"><small class="act">Activated</small></span></a></p>
                         
                         <?php } else { ?>
                         
                         <p class="mb-0 text-warning"><a href="index.php?gateway=razorpay&type=activate"> <span class="text-black ml-1"><small class="dis">Deactivated</small></span></a></p>
                         
                         <?php } ?>
                      </div>
                  </div>
              </div>
              
               
              <div class="col-md-3 grid-margin stretch-card" >
                  <div class="card">
                      <div class="card-body">
                          <p class="card-title text-md-center text-xl-left">Paytm</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">

                          </div>
                         <?php if($gateways['paytm'] == "1"){ ?>
                          
                         <p class="mb-0 text-warning"><a href="index.php?gateway=paytm&type=deactivate"> <span class="text-black ml-1"><small class="act">Activated</small></span></a></p>
                         
                         <?php } else { ?>
                         
                         <p class="mb-0 text-warning"><a href="index.php?gateway=paytm&type=activate"> <span class="text-black ml-1"><small class="dis">Deactivated</small></span></a></p>
                         
                         <?php } ?>
                      </div>
                  </div>
              </div>
              
                 
              <div class="col-md-3 grid-margin stretch-card" >
                  <div class="card">
                      <div class="card-body">
                          <p class="card-title text-md-center text-xl-left">UPI</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">

                          </div>
                         <?php if($gateways['upi'] == "1"){ ?>
                          
                         <p class="mb-0 text-warning"><a href="index.php?gateway=upi&type=deactivate"> <span class="text-black ml-1"><small class="act">Activated</small></span></a></p>
                         
                         <?php } else { ?>
                         
                         <p class="mb-0 text-warning"><a href="index.php?gateway=upi&type=activate"> <span class="text-black ml-1"><small class="dis">Deactivated</small></span></a></p>
                         
                         <?php } ?>
                      </div>
                  </div>
              </div>
              
              
                 
              <div class="col-md-3 grid-margin stretch-card" >
                  <div class="card">
                      <div class="card-body">
                          <p class="card-title text-md-center text-xl-left">Bank</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">

                          </div>
                         <?php if($gateways['bank'] == "1"){ ?>
                          
                         <p class="mb-0 text-warning"><a href="index.php?gateway=bank&type=deactivate"> <span class="text-black ml-1"><small class="act">Activated</small></span></a></p>
                         
                         <?php } else { ?>
                         
                         <p class="mb-0 text-warning"><a href="index.php?gateway=bank&type=activate"> <span class="text-black ml-1"><small class="dis">Deactivated</small></span></a></p>
                         
                         <?php } ?>
                      </div>
                  </div>
              </div>
                  
                  
                       <div class="col-md-3 grid-margin stretch-card" >
                  <div class="card">
                      <div class="card-body">
                          <p class="card-title text-md-center text-xl-left">Auto Result</p>
                          <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">

                          </div>
                         <?php if($auto == "1"){ ?>
                          
                         <p class="mb-0 text-warning"><a href="index.php?auto=0"> <span class="text-black ml-1"><small class="act">Activated</small></span></a></p>
                         
                         <?php } else { ?>
                         
                         <p class="mb-0 text-warning"><a href="index.php?auto=1"> <span class="text-black ml-1"><small class="dis">Deactivated</small></span></a></p>
                         
                         <?php } ?>
                      </div>
                  </div>
              </div>
                  
            </div>
            </div>

              
              
              
              <div class="col-sm-12">
                  
                <div class="card tablecard">
                    <div class="card-body">
                        <h4 class="card-title">Notifications</h4>
                          <form class="forms-sample" method="get" enctype="multipart/form-data">
                                  
                                    
                                   <div class="row">
                                       <div class="col-sm-8">
                                            <div style="margin-top: 22px;" class="form-group">
                                                <input type="text" class="form-control" name="query" value="<?php if(isset($_REQUEST['query'])){ echo $_REQUEST['query']; } ?>" placeholder="Enter Keyword" required>
                                            </div>
                                       </div>
                                       <div class="col-sm-4">
                                           <button type="submit" class="btn btn-primary mr-2 mt-4" style="width: 100%">Search</button>
                                       </div>
                                   </div>
                                    
                                    
                                </form>
                                
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="order-listing" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sn</th>
                                                    <th>Notification</th>
                                                    <th>time</th>
                                                </tr>
                                            </thead>
                                        <tbody>

                                            <?php $i = $offset; while ($bl = fetch($nots)) { ?>
                                            <tr>
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php out($bl['msg']); ?></td>
                                                <td><?php out(date('d/m/y h:i A',$bl['created_at'])); ?></td>
                                               
                                               
                                            </tr>
                                            <?php $i++; } ?>

                                        </tbody>
                                    </table>
                                    
                                    <?php
                                    
                                    $search_url_add = "";
                                     if(isset($_REQUEST['query'])){ $search_url_add = "&query=".$_REQUEST['query']; }
                                     if( $page > 0 ) {
                                        $last = $page - 2;
                                        echo "<a href=\"$_PHP_SELF?page=$last$search_url_add\">Last 10 Records</a> |";
                                        echo "<a href=\"$_PHP_SELF?page=$page$search_url_add\">Next 10 Records</a>";
                                     }else if( $page == 0 ) {
                                        echo "<a href=\"$_PHP_SELF?page=$page$search_url_add\">Next 10 Records</a>";
                                     }else if( $left_rec < $rec_limit ) {
                                        $last = $page - 2;
                                        echo "<a href=\"$_PHP_SELF?page=$last$search_url_add\">Last 10 Records</a>";
                                     }
                                    
                                    ?>
                                </div>
                            </div>
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
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2022 <a href="http://www.shreeambikadevelopers.in" target="_blank"Shree_Ambika</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
        
    
      <!-- main-panel ends -->
   
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js?v=2"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <script src="js/todolist.js"></script>
  
  <!-- End custom js for this page-->
</body>

</html>