<?php
include "../connection/config.php";
extract($_REQUEST);

$get_code = query("select * from users where code='$code'");

if(rows($get_code) > 0){
    $data['success'] = "1";
} else {
    $data['success'] = "0";
    $data['msg'] = "Please enter a valid referral code";
}

echo json_encode($data);