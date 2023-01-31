<?php
include "../connection/config.php";
extract($_REQUEST);

$sx = query("SELECT * FROM `feed` order by sn desc");
while($x = fetch($sx))
{
    if(rows(query("select sn from likes where user='$mobile' AND post='".$x['sn']."'")) > 0)
    {
        $x['islike'] = '1';
    } else {
        
        $x['islike'] = '0';
    }
    $x['time'] = getDateTimeDifferenceString($x['created_at']);
    $x['content'] = str_replace("&nbsp;"," ",strip_tags($x['content']));
    $x['full_content'] = $x['content'];
    
    $data['data'][] = $x;
}



//return current date time
function getCurrentDateTime(){
    //date_default_timezone_set("Asia/Calcutta");
    return date("Y-m-d H:i:s");
}
function getDateString($date){
    $dateArray = date_parse_from_format('Y/m/d', $date);
    $monthName = DateTime::createFromFormat('!m', $dateArray['month'])->format('F');
    return $dateArray['day'] . " " . $monthName  . " " . $dateArray['year'];
}

function getDateTimeDifferenceString($datetime){
    $datetime = date("Y-m-d H:i:s",$datetime);
    $currentDateTime = new DateTime(getCurrentDateTime());
    $passedDateTime = new DateTime($datetime);
    $interval = $currentDateTime->diff($passedDateTime);
    //$elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
    $day = $interval->format('%a');
    $hour = $interval->format('%h');
    $min = $interval->format('%i');
    $seconds = $interval->format('%s');

    if($day > 7)
        return getDateString($datetime);
    else if($day >= 1 && $day <= 7 ){
        if($day == 1) return $day . " day ago";
        return $day . " days ago";
    }else if($hour >= 1 && $hour <= 24){
        if($hour == 1) return $hour . " hour ago";
        return $hour . " hours ago";
    }else if($min >= 1 && $min <= 60){
        if($min == 1) return $min . " minute ago";
        return $min . " minutes ago";
    }else if($seconds >= 1 && $seconds <= 60){
        if($seconds == 1) return $seconds . " second ago";
        return $seconds . " seconds ago";
    } else if($seconds == 0){
        return "just now";
    }
}


echo json_encode($data);