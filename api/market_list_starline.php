<?php
include "../connection/config.php";
extract($_REQUEST);


$gatConfig = query("select * from starline_markets where active='1'");

while($g = fetch($gatConfig)){
    $data['data'][] = $g;
}


echo json_encode($data);