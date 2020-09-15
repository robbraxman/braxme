<?php
session_start();
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = @mysql_safe_string($_POST['c']);
    $callingid = @mysql_safe_string($_POST['a']);
    $mode = @mysql_safe_string($_POST['mode']);
    $chatid = @mysql_safe_string($_POST['chatid']);
    $passkey64 = @mysql_safe_string($_POST['passkey64']);
    
    $result = pdo_query("1",
        "
            insert into chatmembers ( chatid, providerid, status, lastactive ) 
            values
            ( ?, ?, 'Y', 0 );
        ",array($chatid,$callingid));
    
    PassE2EKey($chatid, $passkey64, $providerid, $callingid);


    exit();

    
    
?>

