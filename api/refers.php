<?php
include "../connection/config.php";
extract($_REQUEST);

$get_code = fetch(query("select * from users where mobile='$mobile'"));
$code = $get_code['code'];

$get_refer = query("select * from refers where code='$code' order by sn desc");

while($refer = fetch($get_refer)){
    $user = $refer['user'];
    
    $get_user = fetch(query("select name from users where mobile='$user'"));
    
    $refer['name'] = $get_user['name'];
    $refer['date'] = date('d/m/Y h:i A',$get_user['created_at']);
    
    $data['refer'][] = $refer;
}


$get_transaction = query("select * from refer_earning where user='$mobile'");
$total = "0";
while($refer = fetch($get_transaction)){
    
    $refer['date'] = date('d/m/Y h:i A',$refer['created_at']);
    
    $total += $refer['amount'];
    
    $data['transaction'][] = $refer;
}


$data['total_amount'] = $total;

echo json_encode($data);