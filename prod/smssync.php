<?php
session_start();
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");


$result = pdo_query("1", 
    "select providerid, replysms from provider where replysms!='' and replysms!='+1' "
);
while( $row = pdo_fetch($result))
{
    $sms = $row['replysms'];
    if($sms[0]!='+')
    {
        $sms = "+1".$sms;
    }
    
    $sms_encrypted = EncryptText( $sms, $providerid);
    pdo_query("1"," 
        insert into sms (providerid, sms, encoding, unencoded ) values 
        (
            $row[providerid], '$sms_encrypted','$_SESSION[responseencoding]','$sms'
        )
    ");

}


?>