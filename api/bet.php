<?php
include "../connection/config.php";
extract($_REQUEST);

if (strlen($mobile) != 10) {
    $data['success'] = "0";
    $data['msg'] = "You are not authorized to use this";

    echo json_encode($data);
    return;
}

if (rows(query("select sn from users where mobile='$mobile' and session ='$session'")) == 0) {
    $data['msg'] = "You are not authrized to use this";

    $dd = query("select session,active,name from users where mobile='$mobile'");
    $d = fetch($dd);
    $data['session'] = $d['session'];
    $data['active'] = $d['active'];

    echo json_encode($data);
    return;
} else {

    $dd = query("select session,active,name from users where mobile='$mobile'");
    $d = fetch($dd);
    $data['session'] = $d['session'];
    $data['active'] = "1";
}

$time = date("H:i", $stamp);
$day = strtoupper(date("l", $stamp));

$nm = explode(",", $number);
$am = explode(",", $amount);
if (isset($_REQUEST['games'])) {
    $gm = explode(",", $games);
}

if (isset($_REQUEST['types'])) {
    $type = explode(",", $types);
}


$_bazar = str_replace("_OPEN", "", $bazar);
$_bazar = str_replace("_CLOSE", "", $_bazar);
$_bazar = str_replace("_", " ", $_bazar);

$date = date('d/m/Y');


$get_mrkt = query("select * from gametime_new where market='$_bazar'");

if (rows($get_mrkt) == 0) {
    $get_mrkt = query("select * from gametime_manual where market='$_bazar'");

    if (rows($get_mrkt) == 0) {

        $get_mrkt = query("select * from gametime_delhi where market='$_bazar'");

        if (rows($get_mrkt) == 0) {

            $data['success'] = "0";
            $data['msg'] = "We are not able to get market details, Please restart application and try again";
            echo json_encode($data);
            return;
        }
    }
}

$xc = fetch($get_mrkt);

if ($xc['days'] == "ALL" || substr_count($xc['days'], $day) == 0) {


    if (strtotime($time) < strtotime($xc['open'])) {
        $xc['is_open'] = "1";
    } else {
        $xc['is_open'] = "0";
    }

    if (strtotime($time) < strtotime($xc['close'])) {
        $xc['is_close'] = "1";
    } else {
        $xc['is_close'] = "0";
    }
} else if (substr_count($xc['days'], $day . "(CLOSE)") > 0) {


    $data['success'] = "0";
    $data['msg'] = "Market already closeed, Try again later";
    echo json_encode($data);
    return;
} else {
    $time_array = explode(",", $xc['days']);
    for ($i = 0; $i < count($time_array); $i++) {
        if (substr_count($time_array[$i], $day) > 0) {
            $day_conf = $time_array[$i];
        }
    }

    $day_conf = str_replace($day . "(", "", $day_conf);
    $day_conf = str_replace(")", "", $day_conf);

    $mrk_time = explode("-", $day_conf);


    $xc['open'] = $mrk_time[0];
    $xc['close'] = $mrk_time[1];

    if (strtotime($time) < strtotime($mrk_time[0])) {
        $xc['is_open'] = "1";
    } else {
        $xc['is_open'] = "0";
    }

    if (strtotime($time) < strtotime($mrk_time[1])) {
        $xc['is_close'] = "1";
    } else {
        $xc['is_close'] = "0";
    }
}





$msg = "New bets game - " . $game . ", user-" . $mobile . ", bets - ";

