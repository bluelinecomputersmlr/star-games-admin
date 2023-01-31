<?php
include "../connection/config.php";
extract($_REQUEST);

$sx = query("SELECT * FROM `transactions` where user='$mobile' AND remark like '%Winning%' OR user='$mobile' AND remark='bet' order by created_at desc");
while($x = fetch($sx))
{
    if($x['type'] == "0")
    {
        $x['amount'] = '-'.$x['amount'];
    }
    $x['date'] = date('d/m/y',$x['created_at']);
    $data['data'][] = $x;
}

echo json_encode($data);