<?php
include "../connection/config.php";
extract($_REQUEST);

$sx = query("SELECT sum(amount) as total, user FROM `transactions` where remark like '%Winning%'  group by user order by total desc limit 20");
$i = 1;
while($x = fetch($sx))
{
    
    $getUser = fetch(query("select name, created_at from users where mobile='".$x['user']."'"));
    
    $x['name'] = $getUser['name'];
    $x['since'] = date('F Y',$getUser['created_at']);
    $x['rank'] = $i;
    $x['amount'] = $x['total'];
    
    $data['data'][] = $x;
    $i++;
}

echo json_encode($data);