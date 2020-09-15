<?php
session_start();
require("validsession.inc.php");
require("config.php");
require("sidebar.inc.php");



    if(@intval($_POST['chatid'])==0 && @intval($_POST['roomid'])==0){
        exit();
    }
    if(@intval($_POST['providerid'])==0 ){
        exit();
    }
    
    $providerid = tvalidator("PURIFY",$_POST['providerid']);
    $chatid = tvalidator("PURIFY",$_POST['chatid']);
    $roomid = tvalidator("PURIFY",$_POST['roomid']);
    if($roomid == ''){
        $roomid = 0;
    }
    if($chatid == ''){
        $chatid = 0;
    }
    
    $result = do_mysqli_query("1",
            "select id from notifymute where ((id=$chatid and idtype='C') or (id=$roomid and idtype='R') ) and providerid = $providerid "
            );
    if($row = do_mysqli_fetch("1",$result) ){
        $result = do_mysqli_query("1",
            "delete from notifymute where ((id=$chatid and idtype='C') or (id=$roomid and idtype='R') ) and providerid = $providerid "
            );
    } else {
        
        $id = 0;
        $idtype = '';
        if($chatid > 0){
            $id = $chatid;
            $idtype = 'C';
        }
        if($roomid > 0){
            $id = $roomid;
            $idtype = 'R';
        }
        if($id > 0){
            
            $result = do_mysqli_query("1",
                "insert into  notifymute (id, idtype, providerid ) values ($id, '$idtype', $providerid )"
                );
            echo 'Y';
            exit();
        }
        
    }