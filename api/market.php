<?php
include "../connection/config.php";
extract($_REQUEST);

$get = query("select * from gametime where sn='1'");
$xc = fetch($get);

$time = date("H:i",$stamp);

if(strtotime($time)<strtotime($xc['kalyanopen']))
{
    $data['kalyanopen'] = "1";
}
else
{
    $data['kalyanopen'] = "0";
}

if(strtotime($time)<strtotime($xc['kalyanclose']))
{
    $data['kalyanclose'] = "1";
}
else
{
    $data['kalyanclose'] = "0";
}


if(strtotime($time)<strtotime($xc['milanopen']))
{
    $data['milanopen'] = "1";
}
else
{
    $data['milanopen'] = "0";
}


if(strtotime($time)<strtotime($xc['milanclose']))
{
    $data['milanclose'] = "1";
}
else
{
    $data['milanclose'] = "0";
}


if(strtotime($time)<strtotime($xc['ratanopen']))
{
    $data['ratanopen'] = "1";
}
else
{
    $data['ratanopen'] = "0";
}


if(strtotime($time)<strtotime($xc['ratanclose']))
{
    $data['ratanclose'] = "1";
}
else
{
    $data['ratanclose'] = "0";
}


if(strtotime($time)<strtotime($xc['desaweropen']))
{
    $data['desaweropen'] = "1";
}
else
{
    $data['desaweropen'] = "0";
}


if(strtotime($time)<strtotime($xc['desawerclose']))
{
    $data['desawerclose'] = "1";
}
else
{
    $data['desawerclose'] = "0";
}

if(strtotime($time)<strtotime($xc['mainopen']))
{
    $data['mainopen'] = "1";
}
else
{
    $data['mainopen'] = "0";
}


if(strtotime($time)<strtotime($xc['mainclose']))
{
    $data['mainclose'] = "1";
}
else
{
    $data['mainclose'] = "0";
}


$sx = query("SELECT * FROM `gametime` where sn='1'");
$x = fetch($sx);
$data['data'] = $x;

echo json_encode($data);