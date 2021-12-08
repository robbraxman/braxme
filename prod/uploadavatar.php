<?php
session_start();
//require("validsession.inc.php");
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");

require_once("room.inc.php");


require_once("notify.inc.php");
require_once("photouploadprocpg.inc.php");

/*
$uniqid = uniqid('');
$new_image_name = "pgtest_$uniqid_$_SESSION[pid].jpg";
move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/prod/upload/server/php/files/".$new_image_name);
*/


$chatid = 0;
$providerid = '';
if(isset($_SESSION['pid'])){
    $providerid = rtrim(tvalidator("PURIFY", "$_SESSION[pid]"));
}
if($providerid == ''){
    $tmp = @tvalidator("PURIFY", "$_GET[pid]");
    $tmp = explode("-",$tmp);
    $providerid = $tmp[0];
    $chatid = $tmp[1];
    LogDebug($providerid,"UploadPG($chatid)$providerid");
    //$providerid = substr($providerid,1);
}
//LogDebug($providerid,"UploadPG");
$loginid = 'admin';
$mode = @tvalidator("PURIFY", "$_GET[c]");
$subject = "Mobile Upload";
//$album = "MobileUpload";
$uploadtype = "";

//LogDebug($providerid, "0-photo: Got Here");
   // LogDebug(4,"UploadPG-$providerid");
if(isset($_SESSION['timezone'])){
    $_SESSION['timezoneoffset'] = floatval($_SESSION['timezone']) - floatval($_SESSION['servertimezone']);
} else {
    $_SESSION['timezone'] = 0;
    $_SESSION['timezoneoffset'] = -8;
}
$today = date("M-d-y",time()+$_SESSION['timezone']*60*60);
$album = "Upload-".$today;

$upload_hdr = "upload-zone/files";
    


/*********************************************************************
 *                       UPLOAD
 *********************************************************************/

    $img = ProcessUpload("$providerid","", $subject, $album, $upload_hdr, $uploadtype );
    if( $img == ''){
        exit();
    }
    SaveLastFunction($providerid,"A", "");
    pdo_query("1","update provider set avatarurl='$rootserver/$installfolder/sharedirect.php?a=$img' where providerid=$providerid ",null);

?>