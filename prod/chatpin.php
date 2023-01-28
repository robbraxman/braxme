<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require ("SmsInterface.inc");

    $providerid = tvalidator("ID",$_POST['providerid']);
    $chatid = tvalidator("PURIFY",$_POST['chatid']);
    $mode = tvalidator("PURIFY",$_POST['mode']);
    
    $result = pdo_query("1","
        select pin from chatmembers where providerid = ? and chatid =?
            ",array($providerid,$chatid));
    $pin = '';
    if($row = pdo_fetch($result))
    {
        $pin = $row['pin'];
        if($pin == 'Y' && $mode!='SAVED' ){
            $result = pdo_query("1","
                update chatmembers set pin='' where providerid = ? and chatid =?
                    ",array($providerid,$chatid));
            echo "Chat Unpinned";
        } else
        if($pin == 'S' && $mode=='SAVED'){
            $result = pdo_query("1","
                update chatmembers set pin='' where providerid = ? and chatid =?
                    ",array($providerid,$chatid));
            echo "Chat Unsaved";
        } else {
            if($mode == 'SAVED'){
                $pin='S';
                echo "Chat Saved";
            } else {
            $pin = 'Y';
                echo "Chat Pinned";
                
            }
            $result = pdo_query("1","
                update chatmembers set pin='$pin' where providerid = ? and chatid =?
                    ",array($providerid,$chatid));
            
        }
    }
?>