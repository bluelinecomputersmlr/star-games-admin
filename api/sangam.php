<?php
include "../connection/config.php";
extract($_REQUEST);
$date = date('d/m/Y');


$check = query("select wallet,winning,bonus from users where mobile='$mobile'");

$check_wallet = fetch($check);

$wallet = $total;

function generateUniqueID()
{
    $characters = '0123456789';
    $uniqueID = '';

    for ($i = 0; $i < 6; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $uniqueID .= $characters[$index];
    }

    return $uniqueID;
}
            
if($check_wallet['bonus'] > 0){
    
  //  $bonus_allowed = $amoun*10/100;
     $bonus_allowed = $amoun;
    
    if($check_wallet['bonus'] < $bonus_allowed){
        $bonus = $check_wallet['bonus'];
    } else {
        $bonus = $bonus_allowed;
    }
    
    $wallet = $amoun - $bonus;
    
} else {
    $bonus = 0;
}

if(($check_wallet['wallet']+$check_wallet['winning']) < $wallet){
     
    $data['success'] = "0";
    $data['msg'] = "You don't have enough wallet balance to place this bet";
    echo json_encode($data);
    return;
    
} else {
    
    if((int)$check_wallet['wallet'] < (int)$wallet){
        $deposit = $check_wallet['wallet'];
        
        $wallet = $wallet - $deposit;
        
        $winning = $wallet;
        
    } else {
        $deposit = $wallet;
    }
    
}

query("update users set wallet=wallet-'$deposit', winning=winning-'$winning', bonus=bonus-'$bonus' where mobile='$mobile'");
    
$uniqueID = generateUniqueID();

query("INSERT INTO `games`(`user`, `game`, `bazar`, `date`, `number`, `amount`, `created_at`,`bid_id`) VALUES ('$mobile','$game','$bazar','$date','$number','$amount','$stamp','$uniqueID')");

query("INSERT INTO `single_games`(`user`, `game`, `bazar`, `date`, `number`, `amount`, `created_at`, `wallet_type`,`bid_id`) VALUES ('$mobile','$game','$bazar','$date','$number','$amount','$stamp','1','')");

$game_data = fetch(query("SELECT sn FROM `single_games` WHERE `user`='$mobile' AND `game`='$game' AND `bazar` = '$bazar' AND `date`='$date' AND `number`='$number' AND `amount`='$amount' AND `created_at`='$stamp'"));

$game_id = $game_data["sn"];

query("INSERT INTO `transactions`( `user`, `amount`, `type`, `remark`, `owner`,`game_id` ,`created_at`,`in_type`) VALUES ('$mobile','$amount','3','Bet placed','user','$game_id','$stamp','0')");


$data['success'] = "1";

echo json_encode($data);