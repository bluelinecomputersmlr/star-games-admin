<?php

include "../connection/config.php";

$sql = "SELECT app_updates_new.appVersion, app_updates_new.appName,app_updates_new.isLive FROM `app_updates_new` ORDER BY app_updates_new.id DESC LIMIT 1";

$query = query($sql);

$someArray = [];

while ($row = fetch($query)) {

  array_push($someArray, [

    'appVersion' => $row['appVersion'],

    'appName' => $row['appName'],

    'isLive' => $row['isLive']

  ]);

}

// Getting payment gateway data

$paymentSql = "SELECT gateway_config.name FROM `gateway_config` WHERE active = 1 LIMIT 1";

$paymetnQuery = query($paymentSql);

while ($paymentRow = fetch($paymetnQuery)) {
  $firstResult = $someArray[0];
  if ($paymentRow['name'] == "razorpay") {
    $firstResult['payment_type'] = 'gateway';
    $someArray = [];
    array_push($someArray, $firstResult);
  } else {
    $firstResult['payment_type'] = 'UPI';
    $someArray = [];
    array_push($someArray, $firstResult);
  }
}

echo json_encode(array("result" => $someArray));

?>