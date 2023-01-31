<?php
include "../connection/config.php";
extract($_REQUEST);


$get_results = query("SELECT * FROM `manual_market_results` where market='$market'");
while($result = fetch($get_results)){
    
    $item['date'] = $result['date'];
    if(strlen($result['open']) < 1){
        $result['open'] = "*";
    }
    if(strlen($result['close']) < 1){
        $result['close'] = "*";
    }
    $item['jodi'] = $result['open'].$result['close'];
    $item['open_panna'] = $result['open_panna'];
    $item['close_panna'] = $result['close_panna'];
    
    $data['data'][] = $item;
}

echo json_encode($data);