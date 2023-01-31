<?php
include "../connection/config.php";
extract($_REQUEST);



$sx = query("SELECT * FROM `rates_star` where sn='1'");
$x2 = fetch($sx);

$xs = $x;

$xs['single'] = "Starline - ".$x2['single']*"10";
$xs['singlepatti'] = "Starline - ".$x2['singlepatti']*"10";
$xs['doublepatti'] = "Starline - ".$x2['doublepatti']*"10";
$xs['triplepatti'] = "Starline - ".$x2['triplepatti']*"10";

$data = $xs;

echo json_encode($data);