<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("internationalization.php");

$providerid = @tvalidator("ID",$_SESSION['pid']);
$mode = @tvalidator("PURIFY",$_POST['mode']);
$chatid = @tvalidator("ID",$_POST['chatid']);
$passkey64 = @tvalidator("PURIFY",$_POST['passkey64']);

$recipientid = @tvalidator("ID",$_POST['recipientid']);
$handle = @tvalidator("PURIFY",$_POST['handle']);
$name = @tvalidator("PURIFY",$_POST['name']);
$email = @tvalidator("EMAIL",$_POST['email']);
$sms = @tvalidator("PURIFY",$_POST['sms']);
$techsupport = @tvalidator("PURIFY",$_POST['techsupport']);
$roomid = @tvalidator("ID",$_POST['roomid']);
$radiostation = @tvalidator("PURIFY",$_POST['radiostation']);

$buttonback = "<center><span class='mainfont'><img class='selectchatlist tapped icon20' 
    src='$iconsource_braxarrowleft_common' 
    style='padding-top:0;padding-left:2px;padding-bottom:0px;' /> Cancel</span></center>";
$passkey =  DecryptE2EPasskey($passkey64, $providerid);
$chattitle = '';
$roomchat = '';
$name2 = '';
$windowtitle = "Confirm ChatParty";
$action = $menu_chat;
$actiontitle = "$menu_startchat";
$chattitle = "";

if($roomid!=''){
    $result = pdo_query("1","select room, radiostation from roominfo where roomid = ?",array($roomid));
    if($row = pdo_fetch($result)){
        
        $chattitle = htmlentities("$row[room]", ENT_QUOTES);
        $chattitle = str_replace("Welcome","",$chattitle);
        $chattitle = str_replace("Room","",$chattitle);
        
        $name2 = htmlentities($row['room'], ENT_QUOTES);
        $name = "Room";
        $windowtitle = "Confirm Chat with $name";
        if($radiostation ==''){
            $radiostation = $row['radiostation'];
        }
        
        
        if($radiostation==''){
            $windowtitle = "<span class='pagetitle2a' style='color:$global_textcolor'>$menu_startchat - $name2</span>";
            $action = $menu_chat;
            $actiontitle = "$menu_startchat";
        }
        if($radiostation=='Y'){
            $windowtitle = "<br><span class='pagetitle2a' style='color:$global_textcolor'>$menu_startstation - $name2</span>";
            $action = $menu_station;
            $actiontitle = "$menu_startstation";
        }
    }
}

