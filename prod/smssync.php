<?php
session_start();
require_once("config.php");
require_once("crypt.inc.php");


$result = do_mysqli_query("1", 
    "select providerid, replysms from provider where replysms!='' and replysms!='+1' "
);
while( $row = do_mysqli_fetch("1",$result))
{
    $sms = $row['replysms'];
    if($sms[0]!='+')
    {
        $sms = "+1".$sms;
    }
    
    $sms_encrypted = EncryptText( $sms, $providerid);
    do_mysqli_query("1"," 
        insert into sms (providerid, sms, encoding, unencoded ) values 
        (
            $row[providerid], '$sms_encrypted','$_SESSION[responseencoding]','$sms'
        )
    ");

}


?>