<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

define("zone",  'Asia/Kolkata');    /** FIND YOUR TIMEZONE - https://www.php.net/manual/en/timezones.php */
date_default_timezone_set("Asia/Kolkata");
$stamp = time();

/// REPLACE WITH YOUR DATABASE DETAILS  
$con = mysqli_connect("localhost","srkgac2u_srk_game","srk_game","srkgac2u_srk_game");

if(mysqli_num_rows(mysqli_query($con,"SELECT * from settings where data_key='auto' AND data='1'"))){

/////// DO NOT TOUCH /////////////

$det_stamp = $stamp;
$time = date("H:i",$det_stamp);
$day = strtoupper(date("l",$stamp));
$date = date('d/m/Y');

$curr_dir = dirname(__FILE__);

require_once $curr_dir."/../scrap/data/support/web_browser.php";
require_once $curr_dir."/../scrap/data/support/tag_filter.php";

$htmloptions = TagFilter::GetHTMLOptions();

	$get_provider = mysqli_fetch_array(mysqli_query($con,"select data from settings where data_key='provider'"));
	if($get_provider['data'] == "dpboss"){

        $url = "https://dpboss.net/";
        $web = new WebBrowser();
        $result = $web->Process($url);
        
        if (!$result["success"])
        {
        	echo "Error retrieving URL.  " . $result["error"] . "\n";
        	exit();
        }
        
        if ($result["response"]["code"] != 200)
        {
        	echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
        	exit();
        }
        
        $baseurl = $result["url"];
        
        $html = TagFilter::Explode($result["body"], $htmloptions);
        
        $root = $html->Get();
        $rows = $root->Find("div.satta-result h4");
        $h5rows = $root->Find("div.satta-result h5");
        
        
        foreach ($rows as $row)
        {
            $temp_h4[] = trim($row->GetInnerHTML());
        }
        	
        foreach ($h5rows as $row)
        {
            $temp_h5[] = trim($row->GetInnerHTML());
        }

        
	} else if($get_provider['data'] == "sattamatka420"){
	    
	    $url = "https://sattamatka420.com/resultdpboss.php";
    	$web = new WebBrowser();
    	$result = $web->Process($url);
    
    	if (!$result["success"])
    	{
    		echo "Error retrieving URL.  " . $result["error"] . "\n";
    		exit();
    	}
    
    	if ($result["response"]["code"] != 200)
    	{
    		echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
    		exit();
    	}
    
    	$baseurl = $result["url"];
    
    	$html = TagFilter::Explode($result["body"], $htmloptions);
    
    	$root = $html->Get();
    // class="satta-result"
    	$rows = $root->Find(".marketName");
        $h5rows = $root->Find(".marketResult");
          
        foreach ($rows as $row)
        {
            $temp_h4[] = trim($row->GetInnerHTML());
        }
        	
        foreach ($h5rows as $row)
        {
            $temp_h5[] = trim(str_replace("<br>","",str_replace("\n ","",$row->GetInnerHTML())));
        }
	    
	} else {
	    
	    $url = "https://spboss.net/index.php";
        $web = new WebBrowser();
        $result = $web->Process($url);
        
        if (!$result["success"])
        {
        	echo "Error retrieving URL.  " . $result["error"] . "\n";
        	exit();
        }
        
        if ($result["response"]["code"] != 200)
        {
        	echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "\n";
        	exit();
        }
        
        $baseurl = $result["url"];
        
        $html = TagFilter::Explode($result["body"], $htmloptions);
        
        $root = $html->Get();
        $rows = $root->Find("div.result_sec div span.market_name");
        $h5rows = $root->Find("div.result_sec div span.market_result");
        
        
        foreach ($rows as $row)
        {
            $temp_h4[] = trim($row->GetInnerHTML());
        }
        	
        foreach ($h5rows as $row)
        {
            $temp_h5[] = trim(str_replace("<br>","",str_replace("\n ","",$row->GetInnerHTML())));
        }
	    
	}

/////// DO NOT TOUCH /////////////


$i = 0;
$first = "";
$get = mysqli_query($con,"select * from gametime_auto");
while($xc = mysqli_fetch_array($get))
{

    
    $date = date('d/m/Y');

    $is_open = "0";
    $is_close = "0";
    
  
 
    
     if($xc['days'] == "ALL" || substr_count($xc['days'],$day) == 0){
        if(strtotime($time)<strtotime($xc['open']))
        {
          $is_open = "1";
        }
        else
        {
            $is_open = "0";
        }
        
        if(strtotime($time)<strtotime($xc['close']))
        {
            $is_close = "1";
        }
        else
        {
            $is_close = "0";
        }
    } else if(substr_count($xc['days'],$day."(CLOSE)") > 0){
        $is_open = "0";
        $is_close = "0";
    } else {
        $time_array = explode(",",$xc['days']);
        for($i =0;$i< count($time_array);$i++){
            if(substr_count($time_array[$i],$day) > 0){
                $day_conf = $time_array[$i];
            }
        }
        
        $day_conf = str_replace($day."(","",$day_conf);
        $day_conf = str_replace(")","",$day_conf);
        
        $mrk_time = explode("-",$day_conf);
        
        
        if(strtotime($time)<strtotime($mrk_time[0]))
        {
           $is_open = "1";
        }
        else
        {
            $is_open = "0";
        }
        
        if(strtotime($time)<strtotime($mrk_time[1]))
        {
            $is_close = "1";
        }
        else
        {
            $is_close = "0";
        }
    }
    
       
    $rr_out[$xc['market']]['open'] = $is_open;
    $rr_out[$xc['market']]['close'] = $is_close;
    
    if($is_open == "0" || $is_close == "0"){
        
        $xvm = mysqli_query($con,"select * from rate where sn='1'");
        $xv = mysqli_fetch_array($xvm);
        
        
        $timex = array_search($xc['market'], $temp_h4);
	    $result = $temp_h5[$timex];
        $data[$xc['market']]['result'] = $result;
        
	    if($is_open == "0"){
	        
	      
	        
	        if($xc['is_open_next'] == "1"){
                $date = date('d/m/Y', strtotime('-1 day',$stamp));
	        } else {
	            $date = date('d/m/Y');
	        }
	        
	        
	        
        	$bazar = str_replace(" ","_",$xc['market']." OPEN");
        	
        	$rr_out[$xc['market']] = "select sn from games where date='$date' AND bazar='$bazar' AND status='0'";
        	
        	        
	        if(mysqli_num_rows(mysqli_query($con,"select sn from games where date='$date' AND bazar='$bazar' AND status='0'")) > 0){
	            
    	        if(strlen($result) > 4){
    	            if(is_numeric(substr($result, 4, 1))){
    	                $open = (int)substr($result, 4, 1);
    	            } else {
    	                $open = "false";
    	            }
    	            
        	        $open_patti = (int)substr($result, 0, 3);
        	        
        	        
        	        
        	        
        	        if(is_int($open_patti)){
        	            
        	               $xx = mysqli_query($con,"select * from games where bazar='$bazar' AND ( game='singlepatti' OR  game='doublepatti' OR  game='triplepatti' ) AND date='$date' AND number='$open_patti' AND status='0' AND is_loss='0'");
        	               
        	               // echo "<br>open patti<br>"."select * from games where bazar='$bazar' AND ( game='singlepatti' OR  game='doublepatti' OR  game='triplepatti' ) AND date='$date' AND number='$open_patti'"."<br><br>";
                            
                            while($x = mysqli_fetch_array($xx))
                            {
                                $sn = $x['sn'];
                                $user = $x['user'];
                                $amount = $x['amount']*$xv[$x['game']];
                            
                                if(mysqli_num_rows(mysqli_query($con,"select sn from games where sn='$sn' AND status='0'")) > 0){
                                    mysqli_query($con,"update games set status='1' where sn='$sn'");
                                
                                    mysqli_query($con,"update users set wallet=wallet+'$amount' where mobile='$user'");
                                    
                                    $remrk = $x['game']." ".$x['bazar']." Winning";
                                    
                                    mysqli_query($con,"INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at` ,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$sn')");
                                    
                                    mysqli_query($con,"INSERT INTO `auto_history`(`user`, `game_id`, `amount`, `created_at`) VALUES ('$user','$sn','$amount','$stamp')");
                                }
                                
                            }
                            
                            mysqli_query($con,"update games set is_loss='1' where bazar='$bazar' AND ( game='singlepatti' OR  game='doublepatti' OR  game='triplepatti' ) AND date='$date' AND number!='$open_patti' AND status='0' AND is_loss='0'");
                            
        	        }
        	        
        	       if(is_int($open)){
        	            
        	               $xx = mysqli_query($con,"select * from games where bazar='$bazar' AND game='single' AND date='$date' AND number='$open' AND status='0' AND is_loss='0'");
                            
        	               //echo "<br>open single<br>"."select * from games where bazar='$bazar' AND game='single' AND date='$date' AND number='$open'"."<br><br>";
        	               
                            while($x = mysqli_fetch_array($xx))
                            {
                                $sn = $x['sn'];
                                $user = $x['user'];
                                $amount = $x['amount']*$xv[$x['game']];
                                
                                if(mysqli_num_rows(mysqli_query($con,"select sn from games where sn='$sn' AND status='0'")) > 0){
                                
                                    mysqli_query($con,"update games set status='1' where sn='$sn'");
                                
                                    mysqli_query($con,"update users set wallet=wallet+'$amount' where mobile='$user'");
                                    
                                    $remrk = $x['game']." ".$x['bazar']." Winning";
                                    
                                    mysqli_query($con,"INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at` ,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$sn')");
                                    
                                    mysqli_query($con,"INSERT INTO `auto_history`(`user`, `game_id`, `amount`, `created_at`) VALUES ('$user','$sn','$amount','$stamp')");
                                
                                }
                            } 
                            
                            
                            mysqli_query($con,"update games set is_loss='1' where bazar='$bazar' AND game='single' AND date='$date' AND number!='$open' AND status='0' AND is_loss='0'");
        	        }
        	        
    	        }
	        
	        }
	    }
	    
	    if($is_close == "0"){
	        
	       
	        if($xc['is_close_next'] == "1"){
                $date = date('d/m/Y', strtotime('-1 day',$stamp));
	        } else {
	            $date = date('d/m/Y');
	        }
	        
	        
        	$bazar = str_replace(" ","_",$xc['market']." CLOSE");
	        
	        if(mysqli_num_rows(mysqli_query($con,"select sn from games where date='$date' AND bazar='$bazar' AND status='0'")) > 0){
	            
    	        if(strlen($result) == 10){
    	            
    	            if(is_numeric(substr($result, 5, 1))){
    	               $close = (int)substr($result, 5, 1);
    	            } else {
    	               $close = "false";
    	            }
    	            
        	        $close_patti = (int)substr($result, 7, 3);
    	            
        	        
        	        
        	        if(is_int($close_patti)){
        	            
        	               $xx = mysqli_query($con,"select * from games where bazar='$bazar' AND ( game='singlepatti' OR  game='doublepatti' OR  game='triplepatti' ) AND date='$date' AND number='$close_patti' AND status='0' AND is_loss='0'");
                            
        	              // echo "<br>closepatti<br>"."select * from games where bazar='$bazar' AND ( game='singlepatti' OR  game='doublepatti' OR  game='triplepatti' ) AND date='$date' AND number='$close_patti'"."<br><br>";
        	               
                            while($x = mysqli_fetch_array($xx))
                            {
                                $sn = $x['sn'];
                                $user = $x['user'];
                                $amount = $x['amount']*$xv[$x['game']];
                                
                                
                                if(mysqli_num_rows(mysqli_query($con,"select sn from games where sn='$sn' AND status='0'")) > 0){
                            
                                    mysqli_query($con,"update games set status='1' where sn='$sn'");
                                
                                    mysqli_query($con,"update users set wallet=wallet+'$amount' where mobile='$user'");
                                    
                                    $remrk = $x['game']." ".$x['bazar']." Winning";
                                    
                                    mysqli_query($con,"INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at` ,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$sn')");
                                    
                                    mysqli_query($con,"INSERT INTO `auto_history`(`user`, `game_id`, `amount`, `created_at`) VALUES ('$user','$sn','$amount','$stamp')");
                                
                                }
                                
                            } 
                            
                            mysqli_query($con,"UPDATE games set is_loss='1' where bazar='$bazar' AND ( game='singlepatti' OR  game='doublepatti' OR  game='triplepatti' ) AND date='$date' AND number!='$close_patti' AND status='0' AND is_loss='0'");
        	        }
        	        
        	       if(is_int($close)){
        	            
        	               $xx = mysqli_query($con,"select * from games where bazar='$bazar' AND game='single' AND date='$date' AND number='$close' AND status='0' AND is_loss='0'");
                            
        	               //echo "<br>close single<br>"."select * from games where bazar='$bazar' AND game='single' AND date='$date' AND number='$close'"."<br><br>";
                            while($x = mysqli_fetch_array($xx))
                            {
                                $sn = $x['sn'];
                                $user = $x['user'];
                                $amount = $x['amount']*$xv[$x['game']];
                                
                                
                                if(mysqli_num_rows(mysqli_query($con,"select sn from games where sn='$sn' AND status='0'")) > 0){
                            
                                    mysqli_query($con,"update games set status='1' where sn='$sn'");
                                
                                    mysqli_query($con,"update users set wallet=wallet+'$amount' where mobile='$user'");
                                    
                                    $remrk = $x['game']." ".$x['bazar']." Winning";
                                    
                                    mysqli_query($con,"INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at` ,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$sn')");
                                    
                                    mysqli_query($con,"INSERT INTO `auto_history`(`user`, `game_id`, `amount`, `created_at`) VALUES ('$user','$sn','$amount','$stamp')");
                                
                                }
                                
                            } 
                            
                            
                            mysqli_query($con,"UPDATE games set is_loss='1' where bazar='$bazar' AND game='single' AND date='$date' AND number!='$close' AND status='0' AND is_loss='0'");
        	        }
        	        
    	        }
	        
	        }
	    }
	    
	    if($is_close == "0"){
	        if(strlen($result) == 10){
	            
	            if($xc['is_close_next'] == "1"){
                    $date = date('d/m/Y', strtotime('-1 day',$stamp));
    	        } else {
    	            $date = date('d/m/Y');
    	        }
	            
    	        $close_patti = (int)substr($result, 7, 3);
    	        $open_patti = (int)substr($result, 0, 3);
    	        $open = (int)substr($result, 4, 1);
    	        
    	        if(is_numeric(substr($result, 5, 1))){
    	           $close = (int)substr($result, 5, 1);
    	        } else {
    	           $close = "false";
    	        }
            
    	        
    	        $bazar = str_replace(" ","_",$xc['market']);
    	        
    	        // JODI
    	        
    	        if(is_int($open) && is_int($close)){
        	            
        	            $full_num = $open.$close;
        	            
        	               $xx = mysqli_query($con,"select * from games where bazar='$bazar' AND game='jodi' AND date='$date' AND number='$full_num' AND status='0' AND is_loss='0'");
        	               
                            
                            while($x = mysqli_fetch_array($xx))
                            {
                                $sn = $x['sn'];
                                $user = $x['user'];
                                $amount = $x['amount']*$xv[$x['game']];
                            
                            
                                if(mysqli_num_rows(mysqli_query($con,"select sn from games where sn='$sn' AND status='0'")) > 0){
                                    mysqli_query($con,"update games set status='1' where sn='$sn'");
                                
                                    mysqli_query($con,"update users set wallet=wallet+'$amount' where mobile='$user'");
                                    
                                    $remrk = $x['game']." ".$x['bazar']." Winning";
                                    
                                    mysqli_query($con,"INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at` ,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$sn')");
                                    
                                    mysqli_query($con,"INSERT INTO `auto_history`(`user`, `game_id`, `amount`, `created_at`) VALUES ('$user','$sn','$amount','$stamp')");
                                
                                }
                                
                            } 
                            
                            
                            
                            mysqli_query($con,"UPDATE games set is_loss='1' where bazar='$bazar' AND game='jodi' AND date='$date' AND number!='$full_num' AND status='0' AND is_loss='0'");
        	        }
        	        
        	        
	            // full sangam
	            
	            
	            
        	        if(is_int($open_patti) && is_int($close_patti)){
        	            
        	            $full_num = $open_patti.' - '.$close_patti;
        	            
        	               $xx = mysqli_query($con,"select * from games where bazar like '%$bazar%' AND game='fullsangam' AND date='$date' AND number='$full_num' AND status='0' AND is_loss='0'");
                            
        	               
                            while($x = mysqli_fetch_array($xx))
                            {
                                $sn = $x['sn'];
                                $user = $x['user'];
                                $amount = $x['amount']*$xv[$x['game']];
                                
                                
                                if(mysqli_num_rows(mysqli_query($con,"select sn from games where sn='$sn' AND status='0'")) > 0){
                            
                                    mysqli_query($con,"update games set status='1' where sn='$sn'");
                                
                                    mysqli_query($con,"update users set wallet=wallet+'$amount' where mobile='$user'");
                                    
                                    $remrk = $x['game']." ".$x['bazar']." Winning";
                                    
                                    mysqli_query($con,"INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at` ,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$sn')");
                                    
                                    mysqli_query($con,"INSERT INTO `auto_history`(`user`, `game_id`, `amount`, `created_at`) VALUES ('$user','$sn','$amount','$stamp')");
                                
                                }
                                
                            } 
                            
                            
                            mysqli_query($con,"update games set is_loss='1' where bazar like '%$bazar%' AND game='fullsangam' AND date='$date' AND number!='$full_num' AND status='0' AND is_loss='0'");
        	        }
        	        
        	        // half sangam
	            
        	        
        	        $num1 = $open.' - '.$close_patti;
        	        $num2 = $open_patti.' - '.$close;
        	        
        	       if(is_int($open_patti) && is_int($close_patti) && is_int($close) && is_int($open)){
        	            
        	               $xx = mysqli_query($con,"select * from games where bazar like '%$bazar%' AND game='halfsangam' AND date='$date' AND ( number='$num1' or number='$num2') AND status='0' AND is_loss='0'");
                            
                            while($x = mysqli_fetch_array($xx))
                            {
                                $sn = $x['sn'];
                                $user = $x['user'];
                                $amount = $x['amount']*$xv[$x['game']];
                                
                                
                                if(mysqli_num_rows(mysqli_query($con,"select sn from games where sn='$sn' AND status='0'")) > 0){
                            
                                    mysqli_query($con,"update games set status='1' where sn='$sn'");
                                
                                    mysqli_query($con,"update users set wallet=wallet+'$amount' where mobile='$user'");
                                    
                                    $remrk = $x['game']." ".$x['bazar']." Winning";
                                    
                                    mysqli_query($con,"INSERT INTO `transactions`(`user`, `amount`, `type`, `remark`, `created_at` ,`game_id`) VALUES ('$user','$amount','1','$remrk','$stamp','$sn')");
                                    
                                    mysqli_query($con,"INSERT INTO `auto_history`(`user`, `game_id`, `amount`, `created_at`) VALUES ('$user','$sn','$amount','$stamp')");
                                
                                }
                                
                            } 
                            
                            
                            mysqli_query($con,"update games set is_loss='1' where bazar like '%$bazar%' AND game='halfsangam' AND date='$date' AND ( number='$num1' or number='$num2') AND status='0' AND is_loss='0'");
        	        }
        	        
    	        }
	    }
	    
	    
    }
    
       
        
        
}

}

