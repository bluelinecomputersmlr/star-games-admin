<?php
include "../connection/config.php";
extract($_REQUEST);


if(strlen($mobile) != 10){
     $data['success'] = "0";
   $data['msg'] = "You are not authorized to use this";

     echo json_encode($data);
     return;
}

 $dd = query("select session,active from users where mobile='$mobile'");
$d = fetch($dd);
 if($d['session'] != $_REQUEST['session']){
    $data['success'] = "0";
   $data['msg'] = "You are not authorized to use this";
    $data['session0'] = $d['session'];
    $data['session1'] = $_REQUEST['session'];

     echo json_encode($data);
     return;
 }

 $order_id = md5($mobile.$amount.$d['session']);

  $hash = openssl_encrypt($order_id, "AES-128-ECB", $_REQUEST['hash_key']);

 $get_data = fetch(query("select * from gateway_temp where hash='$hash' AND user='$mobile'"));


$amount = $get_data['amount'];

if(rows(query("select sn from settings where data_key='upi_verify' AND data='1'")) > 0 && $get_data['type'] == "paytm"){
query("INSERT INTO `upi_verification`( `user`, `amount`, `created_at`) VALUES ('$mobile','$amount','$stamp')");
query("update users set wallet=wallet+'$amount' where mobile='$mobile'");
} else {
query("update users set wallet=wallet+'$amount' where mobile='$mobile'");

query("INSERT INTO `transactions`( `user`, `amount`, `type`, `remark`, `owner`, `created_at`,`in_type`) VALUES ('$mobile','$amount','1','Deposit','user','$stamp','".$get_data['type']."')");
}
$data['success'] = "1";


echo json_encode($data);