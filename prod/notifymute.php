<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
require("sidebar.inc.php");



    if(@intval($_POST['chatid'])==0 && @intval($_POST['roomid'])==0){
        exit();
    }
    if(@intval($_POST['providerid'])==0 ){
        exit();
    }
    
    $providerid = tvalidator("ID",$_POST['providerid']);
    $chatid = tvalidator("ID",$_POST['chatid']);
    $roomid = tvalidator("ID",$_POST['roomid']);
    if($roomid == ''){
        $roomid = 0;
    }
    if($chatid == ''){
        $chatid = 0;
    }
    
    $result = pdo_query("1",
            "select id from notifymute where ((id=? and idtype='C') or (id=? and idtype='R') ) and providerid = ? "
            ,array($chatid,$roomid,$providerid));
    if($row = pdo_fetch($result) ){
        $result = pdo_query("1",
            "delete from notifymute where ((id=? and idtype='C') or (id=? and idtype='R') ) and providerid = ? "
            ,array($chatid,$roomid,$providerid));
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
            
            $result = pdo_query("1",
                "insert into  notifymute (id, idtype, providerid ) values (?,?,? )"
                ,array($id,$idtype,$providerid));
            echo 'Y';
            exit();
        }
        
    }