<?php
include "../connection/config.php";
extract($_REQUEST);


query("update users set password='$pass' where mobile='$mobile'");

$data['success'] = "1";
$data['msg'] = "Password updated successfully";

echo json_encode($data);