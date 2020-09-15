<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("internationalization.php");
require_once("crypt-pdo.inc.php");
require_once("notify.inc.php");


    $providerid = tvalidator("ID",$_POST['providerid']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $friendproviderid = @tvalidator("PURIFY",$_POST['friendid']);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $filter = @tvalidator("PURIFY",$_POST['filter']);
    $friendlevel = @tvalidator("PURIFY",$_POST['friendlevel']);
    $chatid = @tvalidator("ID",$_POST['chatid']);

    if($providerid == $friendproviderid ){
        exit();
    }

    $profileroomid='';
    $result = pdo_query("1","select profileroomid from provider where providerid = $friendproviderid ");
    if($row = pdo_fetch($result)){
        $profileroomid = $row['profileroomid'];
    }
    
    if( $mode == 'D'){
        pdo_query("1","delete from friends where providerid = $providerid and friendid = $friendproviderid ");
        $mode = "";
    }    
    if( $mode == 'A'){
        pdo_query("1","delete from friends where providerid = $providerid and friendid = $friendproviderid ");
        pdo_query("1","insert into friends (providerid, friendid, level ) values ($providerid, $friendproviderid, '$friendlevel' ) " );
        $mode = "";
    }    
    if( $mode == 'XBAN'){
        $result = pdo_query("1","update provider p2 set banid = null where providerid = $friendproviderid and banid not in (select banid from provider p1 where providerid!=$friendproviderid and  p1.banid = p2.banid) ");
        $banid = '';
    }
    if( $mode == 'BAN'){
        $result = pdo_query("1","select banid, iphash, iphash2, handle from provider where providerid = $friendproviderid ");
        $banid = '';
        $iphash = '';
        $handle = '';
        if($row = pdo_fetch($result)){
            $banid = $row['banid'];
            $iphash = $row['iphash'];
            $iphash2 = $row['iphash2'];
            $handle = $row['handle'];
        }
        $result = pdo_query("1","select banid from ban where banid='$banid' and chatid = $chatid ");
        if($row = pdo_fetch($result)){
            //Already banned - unban
            pdo_query("1","delete from ban where banid = '$banid' and chatid = $chatid ");
            exit();
        }
        if($iphash == ''){
            $iphash = $handle;
            pdo_query("1","update provider set iphash = handle where providerid = $friendproviderid ");
        }
        $banid = $iphash2;
        if($banid == ''){
            $banid = $iphash;
        }
        
        pdo_query("1","delete from ban where banid = '$banid' and chatid = $chatid ");
        pdo_query("1","update provider set banid = iphash where iphash = '$iphash' ");
        pdo_query("1","update provider set banid = iphash2 where iphash2 = '$iphash2' and iphash2!='' ");
        pdo_query("1","insert into ban (banid, chatid ) values ('$banid', $chatid ) " );
        $mode = "";
    }    
    if( $mode == 'AF'){
        if($friendlevel == 'INCOGNITO'){
            $friendlevel = 'I';
        } else {
            $friendlevel = '';
        }
        pdo_query("1","delete from followers where providerid = $friendproviderid and followerid = $providerid ");
        pdo_query("1","insert into followers (providerid, followerid, level,followdate ) values ($friendproviderid, $providerid,'$friendlevel',now() ) " );
        $mode = "";
        
        GenerateNotificationV2( 
        $providerid, 
        $friendproviderid, //recipient 
        "CF", "", 
        null, 0, 
        "Followed", "",
        "PLAINTEXT", "", "", "" );
        
    }    
    if( $mode == 'UF'){
        pdo_query("1","delete from followers where providerid = $friendproviderid and followerid = $providerid ");
        $mode = "";
    }    
    
    exit();
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img class='icon20' src='$iconsource_braxarrowleft_common' style='padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    if($caller == 'room'){
    
        echo "      
                <span class='roomcontent'>
                    <div class='gridstdborder' 
                        data-room='All' data-roomid='All'                
                        style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        <img class='icon20 feed' Title='Back to Room' data-roomid='$roomid' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                            style='' />
                        &nbsp;
                        <span style='opacity:.5'>
                        $icon_braxroom2
                        </span>    
                        <span class='pagetitle2a' style='color:white'>$menu_friends $mode</span> 
                    </div>
                </span>
           ";
    } else {
        
        echo "      
                 <span class='roomcontent'>
                    <div class='gridstdborder roomselect' 
                        data-room='All' data-roomid='All'                
                        style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        <span style='opacity:.5'>
                        $icon_braxroom2
                        </span>
                        <span class='pagetitle2a' style='color:white'>$menu_friends $mode</span> 
                    </div>
                </span>
                <div class='mainfont showtop feed tapped'  
                    style='background-color:$global_background;color:$global_textcolor;padding-left:20px'
                    id='feed' data-roomid='$profileroomid' data-caller='$caller'>
                        $braxsocial
                            Friend Added - Return to User Profile
                        <br><br>
                </div>
           ";
        
    }
   
    
    
?>
