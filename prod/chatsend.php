<?php
session_start();
require_once("config.php");
require_once("crypt.inc.php");
require_once ("notify.inc.php");
require_once("chatsend.inc.php");
include("lib_autolink.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = mysql_safe_string($_POST['providerid']);
    $message = @mysql_safe_string_unstripped($_POST['message']);
    $chatid = @mysql_safe_string($_POST['chatid']);
    $msgid = @mysql_safe_string($_POST['msgid']);
    $img = @mysql_safe_string($_POST['img']);
    $url = @mysql_safe_string($_POST['url']);
    $mode = @mysql_safe_string($_POST['mode']);
    $action = @mysql_safe_string($_POST['action']);
    $popupurl = @mysql_safe_string($_POST['popupurl']);
    
    $radio = @mysql_safe_string($_POST['radio']);
    $passkey = DecryptE2EPasskey(@mysql_safe_string($_POST['passkey64']),$providerid);
    $streaming = intval(@mysql_safe_string($_POST['streaming']));
    $title = base64_encode(@mysql_safe_string(StripEmojis($_POST['title'])));
    
    $broadcastmode = '';
    if( $chatid == ""){
    
        echo "Fail2";
        exit();
    }
    
    if( $mode == 'F'){
        FlagChatMessage($action, $msgid, $chatid);
        
        exit();
    }    
    
    if( $mode == 'E'){
        ChatNotificationRequest($providerid, $chatid, "Email Notify", "PLAINTEXT","M");
        echo "Email Requested";
        exit();
    }
    
    
    if( $mode == 'D'){
        DeleteChatMessage($msgid, $chatid);
        TouchMembers($chatid);
        
        exit();
    }
    
    if( $mode == 'DP'){
    
        $result = do_mysqli_query("1",
            "
            delete from chatmembers 
            where 
            providerid = $providerid
            and 
            (
                providerid=$_SESSION[pid]
                or $providerid in 
                    (select providerid from chatmaster 
                    where owner=$_SESSION[pid] and 
                    chatmaster.chatid = chatmembers.chatid )
            )
            and
            chatid=$chatid 
            ");
        
        //Remove Me from Room that spawned this chat
        $result = do_mysqli_query("1","select roomid from chatmaster where chatid=$chatid and owner!=$providerid");
        if($row = do_mysqli_fetch("1",$result)){
            if($row['roomid']!=''){
                do_mysqli_query("1","delete from statusroom where roomid=$row[roomid] and providerid=$providerid and owner!=$providerid");
            }
        }
        
        
        exit();
    }
    
    $result = do_mysqli_query("1","
            select keyhash, radiotitle, broadcaster, hidemode, 
            owner, radiostation,
            ( select radiostation from roominfo 
              where 
              roominfo.roomid = chatmaster.roomid
            ) as roomradiostation
            from chatmaster where chatid=$chatid 
            ");
    if( !$row = do_mysqli_fetch("1",$result)){
    
        $chatid = "";
        echo "Fail";
        exit();
    }

    
    $keyhash = $row['keyhash'];
    $hidemode = $row['hidemode'];
    
    $compiled = FormatImage($img);
    $compiled .= FormatMessageNew($message);
    $messageshort = mb_substr(str_replace("\\n"," ",strip_tags($message)),0,80);
    $message = $compiled;
    if($img!=''){
        $messageshort .= "(Image)";
    }

    
    //$passkey = $_SESSION['chatpasskey'];
    if($keyhash == ''){
        $passkey = '';
        //$_SESSION['chatpasskey']='';
    } else {
        $hash = hash('sha256',"$passkey$chatid");
        if($hash != $keyhash){
            echo "Fail3";
            exit();
        }
        
    }
    
    $subtype = '';
    
    //*********************************************************************************************
    //*********************************************************************************************
    // Create Chat Message
    //*********************************************************************************************
    //*********************************************************************************************
    $encode = EncryptChat ($message,"$chatid","$passkey" );
    $encodeshort = EncryptChat ($messageshort,"$chatid","" );
    
    if($msgid == ''){
        
        $notify = true;
        CreateChatMessage( $providerid, $chatid, $passkey, $message, $messageshort, $streaming, $notify, $radiostation);
        
        
    } else {
        
        $result = do_mysqli_query("1",
            "
                update chatmessage set message = \"$encode\", encoding = '$_SESSION[responseencoding]' 
                where chatid = $chatid and msgid = $msgid
            ");
        
    }

    
    $result = do_mysqli_query("1",
        "
        update chatmembers set lastmessage=now(), lastread=now() 
        where providerid=$providerid and chatid=$chatid and status='Y'
        ");
    $result = do_mysqli_query("1",
        "
        update chatmaster set lastmessage=now() where  chatid=$chatid and status='Y'
        ");
    
    
    
    
    echo "Success";
    exit();
    