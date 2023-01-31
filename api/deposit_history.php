<?php
include "../connection/config.php";
extract($_REQUEST);

$sx = query("SELECT * FROM `transactions` where user='$mobile' AND in_type!='' order by created_at desc");
while($x = fetch($sx))
{
    $x['type'] = $x['in_type'];
    
    $x['remark'] = str_replace("_"," ",$x['remark']);
    $x['remark'] = str_replace("OPEN"," ",$x['remark']);
    $x['remark'] = str_replace("CLOSE"," ",$x['remark']);
    
    $x['date'] = date('d/m/y',$x['created_at']);
    $data['data'][] = $x;
}

echo json_encode($data);