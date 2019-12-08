<?php
session_start();
require_once("config.php");
require_once("crypt.inc.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = @mysql_safe_string($_POST['c']);
    $callingid = @mysql_safe_string($_POST['a']);
    $mode = @mysql_safe_string($_POST['mode']);
    $chatid = @mysql_safe_string($_POST['chatid']);
    $passkey64 = @mysql_safe_string($_POST['passkey64']);
    
    $result = do_mysqli_query("1",
        "
            insert into chatmembers ( chatid, providerid, status, lastactive ) 
            values
            ( $chatid, $callingid, 'Y', 0 );
        ");
    
    PassE2EKey($chatid, $passkey64, $providerid, $callingid);


    exit();

    
    
?>

