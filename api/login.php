<?php
include "../connection/config.php";
extract($_REQUEST);

$check = query("select * from users where mobile='$mobile' AND password='$pass'");

if(rows($check) > 0)
{
    $cs = fetch($check);
    if($cs['active'] == 1)
    {
        query("update users set session='$session' where mobile='$mobile'");
         $data = $cs;
         $data['success'] = "1";
         $data['msg'] = "login successfull";
        
    }
    else
    {
        $data['success'] = "0";
        $data['msg'] = "Your account is temporarely banned";
    }
}
else
{
        $data['success'] = "0";
        $data['msg'] = "Mobile number and password does not match";
}

echo json_encode($data);
