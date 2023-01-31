<?php
include "../connection/config.php";
extract($_REQUEST);

$sx = query("SELECT * FROM `withdraw_options` where active='1' order by name");
while($x = fetch($sx))
{
    $data['data'][] = $x;
}


if(rows(query("select sn from bank_details where user='$mobile'")) > 0){
    
    $data['is_bank'] = "1";
   $data['bank'] = fetch(query("select * from bank_details where user='$mobile'"));
    
} else {
    
    $data['is_bank'] = "0";
    
}


echo json_encode($data);