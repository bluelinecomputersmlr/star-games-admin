<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

include "../connection/config.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// following files need to be included
if(isset($_SESSION['server']))
{
    $server = $_SESSION['server'];
     if($server == "1")
    {
        require_once("./lib/config_paytm.php");
    }
    else if($server == "2")
    {
        require_once("./lib/config_paytm2.php");
    }
    else if($server == "test")
    {
        require_once("./lib/config_paytm3.php");
    }
    else
    {
        require_once("./lib/config_paytm3.php");
    }
}
else
{
    require_once("./lib/config_paytm.php");
}

require_once("./lib/encdec_paytm.php");



$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
    
    // print_r($_POST);
    // return;
    
    
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
	    
	   $money = $_POST['TXNAMOUNT'];
	   $txn = $_POST['TXNID'];
	   $o_id = $_POST['ORDERID'];
	   
	
	    $get_order = fetch(query("select user from gateway_temp where hash='$o_id'"));
	 
        $mobile = $get_order['user'];
    
         
        query("update users set wallet=wallet+'$money' where mobile='$mobile'");
        
               
        query("INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at`,`owner`,'in_type') VALUES ('$mobile','$money','1','Deposit with paytm','$stamp','user','PAYTM')");
    
        
        echo '<script>window.location = "http://success.com";</script>';

	}
	else {
	    echo '<script>window.location = "http://failed.com";</script>';
	}

	if (isset($_POST) && count($_POST)>0 )
	{ 
		foreach($_POST as $paramName => $paramValue) {
			//	echo "<br/>" . $paramName . " = " . $paramValue;
		}
	}
	

}
else {
//	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>