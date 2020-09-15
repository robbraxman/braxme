<?php
session_start();
require_once("config-pdo.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("PURIFY",$_POST['providerid']);
    $chatid = tvalidator("PURIFY",$_POST['chatid']);
    $archive = tvalidator("PURIFY",$_POST['archive']);

    SaveLastFunction($providerid,"", "");
    
    if( $chatid == ""){
        exit();
    }
    //echo "Deleting $chatid";

    if( $chatid == 1217 ){
        exit();
    }
    
    if( $archive !== 'Y')
    {
        $result = pdo_query("1",
            "
            delete from chatmembers where chatid=?
            ",array($chatid));

        $result = pdo_query("1",
            "
            delete from chatmessage where chatid=?
            ",array($chatid));

        $result = pdo_query("1",
            "
            delete from chatmaster where chatid=? 
            ",array($chatid));
    }
    if( $archive === 'Y')
    {
        $result = pdo_query("1",
            "
            update chatmessage set status='N' where chatid=?
            ",array($chatid));
        $result = pdo_query("1",
            "
            update chatmaster set status='N' where chatid=?
            ",array($chatid));

    }
?>