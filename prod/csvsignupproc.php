<?php
session_start();
$_SESSION['returnurl']="<a href='login.php'>Login</a>";
require_once("config.php");
require ("password.inc.php");
//require ("accountcheck.inc");




$subject = @mysql_safe_string( "$_POST[subject]");
$uploadtype = @mysql_safe_string( "$_POST[uploadtype]");
$folder = @mysql_safe_string( "$_POST[folder]");
$roomid = @mysql_safe_string( "$_POST[roomid]");
$sponsor = @mysql_safe_string( "$_POST[sponsor]");
$step = @mysql_safe_string( "$_POST[step]");


$upload_dir = 'upload-zone';

if($step!=='4'){
require ("htmlhead.inc.php");   
echo "<title>Upload Account Setup</title>";
echo "</head>";
echo "<body class='newmsgbody' style='font-size:13px;'>";
echo "<div class='statustitle'>Upload Status</div>";
}
/*********************************************************************
 *                       UPLOAD
 *********************************************************************/


require ("csvsignupproc.inc.php");
if($step == '1'){
    if( !ProcessUpload("$providerid", $upload_dir, $roomid, $sponsor )){
    
    }
}
if($step == '2'){
    if( !ProcessUpload2("$providerid", $upload_dir, $roomid, $sponsor )){
    
    }
}
if($step == '3'){
    
    if( !ProcessUpload2a("$providerid", $upload_dir, $roomid, $sponsor )){
    
    }
}
if($step == '4'){
    if( !exportCSV( $sponsor )){
    
    }
} else {
    require("htmlfoot.inc");
}
?>

