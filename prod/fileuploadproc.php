<?php
session_start();
$_SESSION[returnurl]="<a href='login.php'>Login</a>";
require_once("config.php");
require ("password.inc.php");
require ("fileuploadproc.inc.php");
require ("htmlhead.inc.php");   

echo "<title>Upload Files</title>";
echo "</head>";
echo "<body class='newmsgbody'>";
echo "<div class='statustitle'>Upload Status</div>";


$_SESSION[sessionid] = uniqid("", false);

$providerid = rtrim(mysql_safe_string( "$_SESSION[pid]"));
$loginid = mysql_safe_string( "$_SESSION[loginid]");
$subject = mysql_safe_string( "$_POST[subject]");
$uploadtype = mysql_safe_string( "$_POST[uploadtype]");


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

