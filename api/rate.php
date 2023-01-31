<?php
include "../connection/config.php";
extract($_REQUEST);

$sx = query("SELECT * FROM `rates` where sn='1'");
$x = fetch($sx);

$sx = query("SELECT * FROM `rates_star` where sn='1'");
$x2 = fetch($sx);

$xs = $x;

$xs['single'] = "10 KA ".$x['single']*"10";
$xs['jodi'] = "10 KA  ".$x['jodi']*"10";
$xs['singlepatti'] = "10 KA  ".$x['singlepatti']*"10";
$xs['doublepatti'] = "10 KA ".$x['doublepatti']*"10";
$xs['triplepatti'] = "10 KA ".$x['triplepatti']*"10";
$xs['halfsangam'] = "10 KA  ".$x['halfsangam']*"10";
$xs['fullsangam'] = "10 KA  ".$x['fullsangam']*"10";

$data = $xs;

echo json_encode($data);