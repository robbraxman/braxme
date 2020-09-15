<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

$chatid = tvalidator("PURIFY",$_POST['chatid']);
$providerid = tvalidator("PURIFY",$_POST['providerid']);


$result = do_mysqli_query("1",

    "
    update notification set status = 'Y', displayed='N' where status='N' and 
    recipientid = $providerid and chatid = $chatid  
    "
);
    
?>