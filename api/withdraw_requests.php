<?php
include "../connection/config.php";
extract($_REQUEST);

$qq = query("select * from withdraw_requests where user='$mobile' order by sn desc");


while($q = fetch($qq)){
    
    if($q['status'] == "0"){
        $q['status'] = "Pending";
    } else if($q['status'] == "2"){
        $q['status'] = "Rejected";
    } else {
        $q['status'] = "Completed";
    }
    
    $q['date'] = date("h:i A d F Y",$q['created_at']);
    
    $data['data'][] = $q;
}

echo json_encode($data);