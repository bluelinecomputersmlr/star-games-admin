<?php

require('config.php');

include "../connection/config.php";
//session_start();

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    
    //     print_r($_POST);
    // return;
    


    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_POST['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }

}

if ($success === true)
{
    $token = $_POST['razorpay_order_id'];
    $pid = $_POST['razorpay_payment_id'];
    
    // print_r($_POST);
    // return;
    
    
    $get_order = fetch(query("select user,amount from gateway_temp where hash='$token'"));
 
    $mobile = $get_order['user'];
    $amount = $get_order['amount'];

     
    query("update users set wallet=wallet+'$amount' where mobile='$mobile'");
    
           
    query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at`,`owner`,`in_type`) VALUES ('$mobile','$amount','1','Deposit with razorpay','$stamp','user','RAZORPAY')");

    
    echo '<script>window.location = "http://success.com";</script>';

    redirect("http://success.com");

    $html = "<p>Your payment was successful</p>
             <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
}
else
{

    
    echo '<script>window.location = "http://failed.com";</script>';

    redirect("http://failed.com");
}

print_r($_POST);

echo $html;
