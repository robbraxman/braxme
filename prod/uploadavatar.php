<?php
session_start();
require_once("config.php");
require_once("crypt.inc.php");
require_once("room.inc.php");

require ("photouploadprocpg.inc.php");



$providerid = '';
if(isset($_SESSION['pid'])){
    $providerid = rtrim(mysql_safe_string( "$_SESSION[pid]"));
}
if($providerid == ''){
    $providerid = @mysql_safe_string( "$_GET[pid]");
}
$loginid = mysql_safe_string( "$_SESSION[loginid]");
$subject = "Mobile Upload";
//$album = "MobileUpload";
$uploadtype = "";

if(isset($_SESSION['timezone'])){
    $_SESSION['timezoneoffset'] = floatval($_SESSION['timezone']) - floatval($_SESSION['servertimezone']);
} else {
    $_SESSION['timezone'] = 0;
    $_SESSION['timezoneoffset'] = -8;
}
$today = date("M-d-y",time()+$_SESSION['timezone']*60*60);
$album = "Upload-".$today;

$upload_hdr = 'photolib';


/*********************************************************************
 *                       UPLOAD
 *********************************************************************/

    $img = ProcessUpload("$providerid","", $subject, $album, $upload_hdr, $uploadtype );
    if( $img == '')
        exit();
    
    SaveLastFunction($providerid,"A", "");
    do_mysqli_query("1","update provider set avatarurl='$rootserver/$installfolder/sharedirect.php?a=$img' where providerid=$providerid ");
            
        
    //ReturnToMessageEntry();
    //require("htmlfoot.inc");
    exit();

?>