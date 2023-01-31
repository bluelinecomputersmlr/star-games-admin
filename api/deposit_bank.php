<?php
include "../connection/config.php";
extract($_REQUEST);

 $name = "img";
$path = "../admin/userupload/";

$file=$_FILES[$name]['name'];
$expfile = explode('.',$file);
$fileexptype=$expfile[count($expfile)-1];
date_default_timezone_set( constant("zone"));
$date = date('m/d/Yh:i:sa', time());
$rand=rand(10000,999999);
$encname=$date.$rand;
$filename=md5($encname).'.'.$fileexptype;
$filepath=$path.$filename;
move_uploaded_file($_FILES[$name]["tmp_name"],$filepath);

$fileurl = "userupload/".$filename;

$get_user = query("select wallet from users where mobile='$mobile' AND wallet >= $amount");

if(rows($get_user) > 0){
    
    // query("update users set wallet=wallet+".$get['amount']." where mobile='".$get['user']."' ");
    
    // query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `owner`, `created_at`, `game_id`, `batch_id`, `in_type`, `wallet_type`) VALUES ('$mobile','$amount','1','Deposit money with Bank','user','$stamp','','','BANK','')");
    
    
    query("INSERT INTO `deposit_request`( `user`, `image`, `amount`, `status`, `created_at`) VALUES ('$mobile','$fileurl','$amount','0','$stamp')");
    
    $data['success'] = "1";
    $data['msg'] = "Deposit request recevied successfully";

} else {
        
    $data['success'] = "1";
    $data['msg'] = "You don't have enough wallet balance";
}

echo json_encode($data);