<?php
session_start();
require_once("config.php");

$shareid = tvalidator("PURIFY", $_GET[p] );
$ip = tvalidator("PURIFY", $_GET[ip] );

do_mysqli_query("1","
    update shares set likes=likes+1 where shareid='$shareid'
    ");
$result = 
do_mysqli_query("1","
    select likes from shares where shareid='$shareid'
    ");
if( $row = do_mysqli_fetch("1",$result))
    echo $row[likes];


?>