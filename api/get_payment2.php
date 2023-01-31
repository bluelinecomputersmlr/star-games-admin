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

 //$data['qyery'] = "select * from gateway_temp where hash='$hash' AND user='$mobile'";

$amount = $get_data['amount'];

if($get_data['type'] != "paytm"){
query("update users set wallet=wallet+'$amount' where mobile='$mobile'");

query("INSERT INTO `transactions`( `user`, `amount`, `type`, `remark`, `owner`, `created_at`,`in_type`) VALUES ('$mobile','$amount','1','Deposit','user','$stamp','".$get_data['type']."')");
  
    $check_refer = query("select code from refers where user='$mobile' AND status='0'");
  if(rows($check_refer) > 0){
    $refer = fetch($check_refer);
    
    $code = $refer['code'];
    
    $get_refer_mobile = fetch(query("select mobile from users where code='$code'"));
    $refer_mobile = $get_refer_mobile['mobile'];
    
    
    $amount = $amount*10/100;
    query("update refers set status='1', amount='$amount' where user='$mobile' AND code='$code'");
	query("update users set wallet=wallet+'$amount' where mobile='$refer_mobile'");

	query("INSERT INTO `transactions`( `user`, `amount`, `type`, `remark`, `owner`, `created_at`) VALUES ('$refer_mobile','$amount','1','Refer earning','user','$stamp')");

    
    
$data['success'] = "1";
} 

}

else {
    
    query("INSERT INTO `upi_verification`( `user`, `amount`, `created_at`) VALUES  ('$mobile','$amount','$stamp')");

    
$data['success'] = "0";
  }
  

echo json_encode($data);
