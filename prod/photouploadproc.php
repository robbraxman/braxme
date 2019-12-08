<?php
session_start();
$_SESSION['returnurl']="<a href='login.php'>Login</a>";
require_once("config.php");


require ("photouploadproc.inc.php");
echo "<body class='newmsgbody' style='padding:50px;font-family:helvetica, san-serif;font-height:20px'>";
echo "<img src='../img/logo-b2.png' style='height:50px' '/>";
echo "<div class='statustitle'>Photo Upload</div>";


$_SESSION['sessionid'] = uniqid("", false);

$providerid = rtrim(@mysql_safe_string( "$_SESSION[pid]"));
$loginid = @mysql_safe_string( "$_SESSION[loginid]");
$subject = @mysql_safe_string( "$_POST[subject]");
$album = @mysql_safe_string( "$_POST[album]");
$newalbum = @mysql_safe_string( "$_POST[newalbum]");
$uploadtype = @mysql_safe_string( "$_POST[uploadtype]");

if( $newalbum!='')
{
    $album = $newalbum;
}

$upload_dir = 'upload-zone';


/*********************************************************************
 *                       UPLOAD
 *********************************************************************/

if( !ProcessUpload("$providerid","", $subject, $album, $upload_dir, $uploadtype ))
{
    //ReturnToMessageEntry();
    //require("htmlfoot.inc");
    exit();
}

?>

