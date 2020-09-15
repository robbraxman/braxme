<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

$chatid = tvalidator("PURIFY",$_POST['chatid']);
$providerid = tvalidator("PURIFY",$_POST['providerid']);


$result = pdo_query("1",

    "
    update notification set status = 'Y', displayed='N' where status='N' and 
    recipientid = $providerid and chatid = $chatid  
    "
);
    
?>