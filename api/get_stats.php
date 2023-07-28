<?php
include "../connection/config.php";
extract($_REQUEST);


$m0 = str_replace(" ","_",$market.' OPEN');
$m1 = str_replace(" ","_",$market.' CLOSE');
$m2 = str_replace(" ","_",$market);

$date = date("d/m/Y");

$data = [];

$get_total = fetch((query("SELECT count(sn) as counting FROM `games` where bazar='$m0' AND game='single' AND date='$date'")));
$total = $get_total['counting'];

$sx = query("SELECT *,count(*) as counting FROM `games` where bazar='$m0' AND game='single' AND date='$date' group by number order by number");

while($x = fetch($sx))
{
    $row['number'] = $x['number'];
    $row['stats'] = round($x['counting']/$total*100);
    
    
    $data['open'][] = $row;
    
}


$get_total = fetch((query("SELECT count(sn) as counting FROM `games` where bazar='$m1' AND game='single' AND date='$date'")));
$total = $get_total['counting'];

$sx = query("SELECT *,count(*) as counting FROM `games` where bazar='$m1' AND game='single' AND date='$date' group by number order by number");

while($x = fetch($sx))
{
    
    $row['number'] = $x['number'];
    $row['stats'] = round($x['counting']/$total*100);
    
    
    $data['close'][] = $row;
    
}



$get_total = fetch((query("SELECT count(sn) as counting FROM `games` where bazar='$m2' AND game='jodi' AND date='$date'")));
$total = $get_total['counting'];

$sx = query("SELECT *,count(*) as counting FROM `games` where bazar='$m2' AND game='jodi' AND date='$date' group by number order by number");

while($x = fetch($sx))
{
    
    $row['number'] = $x['number'];
    $row['stats'] = round($x['counting']/$total*100);
    
    
    $data['jodi'][] = $row;
    
}


echo json_encode($data);