<?php
include "../connection/config.php";
extract($_REQUEST);

$gatConfig = query("select * from settings");

while($g = fetch($gatConfig)){
    $data['data'][] = $g;
}


$check = query("SELECT * FROM `app_updates` ORDER BY sn DESC;");
if(rows($check) > 0){
    
    $info = fetch($check);
    // $data['update_link'] = $info['link'];
    $data['update_log'] = $info['log'];
    $data['version'] = $info['version'];
    $data['update'] = "1";
} else {
    
    $data['update'] = "0";
}

if(isset($_REQUEST['mobile'])){
    if($mobile != ""){
        query("update users set f_token='$token' where mobile='$mobile'");
    }
}


// delete line below this after play store approval
//$data['redirect'] = "1";

echo json_encode($data);