<?php
session_start();
require_once("config-pdo.php");

$providerid = tvalidator("PURIFY",$_POST[providerid]);
$override = tvalidator("PURIFY",$_POST[override]);


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