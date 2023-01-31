<?php
include "../connection/config.php";
extract($_REQUEST);


$get_results = query("SELECT date FROM `starline_results` where market='$market' group by date order by sn desc");
while($result = fetch($get_results)){
    
    if(isset($item)){
        unset($item);
    }
    
    $item['date'] = $result['date'];
    
    
    $get_results_ = query("SELECT panna,number FROM `starline_results` where market='$market' AND date='".$result['date']."'");
    while($result_ = fetch($get_results_)){
        
        $item['results'][] = $result_;
        
    }
    
    
    $data['data'][] = $item;
}

echo json_encode($data);