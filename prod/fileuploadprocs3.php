<?php
session_start();
$_SESSION['returnurl']="<a href='login.php'>Login</a>";
require_once("config.php");
require ("password.inc.php");
require ("fileuploadprocs3.inc.php");
require ("htmlhead.inc.php");   

echo "<title>Upload Files</title>";
echo "</head>";
echo "<body class='newmsgbody'>";
echo "<div class='statustitle'>Upload Status</div>";


$_SESSION['sessionid'] = uniqid("", false);

$providerid = rtrim(@mysql_safe_string( "$_SESSION[pid]"));
$loginid = @mysql_safe_string( "$_SESSION[loginid]");
$subject = @mysql_safe_string( "$_POST[subject]");
$uploadtype = @mysql_safe_string( "$_POST[uploadtype]");
$folder = @mysql_safe_string( "$_POST[folder]");
$sendemail = @mysql_safe_string( "$_POST[sendemail]");
$chatid = @mysql_safe_string( "$_POST[chatid]");
$passkey64 = @mysql_safe_string( "$_POST[passkey64]");


$upload_dir = 'upload-zone';


/*********************************************************************
 *                       UPLOAD
 *********************************************************************/

if( !ProcessUpload("$providerid","", $subject, $upload_dir, $uploadtype, $folder, $sendemail, $chatid, $passkey64 ))
{
    //ReturnToMessageEntry();
    require("htmlfoot.inc");
    exit();
}

?>