if($mode =='U'){
    
    
    $temppasskey = GetOneSentKey($providerid, $chatid);
    if($temppasskey!=''){
        $passkey = $temppasskey;
        //$passkey = "test2";
    }
    echo $passkey;
    exit();
}
if($mode =='C'){
    
    $windowtitle = "Access E2E Encrypted Chat";
    $placeholder = "Secret Key";
    $result = pdo_query("1","select title, encoding from chatmaster where chatid=? ",array($chatid));
    if($row = pdo_fetch($result)){
        $title = DecryptText( $row['title'],$row['encoding'], $chatid);
        
    }
    /*
    $passkey = DecryptE2EPasskey($passkey64, $providerid);
    */
    $button = "<img class='usechatpasskey' src='$iconsource_braxarrowright_common' style='cursor:pointer;position:relative;top:12px;height:35px;width:auto;padding-top:0;padding-left:2px;padding-bottom:0px;' />";
    $delete = "<img class='endchatbutton' id='endchatbutton' data-archive='N' data-chatid='$chatid' src='$iconsource_braxclose_common' style='cursor:pointer;position:relative;top:12px;height:35px;width:auto;padding-top:0;padding-left:2px;padding-bottom:0px;' />";
    if($title == ''){
        $prompt = "<b>Enter the Chat Secret Key</b><br>";
    } else {
        $prompt = "<b>$title</b> requires a Chat Secret Key.<br>";
        
    }
    $buttonback = "<center><img class='selectchatlist tapped icon20' data-mode='CHAT' src='../img/arrow-stem-circle-left-128.png' style='padding-top:0;padding-left:2px;padding-bottom:0px;max-height:35px' /> Back</center>";
    $prompt .= "        
        <br>
        <input id='chatpasskey' type='password' placeholder='$placeholder' size='40'  value='$passkey' autocomplete='false' style='max-width:300px' />
        <br><br>
        $delete &nbsp;&nbsp;&nbsp; $button
        <br><br>
        ";
        //<script>$('#chatpasskey').val( localStorage.getItem('chat-$chatid') );</script>
    
}
if($mode ==''){
    
    $placeholder = $menu_e2ekey;
    $button = "<img class='setchatpasskey icon20' src='$iconsource_braxarrowright_common' style='padding-top:0;padding-left:2px;padding-bottom:0px;max-height:35px' />";

        $prompt = " 

            <div style='color:$global_textcolor;max-width:300px;margin:auto'>
                <div class='smalltext'><b>$menu_title</b></div>
                <input id='chattitle' type='text' placeholder='$menu_title' value='$chattitle' size='40' maxlength=20 style='width:300px'  autocomplete='false' />
                <br><br>

            </div>
            <div class='chatE2Earea smalltext' style='color:$global_textcolor;max-width:300px;margin:auto;display:none'>
                <br>
                <b>$menu_e2ekey</b><br>
                <input id='chatpasskey' type='text' placeholder='$placeholder' value='' size='40' style='width:300px'  autocomplete='false' />
                <!--
                <br><br>
                <div class='smalltext'><b>Message Self-Destruct Time</b></div>
                <input id='chatlifespan' type='number' placeholder='Minutes to Self-Destruct' value='' size='40' min=0 max=525600 style='width:300px'  autocomplete='false' />
                -->
            </div>
            ";

        if($radiostation==''){
        $prompt .=
            "
            <div style='text-align:center'>
                <br>
                <div class='pagetitle3  chatenableE2E' style='color:$global_activetextcolor;cursor:pointer'>$menu_advancedsettings</div>
            </div>
            ";
        }
        $prompt .=
            "
            <br><br><br>
            <div class='divbutton6 mainfont setchatpasskey' 
                style='background-color:$global_menu_color'
                data-providerid='$providerid' data-mode='S' data-handle='$handle' 
                data-techsupport='$techsupport' data-recipientid='$recipientid' data-radiostation='$radiostation'
                data-email='$email' data-name='$name' data-sms='$sms' data-roomid='$roomid'
                >
                $actiontitle
                <img class='icon15' src='../img/Arrow-Right-in-Circle-White_120px.png' style='top:3px' />
            </div>
            <div class='chatE2Earea' style='color:$global_textcolor;max-width:300px;margin:auto;display:none'>
                <br><br><br>
                <b>Chat Secret Key</b><br>
                <span class='smalltext'>
                Advanced Feature: 
                This is an optional chat storage encryption. When used, the session is encrypted with a key only
                the participants possess.
                Keys are automatically sent for pickup to the recipient device but must be done within 24 hours.
                Keys also need to be resent for additional devices. Due to the extra steps involved with
                key management, this is 
                not intended for casual use. If a user loses a key, another current member has to enter the chat 
                and this will repass the key.
                </span>
                <br><br>
            </div>
            ";
    
}


?>
<div class='pagetitle2a gridstdborder' 
   style='background-color:<?=$global_titlebar_color?>;padding-top:0px;
   padding-left:20px;padding-bottom:3px;
   text-align:left;color:white;margin:0'> 

   <img class='icon20 tilebutton' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
       style='' />
   &nbsp;
   <span style='opacity:.5'>
   <?=$icon_braxchat2?>
   </span>
   <?=$menu_chat?>
   <br>
</div>

<div class="pagetitle2" style='color:<?=$global_textcolor?>;text-align:center;margin:auto'>        
    <br><br>
    <img class='icon20' src='../img/brax-chat-round-black-128.png' />
    <?=$windowtitle?>
    <br>
    <div class='pagetitle2a' style='margin:auto;text-align:left'>
        <br><br>
        <center>
            <div class='pagetitle2a' style='text-align:center;margin:auto'>
                <?=$prompt?>
            </div>
        </center>    
    </div>
    <div style='padding:0px;color:black'>
        <?=$roomchat?>
    </div>
 </div>


       
                   