for ($a = 0; $a < count($am); $a++) {

    $amoun = $am[$a];
    $numbe = $nm[$a];


    if (isset($_REQUEST['games'])) {
        $game = $gm[$a];
    }


    if (isset($_REQUEST['types'])) {
        if ($game == "jodi") {
            $bazar2 = $bazar;
        } else {
            $bazar2 = $bazar . "_" . $type[$a];
        }
    } else {
        $bazar2 = $bazar;
    }

    $bazar2 = str_replace(' ', "_", $bazar2);


    $check = query("select wallet,winning,bonus from users where mobile='$mobile'");


    $check_wallet = fetch($check);


    if (strpos($bazar2, 'OPEN') !== false) {
        if ($xc['is_open'] == "0") {
            $data['success'] = "0";
            $data['msg'] = "Market already closeed, Try again later";
            echo json_encode($data);
            return;
        }


        $chk_if_query = query("select * from manual_market_results where market='$bazar' AND date='$date'");
        if (rows($chk_if_query) > 0) {

            $data['success'] = "0";
            $data['msg'] = "Market already closeed, Try again later";
            echo json_encode($data);
            return;
        }
    } else if (strpos($bazar2, 'CLOSE') !== false) {

        if ($xc['is_close'] == "0") {
            $data['success'] = "0";
            $data['msg'] = "Market already closeed, Try again later";
            echo json_encode($data);
            return;
        }


        $chk_if_query = query("select * from manual_market_results where market='$bazar' AND date='$date'");
        if (rows($chk_if_query) > 0) {

            $chk_if_updated = fetch($chk_if_query);


            if ($chk_if_updated['close'] != '') {

                $data['success'] = "0";
                $data['msg'] = "Market already closeed, Try again later";
                echo json_encode($data);
                return;
            }
        }
    } else if ($game == "jodi" || $game == "halfsangam" || $game == "fullsangam") {

        if ($xc['is_open'] == "0") {
            $data['success'] = "0";
            $data['msg'] = "Market already closeed, Try again later";
            echo json_encode($data);
            return;
        }


        $chk_if_query = query("select * from manual_market_results where market='$bazar' AND date='$date'");
        if (rows($chk_if_query) > 0) {

            $data['success'] = "0";
            $data['msg'] = "Market already closeed, Try again later";
            echo json_encode($data);
            return;
        }
    }



    $wallet = $amoun;

    if ($check_wallet['bonus'] > 0) {

        //  $bonus_allowed = $amoun*10/100;

        $bonus_allowed = $amoun;


        if ($check_wallet['bonus'] < $bonus_allowed) {
            $bonus = $check_wallet['bonus'];
        } else {
            $bonus = $bonus_allowed;
        }

        $wallet = $amoun - $bonus;
    } else {
        $bonus = 0;
    }

    if (($check_wallet['wallet'] + $check_wallet['winning']) < $wallet) {

        $data['success'] = "0";
        $data['msg'] = "You don't have enough wallet balance to place this bets";
        echo json_encode($data);
        return;
    } else {

        if ((int) $check_wallet['wallet'] < (int) $wallet) {
            $deposit = $check_wallet['wallet'];

            $wallet = $wallet - $deposit;

            $winning = $wallet;
        } else {
            $deposit = $wallet;
        }
    }


    $ref_q = query("select code from refers where user='$mobile'");


    $data['cs'] = "select code from refers where user='$mobile'";

    if (rows($ref_q) > 0) {
        $ref_d = fetch($ref_q);
        $ref_code = $ref_d['code'];
        $ref_user = fetch(query("select name,mobile from users where code='$ref_code'"));


        $data['cs2'] = "select name,mobile from users where code='$ref_code'";

        $ref_mobile = $ref_user['mobile'];
        $get_ref_per = fetch(query("select data from settings where data_key='refer_play'"));
        $refer_per = $get_ref_per['data'];
        $ref_amount = round($amoun * $refer_per / 100);

        query("update users set wallet=wallet+$ref_amount where mobile='$ref_mobile' ");

        query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `owner`, `created_at`, `game_id`, `batch_id`, `in_type`, `wallet_type`) VALUES ('$ref_mobile','$ref_amount','1','Refer earning','user','$stamp','','','BANK','')");
        $remark = "Refer betting earning";
        query("INSERT INTO `refer_earning`(`user`, `action_user`, `amount`, `created_at`,`remark`) VALUES ('$ref_mobile','$mobile','$ref_amount','$stamp','$remark')");

        $data['cs3'] = "INSERT INTO `refer_earning`(`user`, `action_user`, `amount`, `created_at`,`remark`) VALUES ('$ref_mobile','$mobile','$ref_amount','$stamp','$remark')";
    }

    query("update users set wallet=wallet-'$deposit', winning=winning-'$winning', bonus=bonus-'$bonus' where mobile='$mobile'");




    $data['cc'][] = $check_wallet['wallet'] . '<' . $wallet;
    $data['wll'][] = "update users set wallet=wallet-'$deposit', winning=winning-'$winning', bonus=bonus-'$bonus' where mobile='$mobile'";


    $msg = $msg . "( Market - " . $bazar2 . " , Num-" . $numbe . " - " . $amoun . "INR )";

    query("INSERT INTO `games`(`user`, `game`, `bazar`, `date`, `number`, `amount`, `created_at`, `wallet_type`) VALUES ('$mobile','$game','$bazar2','$date','$numbe','$amoun','$stamp','1')");


    //  query("update users set wallet=wallet-'$amoun' where mobile='$mobile'");

    // query("INSERT INTO `games`(`user`, `game`, `bazar`, `date`, `number`, `amount`, `created_at`, `wallet_type`) VALUES ('$mobile','$game','$bazar2','$date','$numbe','$amoun','$stamp','1')");
    // $game_data = fetch(query("SELECT sn FROM `games` WHERE `user`='$mobile' AND `game`='$game' AND `bazar` = '$bazar2' AND `date`='$date' AND `number`='$numbe' AND `amount`='$amoun' AND `created_at`='$stamp'"));
    // $game_id = $game_data["sn"];
    // query("INSERT INTO `transactions`( `user`, `amount`, `type`, `remark`, `owner`,`game_id` ,`created_at`,`in_type`) VALUES ('$mobile','$amoun','3','Bet placed','user','$game_id','$stamp','0')");

    // $data['qq'] = "INSERT INTO `games`(`user`, `game`, `bazar`, `date`, `number`, `amount`, `created_at`, `wallet_type`) VALUES ('$mobile','$game','$bazar2','$date','$numbe','$amoun','$stamp','1')";
}
function generateUniqueID() {
    $characters = '0123456789';
    $uniqueID = '';
    
    for ($i = 0; $i < 6; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $uniqueID .= $characters[$index];
    }
    
    return $uniqueID;
}

$uniqueID = generateUniqueID();
query("INSERT INTO `single_games`(`user`, `game`, `bazar`, `date`, `number`, `amount`, `created_at`, `wallet_type`,`bid_id`) VALUES ('$mobile','$game','$bazar2','$date','$number','$amount','$stamp','1','$uniqueID')");
$game_data = fetch(query("SELECT sn FROM `single_games` WHERE `user`='$mobile' AND `game`='$game' AND `bazar` = '$bazar2' AND `date`='$date' AND `number`='$number' AND `amount`='$amount' AND `created_at`='$stamp'"));
$game_id = $game_data["sn"];
query("INSERT INTO `transactions`( `user`, `amount`, `type`, `remark`, `owner`,`game_id` ,`created_at`,`in_type`) VALUES ('$mobile','$amount','3','Bet placed','user','$game_id','$stamp','0')");

query("INSERT INTO `notifications`( `msg`, `created_at`) VALUES ('$msg','$stamp')");


$data['success'] = "1";






echo json_encode($data);
