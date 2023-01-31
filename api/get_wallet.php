<?php
include "../connection/config.php";
extract($_REQUEST);

$get_wallet = fetch(query("select wallet, winning, bonus from users where mobile='$mobile'"));


$get_wallet['total'] = $get_wallet['wallet']+$get_wallet['winning']+$get_wallet['bonus'];

if(rows(query("select active from gateway_config where name='paytm' AND active='1'")) > 0){
    $get_wallet['paytm'] = "1";
} else {
    $get_wallet['paytm'] = "0";
}

if(rows(query("select active from gateway_config where name='razorpay' AND active='1'")) > 0){
    $get_wallet['razorpay'] = "1";
} else {
    $get_wallet['razorpay'] = "0";
}

if(rows(query("select active from gateway_config where name='upi' AND active='1'")) > 0){
    $get_wallet['upi'] = "1";
} else {
    $get_wallet['upi'] = "0";
}


$get_bank = fetch(query("select data from settings where data_key='bank_details'"));
$get_wallet['bank_details'] = $get_bank['data'];


$sx = query("SELECT * FROM `transactions` where user='$mobile' AND (remark like '%deposit%' OR remark like '%withdraw%') order by created_at desc");
while($x = fetch($sx))
{
    if($x['type'] == "0")
    {
        $x['amount'] = '-'.$x['amount'];
    }
    $x['date'] = date('d/m/y',$x['created_at']);
    $get_wallet['data'][] = $x;
}


if(rows(query("select sn from bank_details where user='$mobile'")) > 0){
    
    $get_wallet['is_bank'] = "1";
   $get_wallet['bank'] = fetch(query("select * from bank_details where user='$mobile'"));
    
} else {
    
    $get_wallet['is_bank'] = "0";
    
}



echo json_encode($get_wallet);