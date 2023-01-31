<?php


$con = mysqli_connect('localhost','srkgac2u_srk_game','srk_game','srkgac2u_srk_game');


if(mysqli_num_rows(mysqli_query($con,"SELECT * FROM `gateway_config` where name='razorpay' AND active='0'")) > 0){
    echo "<h1>Payment service temporarily down</h1>";
    return;
}

 $get_min = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `settings` where data_key='min_deposit'"));
if((int)$get_min['data'] > $_REQUEST["amount"]){
    echo "<h1>Minimum deposit amount is ".$get_min['data']."</h1>";
    return;
}


header("location:https://kakusofti.co.in/razorpay/pay.php?amount=".$_REQUEST["amount"]."&user=".$_REQUEST["user"]);
return;

require('config.php');
require('razorpay-php/Razorpay.php');

include "../connection/config.php";

//session_start();
// Create the Razorpay Order

$mobile = $_REQUEST['user'];

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//


$user = query("select * from users where mobile='$mobile'");
$us = fetch($user);



$ORDER_ID = md5(rand().time().$_REQUEST["user"].$_REQUEST["amount"]);

$orderData = [
    'receipt'         => $ORDER_ID,
    'amount'          => $_REQUEST['amount'] * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$am = $_REQUEST['amount'];
query("INSERT INTO `gateway_temp`( `user`, `hash`,`amount`) VALUES ('$mobile','$razorpayOrderId','$am')");


//$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$checkout = 'automatic';

if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
{
    $checkout = $_GET['checkout'];
}


$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => "SRK GAME",
    "description"       => "",
    "prefill"           => [
    "name"              => $us['name'],
    "email"             => $us['email'],
    "contact"           => $us['mobile'],
    ],
    "notes"             => [
    "merchant_order_id" => $ORDER_ID,
    ],
    "theme"             => [
    "color"             => "#f58634"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);

require("checkout/{$checkout}.php");

?>
<script>
    window.onload=function(){
        document.getElementsByClassName("razorpay-payment-button")[0].click();
    };
</script>
<style>
    body
    {
        background: #ff722c;
    }
    .razorpay-payment-button
    {
        display: none;
    }
</style>
