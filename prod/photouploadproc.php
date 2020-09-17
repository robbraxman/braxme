<?php
session_start();
$_SESSION['returnurl']="<a href='login.php'>Login</a>";
require_once("config-pdo.php");


require ("photouploadproc.inc.php");
echo "<body class='newmsgbody' style='padding:50px;font-family:helvetica, san-serif;font-height:20px'>";
echo "<img src='../img/logo-b2.png' style='height:50px' '/>";
echo "<div class='statustitle'>Photo Upload</div>";


$_SESSION['sessionid'] = uniqid("", false);

$providerid = rtrim(@tvalidator("ID", "$_SESSION[pid]"));
$loginid = @tvalidator("PURIFY", "$_SESSION[loginid]");
$subject = @tvalidator("PURIFY", "$_POST[subject]");
$album = @tvalidator("PURIFY", "$_POST[album]");
$newalbum = @tvalidator("PURIFY", "$_POST[newalbum]");
$uploadtype = @tvalidator("PURIFY", "$_POST[uploadtype]");

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

