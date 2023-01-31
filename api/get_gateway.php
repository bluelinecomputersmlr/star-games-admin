<?php
include "../connection/config.php";
extract($_REQUEST);

$gateway = query("select name from gateway_config where active='1'");

while($g = fetch($gateway)){
    $data['data'][] = $g;
}

echo json_encode($data);