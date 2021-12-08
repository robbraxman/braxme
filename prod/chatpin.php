<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require ("SmsInterface.inc");

    $providerid = tvalidator("ID",$_POST['providerid']);
    $chatid = tvalidator("PURIFY",$_POST[chatid]);
    
    $result = pdo_query("1","
        select pin from chatmembers where providerid = ? and chatid =?
            ",array($providerid,$chatid));
    $pin = '';
    if($row = pdo_fetch($result))
    {
        $pin = $row['pin'];
        if($pin == 'Y'){
            $result = pdo_query("1","
                update chatmembers set pin='' where providerid = ? and chatid =?
                    ",array($providerid,$chatid));
            echo "Chat Unpinned";
        } else {
            $result = pdo_query("1","
                update chatmembers set pin='Y' where providerid = ? and chatid =?
                    ",array($providerid,$chatid));
            echo "Chat Pinned";
            
        }
    }
?>