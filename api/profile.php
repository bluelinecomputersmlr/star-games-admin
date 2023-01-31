<?php
include "../connection/config.php";
extract($_REQUEST);

query("update users set name='$name',email='$email' WHERE mobile='$mobile'");

$data['success'] = "1";

echo json_encode($data);