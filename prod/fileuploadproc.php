<?php
session_start();
$_SESSION[returnurl]="<a href='login.php'>Login</a>";
require_once("config-pdo.php");
require ("password.inc.php");
require ("fileuploadproc.inc.php");
require ("htmlhead.inc.php");   

echo "<title>Upload Files</title>";
echo "</head>";
echo "<body class='newmsgbody'>";
echo "<div class='statustitle'>Upload Status</div>";


$_SESSION[sessionid] = uniqid("", false);

$providerid = rtrim(tvalidator("PURIFY", "$_SESSION[pid]"));
$loginid = tvalidator("PURIFY", "$_SESSION[loginid]");
$subject = tvalidator("PURIFY", "$_POST[subject]");
$uploadtype = tvalidator("PURIFY", "$_POST[uploadtype]");


$upload_dir = 'upload-zone';


/*********************************************************************
 *                       UPLOAD
 *********************************************************************/

if( !ProcessUpload("$providerid","", $subject, $upload_dir, $uploadtype ))
{
    ReturnToMessageEntry();
    require("htmlfoot.inc");
    exit();
}

?>

