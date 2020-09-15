<?php
session_start();
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once ("notify.inc.php");
require_once("chatsend.inc.php");
require_once("broadcast.inc.php");
include("lib_autolink.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_POST['providerid']);
    $message = @mysql_safe_string_unstripped($_POST['message']);
    $chatid = tvalidator("ID",$_POST['chatid']);
    $msgid = tvalidator("PURIFY",$_POST['msgid']);
    $img = tvalidator("PURIFY",$_POST['img']);
    $url = tvalidator("PURIFY",$_POST['url']);
    $mode = tvalidator("PURIFY",$_POST['mode']);
    $action = tvalidator("PURIFY",$_POST['action']);
    $popupurl = tvalidator("PURIFY",$_POST['popupurl']);
    
    $radio = @tvalidator("PURIFY",$_POST['radio']);
    $passkey = DecryptE2EPasskey(@tvalidator("PURIFY",$_POST['passkey64']),$providerid);
    $streaming = intval(@tvalidator("PURIFY",$_POST['streaming']));
    $title = base64_encode(@tvalidator("PURIFY",StripEmojis($_POST['title'])));
    
    $broadcastmode = '';
    if($action =='VIDEO'){
        $broadcastmode = 'V';
    }
    if( $chatid == ""){
    
        echo "Fail2";
        exit();
    }
    
    if( $mode == 'F'){
        FlagChatMessage($action, $msgid, $chatid);
        TouchMembers($chatid);
        echo "success";
        exit();
    }    
    
    if( $mode == 'E'){
        ChatNotificationRequest($providerid, $chatid, "Email Notify", "PLAINTEXT","M");
        echo "Email Requested";
        exit();
    }
    
    if( $mode == 'T'){
    
        $title = stripslashes(@tvalidator("PURIFY",StripEmojis($_POST['title'])));
        
        if($title == ''){
            $encoding = '';
            $titleencrypted = '';
        } else {
            $titleencrypted =  EncryptText( $title, "$chatid" );
            $encoding = $_SESSION['responseencoding'];
        }
        $result = pdo_query("1",
            "
            update chatmaster set title='$titleencrypted', encoding='$encoding', radiostation='$radio' where chatid=$chatid 
            ");
        echo "success";
        exit();
    }
    
    if( $mode == 'D'){
        DeleteChatMessage($msgid, $chatid);
        TouchMembers($chatid);
        
        echo "success";
        exit();
    }
    
    if( $mode == 'DP'){
    
        $result = pdo_query("1",
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
        $result = pdo_query("1","select roomid from chatmaster where chatid=? and owner!=?",array($chatid,$providerid));
        if($row = pdo_fetch($result)){
            if($row['roomid']!=''){
                pdo_query("1","delete from statusroom where roomid=$row[roomid] and providerid=? and owner!=?",array($providerid,$providerid));
            }
        }
        
        echo "success";
        exit();
    }
    
    $result = pdo_query("1","
            select keyhash, radiotitle, broadcaster, hidemode, 
            owner, radiostation,
            ( select radiostation from roominfo 
              where 
              roominfo.roomid = chatmaster.roomid
            ) as roomradiostation
            from chatmaster where chatid=? 
            ",array($chatid));
    if( !$row = pdo_fetch($result)){
    
        $chatid = "";
        echo "Fail";
        exit();
    }

    
    $keyhash = $row['keyhash'];
    $quizroom = "";
    if($row['roomradiostation']=='Q'){
        $quizroom = 'Y';
    }
    $hidemode = $row['hidemode'];
    $broadcasterid = $row['broadcaster'];
    $radiostation = $row['radiostation'];
    $radiotitle = stripslashes(base64_decode($row['radiotitle']));
    
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
    
    //Ring Bell
    if($mode == "STREAM"){
        
        $msg = BroadcastModeMessage($providerid, $chatid, $mode, $action, "" );
        $message = $msg->message;
        $messageshort = $msg->messageshort;
        
        $msgid = "";
        $streaming = false;
        $subtype = "LV";
        $providerid = $broadcasterid;
        
    }
    if($mode == "BROADCASTER"){
        //$providerid = $broadcasterid;
        if($action == 'TITLE' && $title ==''){
            exit();
        }

        SetChatPopUpVideoViewer($providerid, $broadcastmode, $chatid);
        
        $streaming = true;

        //Audio Broadcast
        if($action == ''){
            //Auto End Audio Broadcasts if Not Streaming
            /* Recheck Streaming Status before Action */
            $streamhash = substr(hash("sha1", $chatid),0,8);
            $streamid = "chat$streamhash";
            
            $streaming = CheckLiveStream($streamid);
            if(!$streaming){
                $result = pdo_query("1",
                    "
                    update chatmembers set broadcaster = null where chatid=? 
                    ",array($chatid));


                $result = pdo_query("1",
                    "
                    update chatmaster set broadcaster = null, broadcastmode='', 
                    live='N', radiotitle='', reservestation=null 
                    where chatid=? and radiostation in ('Q','Y')
                    ",array(4chatid));
                //Delete original Streamid.mp3
                DeleteIcecastRecording($providerid, $chatid );
                RenameIcecastRecording($providerid, $chatid, $broadcastername, $title );

                echo "success";
                exit();
            }
            
        }
        
        if($action!='TITLE'){
            
            GoLive($providerid, $chatid, $title, $broadcastmode );
            CreateNewBroadcastLog($providerid, $chatid);
            
        } else {
            
            ChangeLiveTitle($chatid, $title);
            
        }
        
        
        $title_decoded = substr(stripslashes(base64_decode($title)),0,40);
        
        $msgid = "";
        $streaming = false;
        $subtype = "LV";
        
        $msg = BroadcastModeMessage($providerid, $chatid, $mode, $action, $title_decoded );
        $message = $msg->message;
        $messageshort = $msg->messageshort;
        
        
    }
    if($mode == "ENDBROADCAST"){
        
        $msgid = "";

        $msg = BroadcastModeMessage($providerid, $chatid, $mode, "", "" );
        $message = $msg->message;
        $messageshort = $msg->messageshort;
        
        $streaming = true;
        $subtype = "LV";
        $result = pdo_query("1",
            "
            update chatmaster set broadcaster = null,  
            live='N', broadcastmode=null, radiotitle='' 
            where chatid=? and radiostation in ('Y','Q')
            ",array($chatid));
        
        $result = pdo_query("1",
            "
            update chatmembers set broadcaster = null where chatid=? 
            ",array($chatid));
        
        $result = pdo_query("1",
            "
            delete from notification where chatid=? and notifytype='CP' and notifysubtype='LV'
            and notifyid > 0
            ",array($chatid));
        
        $result = pdo_query("1",
            "select broadcastid from broadcastlog  
             where providerid = ? and 
             chatid = ? order by broadcastid desc limit 1
            ",array($chatid,$providerid)
            );
        if($row = pdo_fetch($result)){
        
            pdo_query("1",
                "
                update broadcastlog
                set broadcastdate2 = now(),
                elapsed = time_to_sec(timediff( now(), broadcastdate ))
                where broadcastid = $row[broadcastid]
                and mode = 'B'
                ");
        }
        
    }
    
    if($mode == "LIKE"){
        $message = "👍";
        $messageshort = $message;
        $msgid = "";
        $streaming = true;
    }
    if($mode == "UNLIKE"){
        $message = "👎";
        $messageshort = $message;
        $msgid = "";
        $streaming = true;
    }
    
    if($mode == "REPLAYDELETE"){
        
        /* Recheck Streaming Status before Action */
        $streamhash = substr(hash("sha1", $chatid),0,8);
        $streamid = "chat$streamhash";
        $streaming = CheckLiveStream($streamid);
        if(streaming){
           //exit(); 
        }
        
        DeleteIcecastRecordingFilename($providerid, $chatid, $action );
        $result = pdo_query("1",
            "
            delete from recordings where recid=?
            ",$action);
        echo "success";
        exit();
    }
    
    
    

    //*********************************************************************************************
    //*********************************************************************************************
    // Create Chat Message
    //*********************************************************************************************
    //*********************************************************************************************
    $encode = EncryptChat ($message,"$chatid","$passkey" );
    $encodeshort = EncryptChat ($messageshort,"$chatid","" );
    
    if($msgid == ''){
        
        $notify = false;
        if(!$streaming && $quizroom==''){
            $notify = true;
        }
        CreateChatMessage( $providerid, $chatid, $passkey, $message, $messageshort, $streaming, $notify, $radiostation);
        
        
    } else {
        
        $result = pdo_query("1",
            "
                update chatmessage set message = \"$encode\", encoding = '$_SESSION[responseencoding]' 
                where chatid = ? and msgid = ?
            ",array($chatid,$msgid));
        
    }

    
    $result = pdo_query("1",
        "
        update chatmembers set lastmessage=now(), lastread=now() 
        where providerid=? and chatid=? and status='Y'
        ",array($providerid,$chatid));
    $result = pdo_query("1",
        "
        update chatmaster set lastmessage=now(),
        chatcount = (select count(*) from chatmessage where chatmessage.chatid = chatmaster.chatid and chatmessage.status = 'Y'),
        chatmembers = (select count(*) from chatmembers where chatmembers.chatid = chatmaster.chatid )
        where  chatid=? and chatmaster.status='Y'
        ",array($chatid));
    
    
    TouchMembers($chatid);
    
    
    echo "success";
    exit();