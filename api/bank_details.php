<?php
include "../connection/config.php";
extract($_REQUEST);


if(rows(query("select sn from bank_details where user='$mobile'")) > 0){
    
    query("delete from bank_details where user='$mobile'");
    
}

query("INSERT INTO `bank_details`(`user`, `ac`, `holder`, `bank`, `ifsc`, `created_at`) VALUES ('$mobile','$ac','$holder','$bank','$ifsc','$stamp')");

$data['success'] ="1";

$data['msg'] = "Bank details updated successfully";

echo json_encode($data);
 
