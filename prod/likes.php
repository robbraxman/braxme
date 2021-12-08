<?php
session_start();
require_once("config-pdo.php");

$shareid = tvalidator("PURIFY", $_GET[p] );
$ip = tvalidator("PURIFY", $_GET[ip] );

pdo_query("1","
    update shares set likes=likes+1 where shareid=?
    ",array($shareid));
$result = 
pdo_query("1","
    select likes from shares where shareid=?
    ",array($shareid));
if( $row = pdo_fetch($result))
    echo $row[likes];


?>