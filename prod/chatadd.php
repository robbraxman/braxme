<?php
session_start();
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("PURIFY",$_POST['c']);
    $callingid = @tvalidator("PURIFY",$_POST['a']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $chatid = @tvalidator("ID",$_POST['chatid']);
    $passkey64 = @tvalidator("PURIFY",$_POST['passkey64']);
    
    $result = pdo_query("1",
        "
            insert into chatmembers ( chatid, providerid, status, lastactive ) 
            values
            ( ?, ?, 'Y', 0 );
        ",array($chatid,$callingid));
    
    PassE2EKey($chatid, $passkey64, $providerid, $callingid);


    exit();

    
    
?>

