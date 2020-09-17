<?php
session_start();
$_SESSION['returnurl']="<a href='login.php'>Login</a>";
require_once("config-pdo.php");
require ("password.inc.php");
require ("fileuploadprocs3.inc.php");
require ("htmlhead.inc.php");   

echo "<title>Upload Files</title>";
echo "</head>";
echo "<body class='newmsgbody'>";
echo "<div class='statustitle'>Upload Status</div>";


$_SESSION['sessionid'] = uniqid("", false);

$providerid = rtrim(@tvalidator("ID", "$_SESSION[pid]"));
$loginid = @tvalidator("PURIFY", "$_SESSION[loginid]");
$subject = @tvalidator("PURIFY", "$_POST[subject]");
$uploadtype = @tvalidator("PURIFY", "$_POST[uploadtype]");
$folder = @tvalidator("PURIFY", "$_POST[folder]");
$sendemail = @tvalidator("PURIFY", "$_POST[sendemail]");
$chatid = @tvalidator("ID", "$_POST[chatid]");
$passkey64 = @tvalidator("PURIFY", "$_POST[passkey64]");


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

