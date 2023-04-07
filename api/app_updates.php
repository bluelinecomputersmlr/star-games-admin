<?php

include "../connection/config.php";

$sql="SELECT app_updates_new.appVersion, app_updates_new.appName,app_updates_new.isLive FROM `app_updates_new` ORDER BY app_updates_new.id DESC LIMIT 1";

$query = query($sql);

$someArray = [];

while ($row = fetch($query)) {

  array_push($someArray,[

  'appVersion'=>$row['appVersion'],

  'appName'=>$row['appName'],

  'isLive'=>$row['isLive']

]);

}

echo json_encode(array("result"=>$someArray));

?>