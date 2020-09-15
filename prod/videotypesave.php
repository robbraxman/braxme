<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

    $providerid = tvalidator("PURIFY","$_SESSION[pid]");

    $broadcasttype = tvalidator("PURIFY","$_POST[broadcasttype]");
    $channel = tvalidator("PURIFY","$_POST[channel]");
    $title = base64_encode(tvalidator("PURIFY","$_POST[title]"));
    if($broadcasttype ==''){
        exit();
    }
    if($broadcasttype=='braxlive' || $broadcasttype=='webcam'){
        $channel = "";
    }
    if($channel == '' && $broadcasttype!='braxlive' && $broadcasttype!='webcam' ){
        $result = pdo_query("1","select channel, title from streamingaccounts where providerid = $providerid and videotype='$broadcasttype'   ");
        if($row = pdo_fetch($result)){
            $title_decoded = base64_decode($row['title']);
            $arr = array('channel'=> "$row[channel]",
                         'title' => "$title_decoded"
                        );
            echo json_encode($arr);
            exit();
        }
    }
    

    $result = pdo_query("1", 
            " update provider set streamingaccount='$broadcasttype/$channel' where providerid=$providerid "
            );
    
    $result = pdo_query("1", 
            " delete from streamingaccounts where providerid = $providerid and videotype='$broadcasttype' "   
            );
    
        
    $result = pdo_query("1", 
            " insert into streamingaccounts( providerid, videotype, channel, title ) values ($providerid, '$broadcasttype','$channel','$title') "
            );
