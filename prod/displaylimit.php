<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require ("SmsInterface.inc");

    $providerid = tvalidator("ID",$_POST['providerid']);
    $limit = tvalidator("PURIFY",$_POST['limit']);
    $mode = tvalidator("PURIFY",$_POST['mode']);
    
    if($mode == 'CHAT'){
        $result = pdo_query("1", "
            update provider set chatlimit=? where providerid=?",array($limit,$providerid)
        );
    }
    if($limit < 100 || $limit > 2000){
        echo "Invalid option. Use a value between 100 and 2000";
        exit();
    }
    if($mode == 'CHAT'){
        $result = pdo_query("1", "
            update provider set chatlimit=? where providerid=?",array($limit,$providerid)
        );
    }
    if($mode == 'PEOPLE'){
        $result = pdo_query("1", "
            update provider set peoplelimit=? where providerid=?",array($limit,$providerid)
        );
    }
    $mode = strtolower($mode);
    echo "Set $mode list limit to $limit items.";
    
?>