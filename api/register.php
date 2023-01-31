<?php
include "../connection/config.php";
extract($_REQUEST);

// if(strlen($mobile) != 10){
//     $data['success'] = "0";
//     $data['msg'] = "Invalid mobile number";
//     echo json_encode($data);
// }

// if(strlen($pass) < 3){
//     $data['success'] = "0";
//     $data['msg'] = "Enter valid password";
//     echo json_encode($data);
// }

// if(strlen($name) < 1){
//     $data['success'] = "0";
//     $data['msg'] = "Enter valid name";
//     echo json_encode($data);
// }

$check = query("select mobile from users where mobile='$mobile'");

if(rows($check) > 0)
{
     $data['success'] = "1";
        $data['msg'] = "Mobile number already registered";
        
        query("update users set session='$session' where mobile='$mobile'");
}
else
{
    $data['success'] = "1";
    $data['msg'] = "Register successfull";
    
    $code = substr($mobile,0,2).rand(100000,9999999);
    
    query("INSERT INTO `users`( `name`, `email`, `mobile`,  `password`, `created_at`, `code`,`session`) VALUES ('$name','$email','$mobile','$pass','$stamp', '$code','$session')");
    
    if($refcode != '')
    {
        query("INSERT INTO `refers`( `user`, `code`) VALUES ('$mobile','$refcode') ");
    }
}

echo json_encode($data);
