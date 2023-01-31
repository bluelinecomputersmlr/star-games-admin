<?php

function httpGet($url)
{
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false); 
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
}
 
$mobile = $_REQUEST['mobile'];
$otp = $_REQUEST['otp'];

if($_REQUEST['code'] == "38ho3f3ws"){
    $msg = rawurlencode("Dear User You will get Your OTP is : $otp in your first game played. Download app link: What's app chat support AynInfra.");
   $url = "http://sms.smslab.in/api/sendhttp.php?authkey=211227AXF9O39W5e93b834P1&mobiles=91$mobile&message=Dear%2BUser%2C%2BYour%2Botp%2Bis%2B$otp%2Bfor%2Bverify%2Bthe%2Bmobile%2Bnumber.%0D%0AAYNSMS&sender=AYNLTD&route=4&country=91&DLT_TE_ID=1307165234948566757";
    
   // $url = "https://sms.smslab.in/action_layer.php?action=11&country=91&route=4&sender=AYNLTD&mobiles=917014965550&DLT_TE_ID=1307165234948566757&unicode=0&message=Dear%2BUser%2C%2BYour%2Botp%2Bis%2B123456%2Bfor%2Bverify%2Bthe%2Bmobile%2Bnumber.%0D%0AAYNSMS&campaign_name=&schtime=&sentTime=2022-05-24%2B15%3A55%3A52&userip=27.58.89.24"
    echo httpGet($url);
} else {
  
  echo '<h3>404 page not found</h3>';
  
}