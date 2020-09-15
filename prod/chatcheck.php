<?php
session_start();
require_once("config-pdo.php");

$providerid = mysql_safe_string($_POST[providerid]);
$override = mysql_safe_string($_POST[override]);


if( $override == "")
{
    $result = pdo_query("1",
    
        "select chatid from chatmembers where providerid = ? and status = 'Y' and lastmessage >= lastread"
    ,array($providerid));
}
else
{
    $result = pdo_query("1",
    
        "select chatid from chatmembers where providerid = ? and status = 'Y' "
    ,array($providerid));
}
if( $row = pdo_fetch($result))
{
    echo "$row[chatid]";
    exit();
}


    
?>