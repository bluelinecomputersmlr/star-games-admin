<?php
include "../connection/config.php";
extract($_REQUEST);

// $sx = query("SELECT * FROM `transactions` where user='$mobile' order by created_at desc");
$sx = query("SELECT * FROM `transactions` INNER JOIN `games` ON transactions.game_id = games.sn where transactions.user='$mobile' order by transactions.created_at desc;");
$data=[];
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