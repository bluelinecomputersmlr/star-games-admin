<?php
include "../connection/config.php";
extract($_REQUEST);


$xvm = query("select * from rate where sn='1'");
$xv = fetch($xvm);

//$_REQUEST['mobile']='mobile=8105661676_MAIN BAZAR';

$req_params = explode("_", $_REQUEST['mobile']);

//if (isset($_REQUEST['market'])) {
if (isset($req_params[1])&&$req_params[1]!="") {
    $market = $req_params[1];
    $mobile = $req_params[0];
    $m0 = str_replace(" ", "_", $market . ' OPEN');
    $m1 = str_replace(" ", "_", $market . ' CLOSE');
    $m2 = str_replace(" ", "_", $market);


    $sx = query("SELECT * FROM `games` where user='$mobile' AND (bazar='$m0' OR bazar='$m1' OR bazar='$m2') order by created_at desc");
} else {
    $mobile = $req_params[0];
    
    $sx = query("SELECT * FROM `games` where user='$mobile' order by created_at desc");
}

$data = [];
// $data['dates'][] = array();
while ($x = fetch($sx)) {
    $x['date'] = date('d M Y h:i A', $x['created_at']);
    $date = date('d M Y', $x['created_at']);

    if ($data["dates"] ?? null) {
        if (!in_array($date, $data['dates'])) {
            $data['dates'][] = $date;
        }
    } else {
        $data['dates'][] = $date;
    }

    $m02 = str_replace('_OPEN', "", $x['bazar']);
    $m12 = str_replace('_CLOSE', "", $m02);
    $m22 = str_replace("_", " ", $m12);


    $x['market'] = $m22;


    if ($x['status'] == "1") {
        $x['msg'] = "Congratulations You Won ₹" . ($x['amount'] * $xv[$x['game']]);
    } else if ($x['is_loss'] == "1") {
        $x['msg'] = "Better Luck Next Time";
        $x['status'] = "2";
    } else {
        $x['msg'] = "Result Pending";
    }


    if (strpos($x['bazar'], "OPEN") !== false) {
        $x['game'] = $x['game'] . ' Open';
    } else if (strpos($x['bazar'], "CLOSE") !== false) {
        $x['game'] = $x['game'] . ' Close';
    }

    // $val_date = date('d M Y', $x['created_at']);

    // $key_val = "dates_".$val_date;

    // $data[$key_val][] = $x;
    $data[date('d M Y', $x['created_at'])][] = $x;
}

echo json_encode($data);
