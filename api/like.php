<?php
include "../connection/config.php";
extract($_REQUEST);

$id = $usermobile;

$check = query("select sn from likes where post='$id' AND user='$mobile'");
if(rows($check) > 0)
{
    query("delete from likes where post='$id' AND user='$mobile'");
    query("update feed set likes=likes-'1' where sn='$id'");
    $data['success'] = "0";
}
else
{
    query("INSERT INTO `likes`( `post`, `user`, `created_at`) VALUES ('$id','$mobile','$stamp')");
    query("update feed set likes=likes+'1' where sn='$id'");
    $data['success'] = "1";
}

$getlike = query("select likes from feed where id='$id'");
$fetchLike = fetch($getlike);

$data['likes'] = $fetchLike['likes'];

echo json_encode($data);