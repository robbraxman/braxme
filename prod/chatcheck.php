<?php
session_start();
require_once("config.php");

$providerid = mysql_safe_string($_POST[providerid]);
$override = mysql_safe_string($_POST[override]);


if( $override == "")
{
    $result = do_mysqli_query("1",
    
        "select chatid from chatmembers where providerid = $providerid and status = 'Y' and lastmessage >= lastread"
    );
}
else
{
    $result = do_mysqli_query("1",
    
        "select chatid from chatmembers where providerid = $providerid and status = 'Y' "
    );
}
if( $row = do_mysqli_fetch("1",$result))
{
    echo "$row[chatid]";
    exit();
}


    
?>