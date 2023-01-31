<?php
include "../connection/config.php";
extract($_REQUEST);

$day = strtoupper(date("l",$stamp));
$date = date('d/m/Y');

// if(rows(query("select sn from users where mobile='$mobile' and session ='$session'")) == 0){
//     $data['msg'] = "You are not authrized to use this";
      
//     $dd = query("select session,active from users where mobile='$mobile'");
//     $d = fetch($dd);
//     $data['session'] = $d['session'];
//     $data['active'] = $d['active'];
    
//     echo json_encode($data);
//     return;
// }

require_once "../scrap/data/support/web_browser.php";
require_once "../scrap/data/support/tag_filter.php";

$htmloptions = TagFilter::GetHTMLOptions();

$get_provider = fetch(query("select data from settings where data_key='provider'"));
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

	} else if($get_provider['data'] == "sattamatkagods"){
	    
	    $url = "https://www.sattamatkagods.net";
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
    	$body = str_replace('style="font-style: italic; font-weight: bold;font-family:Times New Roman;"',"class='markets'",$result["body"]);
    	$body = preg_replace('~[\r\n]+~', ' ', $body);
    	$body = str_replace('</span> <br/> ',"~",$body);
    	$body = str_replace('</span><br/> ',"~",$body);
    	$body = str_replace('</span> <br> ',"~",$body);
    	$body = str_replace('</span><br> ',"~",$body);
    	$body = str_replace('<br/> <div class="jodichartleft">','</span><div class="jodichartleft">',$body);
    	$body = str_replace('<br/><div class="jodichartleft">','</span><div class="jodichartleft">',$body);
        $body = str_replace('<br> <div class="jodichartleft">','</span><div class="jodichartleft">',$body);
         $body = str_replace('<div class="jodichartleft">','</span><div class="jodichartleft">',$body);
    
    	$html = TagFilter::Explode($body, $htmloptions);
    
    	$root = $html->Get();
    // class="satta-result"
    	$rows = $root->Find(".markets span");
    	
    	foreach ($rows as $row)
    	{
    	    if(strpos($row->GetPlainText(),"~") !== false){
    	  $table[] = $row->GetPlainText();
    	    }
    	   // echo $row->GetOuterHTML();
    	   // echo strpos($row->GetOuterHTML(),"background-color: yellow");
    	    
    	    if(strpos($row->GetOuterHTML(),'yellow') !== false){
    	        $tc = str_replace("<br>","~",$row->GetOuterHTML());
        	
        	  $table[] = strip_tags($tc);
    	    }
    	}
        
        foreach($table as $tb){
            
            $tbs = explode("~",$tb);
          //  print_r($tbs);
            $temp_h4[] = trim($tbs[0]);
            $temp_h5[] = trim($tbs[1]);
        }
        
        // foreach ($rows as $row)
        // {
        //     $temp_h4[] = trim($row->GetInnerHTML());
        // }
        	
        // foreach ($h5rows as $row)
        // {
        //     $temp_h5[] = trim($row->GetInnerHTML());
        // }

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
	    
	} 
	else {
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



$get = query("select * from gametime_new order by sort_no");
while($xc = fetch($get))
{
    
    $time = date("H:i",$stamp);
    
    if($xc['days'] == "ALL" || substr_count($xc['days'],$day) == 0){
        if(strtotime($time)<strtotime($xc['open']))
        {
            $xc['is_open'] = "1";
        }
        else
        {
            $xc['is_open'] = "0";
        $xc['open_time'] = "CLOSE";
        }
        
        if(strtotime($time)<strtotime($xc['close']))
        {
            $xc['is_close'] = "1";
        }
        else
        {
            $xc['is_close'] = "0";
        $xc['close_time'] = "CLOSE";
        }
    } else if(substr_count($xc['days'],$day."(CLOSED)") > 0){
        $xc['is_open'] = "0";
        $xc['is_close'] = "0";
        $xc['open'] = "CLOSE";
        $xc['close'] = "CLOSE";
        $xc['open_time'] = "CLOSE";
        $xc['close_time'] = "CLOSE";
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
        
        
        $xc['open'] = $mrk_time[0];
        $xc['close'] = $mrk_time[1];
        
        if(strtotime($time)<strtotime($mrk_time[0]))
        {
            $xc['is_open'] = "1";
        }
        else
        {
            $xc['is_open'] = "0";
        $xc['open_time'] = "CLOSE";
        }
        
        if(strtotime($time)<strtotime($mrk_time[1]))
        {
            $xc['is_close'] = "1";
        }
        else
        {
            $xc['is_close'] = "0";
        $xc['close_time'] = "CLOSE";
        }
    }
    
    $bazar = $xc['market'];
    
    $chk_if_query = query("select * from manual_market_results where market='$bazar' AND date='$date'");
    if(rows($chk_if_query) > 0){
        
        $xc['is_open'] = "0";
        $chk_if_updated = fetch($chk_if_query);
    
         
        if($chk_if_updated['close'] != ''){
           
            $xc['is_close'] = "0";
           
        } 
        
        
    } 
    
	$time = array_search($xc['market'], $temp_h4);
	   $mrk['is_close'] = $xc['is_close'];
    $mrk['is_open'] = $xc['is_open'];
    
    if($xc['open_time'] != "CLOSE"){
    $mrk['open_time'] = date("g:i a", strtotime($xc['open']));
    } else {
      $mrk['open_time'] = "HOLIDAY";
    }
  if($xc['close_time'] != "CLOSE"){
    $mrk['close_time'] = date("g:i a", strtotime($xc['close']));
  }else {
      $mrk['close_time'] = "HOLIDAY";
    }
    
	$mrk['market'] = $xc['market'];
	$mrk['result'] = $temp_h5[$time];
    $data['result'][] = $mrk;
}

$get = query("select * from gametime_manual order by sort_no");
while($xc = fetch($get))
{
    
    $time = date("H:i",$stamp);

    if($xc['days'] == "ALL" || substr_count($xc['days'],$day) == 0){
        if(strtotime($time)<strtotime($xc['open']))
        {
            $xc['is_open'] = "1";
        }
        else
        {
            $xc['is_open'] = "0"; 
          $xc['open_time'] = "CLOSE";
        }
        
        if(strtotime($time)<strtotime($xc['close']))
        {
            $xc['is_close'] = "1";
        }
        else
        {
            $xc['is_close'] = "0";
        $xc['close_time'] = "CLOSE";
        }
    } else if(substr_count($xc['days'],$day."(CLOSED)") > 0){
        $xc['is_open'] = "0";
        $xc['is_close'] = "0";
        $xc['open'] = "CLOSE";
        $xc['close'] = "CLOSE";        
        $xc['open_time'] = "CLOSE";
        $xc['close_time'] = "CLOSE";
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
        
        
        $xc['open'] = $mrk_time[0];
        $xc['close'] = $mrk_time[1];
        
        if(strtotime($time)<strtotime($mrk_time[0]))
        {
            $xc['is_open'] = "1";
        }
        else
        {
            $xc['is_open'] = "0";  
        $xc['open_time'] = "CLOSE";
        }
        
        if(strtotime($time)<strtotime($mrk_time[1]))
        {
            $xc['is_close'] = "1";
        }
        else
        {
            $xc['is_close'] = "0";
        $xc['close_time'] = "CLOSE";
        }
    }
    
    $bazar = $xc['market'];
    
    $chk_if_query = query("select * from manual_market_results where market='$bazar' AND date='$date'");
    if(rows($chk_if_query) > 0){
        
        $xc['is_open'] = "0";
        $chk_if_updated = fetch($chk_if_query);
    
         
        if($chk_if_updated['close'] != ''){
           
            $xc['is_close'] = "0";
           
        } 
        
        
    } 
    
    $mrk['market'] = $xc['market'];
    $market  = $xc['market'];
    $date = date("d/m/Y");
    
    $chk_if_query = query("select * from manual_market_results where market='$market' AND date='$date'");
    if(rows($chk_if_query) > 0){
        
    $chk_if_updated = fetch($chk_if_query);
    
        $rslt = $chk_if_updated['open_panna'].'-'.$chk_if_updated['open'];
        
        if($chk_if_updated['close'] != ''){
            $rslt = $rslt.$chk_if_updated['close'];
        } else {
             $rslt = $rslt.'*';
        }
        
         if($chk_if_updated['close_panna'] != ''){
            $rslt = $rslt.'-'.$chk_if_updated['close_panna'];
        } else {
            $rslt = $rslt.'-***';
        }
        
        
    } else {
        
        $date2 = date("d/m/Y",strtotime("-1 days"));
    
        $chk_if_query = query("select * from manual_market_results where market='$market' AND date='$date2'");
        if(rows($chk_if_query) > 0){
            
        $chk_if_updated = fetch($chk_if_query);
        
            $rslt = $chk_if_updated['open_panna'].'-'.$chk_if_updated['open'];
            
            if($chk_if_updated['close'] != ''){
                $rslt = $rslt.$chk_if_updated['close'];
            } else {
                 $rslt = $rslt.'*';
            }
            
             if($chk_if_updated['close_panna'] != ''){
                $rslt = $rslt.'-'.$chk_if_updated['close_panna'];
            } else {
                $rslt = $rslt.'-***';
            }
            
            
        } else {
            
            $rslt = "***-**-***";
            
        }
        
    }
    
       $mrk['is_close'] = $xc['is_close'];
    $mrk['is_open'] = $xc['is_open'];
    
    if($xc['open_time'] != "CLOSE"){
        $mrk['open_time'] = date("g:i a", strtotime($xc['open']));
        } else {
          $mrk['open_time'] = "HOLIDAY";
        }
      if($xc['close_time'] != "CLOSE"){
        $mrk['close_time'] = date("g:i a", strtotime($xc['close']));
      }else {
          $mrk['close_time'] = "HOLIDAY";
        }
    
	$mrk['result'] = $rslt;
    $data['result'][] = $mrk;
}

$today = date("m-d-y ");
   $name = 'open_time';
   $name2 = 'is_open';
   usort($data['result'], function ($a, $b) use(&$name){
      if($b[$name] == "HOLIDAY" || $b[$name] == "CLOSE" || $b[$name2] == "0") {return 1;}
      return strtotime($today.' '.$b[$name]) - strtotime($today.' '.$a[$name]);});



  
$dd = query("select sn,wallet,active,session,code,winning, bonus from users where mobile='$mobile'");
$d = fetch($dd);

$nt = query("select homeline from content where sn='1'");
$n = fetch($nt);

if($d['code'] == "0")
{
    $code = $d['sn'].rand(100000,9999999);
    query("update users set code='$code' where mobile='$mobile'");
}
else
{
    $code = $d['code'];
}


$getConfig = query("select * from settings");
while($config = fetch($getConfig)){
    
    $data[$config['data_key']] = $config['data'];
}

$getConfig = query("select * from image_slider");
while($config = fetch($getConfig)){
    
    $data['images'][] = $config;
}

if(rows(query("select sn from gateway_config where active='1'")) > 0){
    $data['gateway'] = "1";
} else {
    $data['gateway'] = "0";
}


$data['code'] = $code;

$get_wallet = fetch(query("select wallet, winning, bonus from users where mobile='$mobile'"));


$data['wallet'] = $get_wallet['wallet']+$get_wallet['winning']+$get_wallet['bonus'];

$data['winning'] = $d['winning'];
$data['bonus'] = $d['bonus'];
$data['active'] = $d['active'];
$data['session'] = $d['session'];
$data['homeline'] = $n['homeline'];


echo json_encode($data);