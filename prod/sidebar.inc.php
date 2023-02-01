<?php
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("internationalization.php");





    function NotificationStatus($providerid, $write){
        
        if(intval($providerid)==0){
            return "";
        }
        if(!isset($_SESSION['deviceid'])){
            return "";
        }

        $notificationstatus = "";
        $result = pdo_query("1",
        
            "
            select lastnotified from alertrefresh 
            where providerid = ? 
            and deviceid = '$_SESSION[deviceid]'
            and lastnotified is null
            ",array($providerid)
        );
        if($row = pdo_fetch($result)){
            
            if($write ){
            
                pdo_query("1","update alertrefresh set lastnotified = now() "
                        . "where providerid = ? and deviceid = '$_SESSION[deviceid]' and lastnotified is null  ",array($providerid));
            }
        
            $notificationstatus = "Y";
        }
        return $notificationstatus;
    }
    function ChatStatus($providerid){

        if(intval($providerid) == 0){
            return "";
        }
        $notificationstatus = "";
        $result = pdo_query("1",
        
            "
            select * 
            from notification 
            left join provider on notification.recipientid = provider.providerid
            where notification.recipientid = ? and 
                ( notification.notifydate > provider.chatnotified or provider.chatnotified is null ) and 
                notification.notifytype = 'CP' and 
                (notification.notifysubtype is null or notification.notifysubtype = 'CY') 
                and notification.chatid is not null
                and notification.chatid not in (select chatid from chatmaster where radiostation='Y')
                
                limit 1
            ",array($providerid)
        );
        if($row = pdo_fetch($result))
        {
            $notificationstatus = "Y";
        }
        return $notificationstatus;
    }
    function RoomStatus($providerid){

        if(intval($providerid) == 0){
            return "";
        }
        $notificationstatus = "";
        $result = pdo_query("1",
        
            "
            select * 
            from notification 
            left join provider on notification.recipientid = provider.providerid
            where recipientid = ? and 
            ( notification.notifydate > provider.roomnotified or provider.roomnotified is null) and 
                notifytype = 'RP' limit 1
            ",array($providerid)
        );
        if($row = pdo_fetch($result))
        {
            $notificationstatus = "Y";
        }
        return $notificationstatus;
    }

    function RadioStatus($providerid){

        if(intval($providerid) == 0){
            return "";
        }
        $radiostatus = "";
        $result = pdo_query("1",
        
            "
            select broadcaster from chatmaster where status='Y' and radiostation='Y' 
            and chatid in (select chatid from chatmembers where chatmaster.chatid = chatmembers.chatid 
            and chatmembers.providerid = ? )
            and chatmaster.adminstation !='Y'
                
            ",array($providerid)
        );
        $count = 0;
        while($row = pdo_fetch($result))
        {
            $count++;
            if($row['broadcaster']!=''){
                $radiostatus = "Y";
            }
        }
        if($count == 0){
            return "";
        }
        return $radiostatus;
    }

    
    function SyncMailStatus($providerid){
        return 0;
    }
    
    function MeetupStatus($providerid){

        $meetupstatus = "";
        $result = pdo_query("1",
        
            "
            select * from 
            appmeetup 
            left join appidentity on appmeetup.appname = appidentity.appname
            and appmeetup.appidentity = appidentity.appidentity
            left join provider on appidentity.replyemail = provider.replyemail
            where providerid = ? and appmeetup.status = 'Y'
            ",array($providerid)
        );
        if($row = pdo_fetch($result))
        {
            $meetupstatus = "blink";
        }
        return $meetupstatus;
    }
    
    function GetLiveNotifications($providerid)
    {
        global $global_icon_check;
        global $global_titlebar_color;
        global $global_textcolor;
        global $global_textcolor2;
        global $global_activetextcolor;
        global $menu_live;
        global $customsite;
        
        
        $notifytext = "";
        $timezone = $_SESSION['timezoneoffset'];
        $flag = "<img class='icon15 chatalert' title='Checked' src='../img/check-yellow-128.png' style='position:relative;top:3px' />";
        $flag = $global_icon_check;
        
        $result = pdo_query("1",
         
             "
             select 
             provider.avatarurl,
             DATE_FORMAT(date_add(chatmaster.created, 
             interval ($timezone)*(60) MINUTE), '%b %d %h:%i%p') as created, 

             chatmaster.chatid, 
             chatmaster.title,
             chatmaster.radiostation,
             chatmaster.broadcaster,
             chatmaster.broadcastmode,
             chatmaster.radiotitle,
             chatmaster.roomid,
             (select photourl from roominfo where chatmaster.roomid = roominfo.roomid ) as photourl,
             chatmaster.encoding,
             chatmaster.lastmessage,
             DATE_FORMAT(date_add(
                 chatmaster.lastmessage, interval (?)*(60) MINUTE), '%b %d %h:%i%p') as lastmessage2,

             (select count(*) from chatmembers 
                 where chatmembers.chatid = chatmaster.chatid ) as membercount,

             (select timestampdiff(SECOND, lastread, chatmaster.lastmessage ) from chatmembers 
                 where providerid = ? and
                      chatmembers.chatid = chatmaster.chatid) as diff, 

             (select lastread from chatmembers 
                 where providerid = ? and
                      chatmembers.chatid = chatmaster.chatid) as lastread,

             (select count(*) from chatmessage 
                 where chatmaster.chatid = chatmessage.chatid and status='Y') as chatcount,


             (select count(*) from chatmessage 
                 where chatmaster.chatid = chatmessage.chatid and 
                 chatmessage.providerid != ? and status='Y') as chatcountc,

             (select
             chatmembers.techsupport from chatmembers 
             where chatmaster.chatid = chatmembers.chatid and
             chatmembers.providerid = ? ) as techsupport,

             chatmaster.keyhash,
             provider.providername

             from chatmaster
             left join provider on chatmaster.broadcaster = provider.providerid
             where chatmaster.status='Y' and chatmaster.chatid in 
             (select chatid from chatmembers 
             where providerid = ? and status='Y' )
             and radiostation in ('Y','Q')
            and chatmaster.adminstation !='Y'
             
             and 
             ( chatmaster.broadcaster is not null 
               /*
               or
                (select timestampdiff(SECOND, lastread, chatmaster.lastmessage ) from chatmembers 
                    where providerid = ? and
                         chatmembers.chatid = chatmaster.chatid
                ) > 0
                */
             )
             order by lastmessage desc
             ",array($timezone,$providerid,$providerid,$providerid,$providerid,$providerid,$providerid)

         );
        $count = 0;
        while($row = pdo_fetch($result)){
            $count++;
        
            $title = htmlentities( DecryptText( $row['title'], $row['encoding'],$row['chatid'] ),ENT_QUOTES);
            $avatar = RootServerReplace($row['photourl']);

            $alert = "";
            if( ($row['diff'] > 0 || $row['lastread']==0) ){
                $alert = $flag;
            } 
            $radiotitle = stripslashes(base64_decode($row['radiotitle']));
            $livestatus = "Off Air";
            if($row['broadcaster']!=''){
                $livestatus = "<b style='color:$global_activetextcolor'>$menu_live</b>";
                if($row['broadcastmode']=='V'){
                    $livestatus = "<b style='color:$global_activetextcolor'>$menu_live</b>";
                }
            } else {
                $avatar = $row['photourl'];
                $radiotitle = "";
            }
            
            $notifytext .=
            "
            <div class='setchatsession mainbutton mainfont' 
                    data-chatid='$row[chatid]' data-keyhash='' 
                    style='float:left;display:block;cursor:pointer; 
                    background-color:transparent;color:$global_textcolor;
                    padding-top:0px;
                    padding-bottom:0px;
                    padding-left:0px;
                    padding-right:0px;
                    width:90%;;
                    margin:0px; 
                    text-align:left;'
                >
                    <table class='gridnorder'>
                        <tr style='vertical-align:top;text-align:left'>
                            <td>
                                <div class='circular gridnoborder icon30' style='margin-right:10px;overflow:hidden;vertical-align:top;position:relative;top:0px;'>
                                <img class='' src='$avatar' style='max-height:100%;;overflow:hidden;margin:0' />
                                </div>
                            </td>
                            <td style='word-break:break-all;word-wrap:break-word;'>
                                <span class='mainfont' style='color:$global_textcolor'>
                                    $livestatus<br>
                                    <b>$title</b> $row[providername]<br>
                                    <span class='mainfont' style='color:$global_textcolor2'>$radiotitle $alert</span>
                                </span>
                            </td>
                        </tr>
                    </table>
            </div>
            ";
        }
        if($count > 0){
            $notifytext .= "<div style='float:left;width:100%;min-width:320px;padding-bottom:40px'>";
            $notifytext .= "</div>";
            $notifytext .="<br><br>";
        }
        return $notifytext;
    }
    
    
    function GetBytzVPNNotifications($providerid)
    {
        global $global_icon_check;
        global $global_titlebar_color;
        global $global_textcolor;
        global $global_textcolor2;
        global $global_activetextcolor;
        global $global_background;
        global $menu_live;
        global $customsite;
        global $iconsource_braxcheck_common;
        
        $count=0;
        $notifytext = "";
        $timezone = $_SESSION['timezoneoffset'];
        $flag = "<img class='icon15 chatalert' title='Checked' src='../img/check-yellow-128.png' style='position:relative;top:3px' />";
        $flag = $global_icon_check;
        
        $result = pdo_query("1",
         
             "
            SELECT providerid, username, startdate 
            FROM braxproduction.bytzvpn where (status='Y' and datediff( date_add( startdate, interval  365 day), curdate() ) < 30
            and providerid = $providerid ) ",array($providerid)

         );
        if($row = pdo_fetch($result)){
            $count++;
        
        
            $title = "Your BytzVPN Subscription is Expiring";
            $notifytext .=
            "
            <div class='setchatsession mainbutton mainfont' 
                    data-chatid='' data-keyhash='' 
                    style='float:left;display:block;cursor:pointer; 
                    background-color:$global_background;color:$global_textcolor;
                    padding-top:0px;
                    padding-bottom:0px;
                    padding-left:0px;
                    padding-right:0px;
                    width:90%;;
                    margin:0px; 
                    text-align:left;'
                >
                    <table class='gridnorder'>
                        <tr style='vertical-align:top;text-align:left'>
                            <td>
                                <div class='circular gridnoborder icon30' style='margin-right:10px;overflow:hidden;vertical-align:top;position:relative;top:0px;'>
                                <img class='' src='$iconsource_braxcheck_common' style='max-height:100%;;overflow:hidden;margin:0' />
                                </div>
                            </td>
                            <td style='word-break:break-all;word-wrap:break-word;'>
                                <span class='mainfont' style='color:$global_activetextcolor'>
                                    <b>$title</b><br>
                                    <span class='mainfont' style='color:$global_textcolor2'>Please renew soon to avoid service interruption.</span>
                                </span>
                            </td>
                        </tr>
                    </table>
            </div>
            ";
        }
        if($count > 0){
            $notifytext .= "<div style='float:left;width:100%;min-width:320px;padding-bottom:40px'>";
            $notifytext .= "</div>";
            $notifytext .="<br><br>";
        }
        return $notifytext;
    }    
    
    function GetKudosNotifications($providerid)
    {
        global $global_icon_check;
        global $global_titlebar_color;
        global $global_textcolor;
        global $global_textcolor2;
        global $global_activetextcolor;
        global $global_bottombar_color;
        global $iconsource_braxgift_common;
        global $menu_gift;
        global $menu_thanks;
        
        $notifytext = "";
        $timezone = $_SESSION['timezoneoffset'];
        $flag = "<img class='icon15 chatalert' title='Checked' src='../img/check-yellow-128.png' style='position:relative;top:3px' />";
        $flag = $global_icon_check;
        
        $result = pdo_query("1",
         
             "
             select provider.providerid, tokens, provider.providername, provider.avatarurl, provider.profileroomid,
             tokens.method, tokens.xacdate,
             DATE_FORMAT(date_add(tokens.xacdate, 
             interval (?)*(60) MINUTE), '%m/%d/%y %h:%i%p') as xacdate2 
             from tokens
             left join provider on tokens.providerid = provider.providerid
             where
             tokens.owner = ?
             and datediff(now(),tokens.xacdate)<1
             union
             select provider.providerid, '0' as tokens, provider.providername, provider.avatarurl, provider.profileroomid,
             gifts.method,  gifts.xacdate,
             DATE_FORMAT(date_add(gifts.xacdate,
             interval (?)*(60) MINUTE), '%m/%d/%y %h:%i%p') as xacdate2 
             from gifts
             left join provider on gifts.providerid = provider.providerid
             where
             gifts.owner =  ? 
             and datediff(now(),gifts.xacdate)<1
             order by xacdate desc
             ",array($timezone,$providerid,$timezone,$providerid)

         );
        $count = 0;
        while($row = pdo_fetch($result)){
            
            if($count == 0){
                
            }
            $count++;
            $xacdate = InternationalizeDate($row['xacdate2']);
            
            $tokens = intval($row['tokens']);
            $message = "Tokens $tokens";
            $gift = 'money';
            if($row['method']=='kudos'){
                $message = $menu_gift;
                $gift = 'gift';
            }
            if($row['method']=='thanks'){
                $message = $menu_thanks;
                $gift = 'thanks';
            }
        

                $notifytext .=
                "
                <div class='smalltext2' 
                        style='float:left;display:block;cursor:pointer; 
                        background-color:transparent;color:black;
                        padding-top:0px;
                        padding-bottom:0px;
                        padding-left:0px;
                        padding-right:0px;
                        width:90%;max-width:150px;
                        margin:0px'; 
                        text-align:left;'
                    >
                        <table class='gridnorder'>
                            <tr class='' style='vertical-align:center;'>
                                <td style='vertical-align:center;text-align:center'>
                                    <div class='smalltext' 
                                        style='background-image:url($iconsource_braxgift_common);
                                            background-size:cover;width:50px;height:50px;
                                            text-align:center;vertical-align:bottom;
                                            position:relative;top:0px;
                                            overflow:hidden' >
                                            
                                        <div class='gridnoborder' 
                                            style='width:65%;height:65%;
                                            background-color:transparent;
                                            overflow:hidden;vertical-align:bottom;
                                            margin-top:15px;margin-left:auto;margin-right:auto;
                                            padding:0px;'>
                                            <center>
                                            
                                            <img class='feed mainbutton' src='$row[avatarurl]' 
                                                data-providerid='$row[providerid]' data-name='$row[providername]'    
                                                data-roomid='$row[profileroomid]'
                                                data-caller='home'
                                                data-mode ='S' data-title='' data-passkey64='' 
                                                style='width:100%;height:100%;overflow:hidden;margin:auto' />
                                            </center>
                                        </div>
                                    </div>   
                                </td>
                                <td class='$gift' style='word-break:break-all;word-wrap:break-word;'>
                                    <span class='mainfont' style='color:$global_textcolor'>
                                        <b>$message</b>
                                        <span class='smalltext2'> 
                                        <br>
                                        $xacdate<br>
                                        $row[providername] 
                                        </span>     
                                    </span>
                                </td>
                            </tr>
                        </table>
                </div>


                ";
        }
        if($count > 0){
            $notifytext .= "<div style='float:left;width:100%;min-width:320px;padding-bottom:40px'>";
            $notifytext .= "</div>";
            $notifytext .="<br><br><br><br>";
        }
        return $notifytext;
    }    
    
    function GetNotifications($providerid)
    {
        global $rootserver;
        global $prodserver;
        global $global_textcolor;
        global $global_textcolor2;
        global $global_bottombar_color;
        global $global_activetextcolor;
        global $global_background;
        global $menu_chat;
        global $menu_room;
        global $iconsource_braxclose_common;
        $beacon = "
            <div class='beaconcontainer' style='z-index:100;position:absolute;'>
                <div class='beacon' style='color:$global_activetextcolor;border-color:activetextcolor'></div>
            </div>
            ";

        $notifytext = "";
        $notifytext =  GetKudosNotifications($providerid);
        //$notifytext .=  GetLiveNotifications($providerid);
        $notifytext .=  GetBytzVPNNotifications($providerid);
        //return "";
        
        $homenotified = '';
        $notification_disable = '';
        $result = pdo_query("1","
            select DATE_FORMAT(homenotified,'%Y-%m-%d %H:%i') as homenotified, notification_disable from provider where provider.providerid = ?
            ", array($providerid));
        if($row = pdo_fetch($result))
        {
            if($row['homenotified']!==''){
                $homenotified = $row['homenotified'];
                
            }
            $notification_disable = $row['notification_disable'];
            if($notification_disable === 'Y'){
                return "";
            }
        }
        
        $result = pdo_query("1","
                select 
                DATE_FORMAT(date_add(notification.notifydate,
                    INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), 
                    '%m/%d/%y %h:%i%p') as notifydate2, 
                notifydate, notification.notifytype,  notification.notifysubtype,
                notification.roomid, 
                notification.providerid, provider.providername, roominfo.room, notification.chatid,
                notification.payload, notification.encoding, notification.reference, 
                provider.avatarurl, roominfo.photourl, provider.profileroomid, provider.stealth,
                (select 'Y' from notification n2 where notification.notifyid = n2.notifyid and notification.notifysubtype='LV') as livechannel
                from notification
                left join provider on provider.providerid = notification.providerid
                left outer join roominfo on notification.roomid = roominfo.roomid 
                
                where
                datediff(curdate(), notification.notifydate) < 3 and
                notification.recipientid = ?
                and notification.notifytype in ('RP','RL','CP')
                and provider.active = 'Y'
                and notification.notifydate > ?
                and notification.providerid not in 
                    (select blockee from blocked where
                    blocker = ?) 
                and notification.recipientid not in 
                    (select blockee from blocked where blocker = notification.providerid )
                order by notification.notifydate desc limit 100
            ",array($providerid,$homenotified, $providerid));
        
        
        $i1 = 0;
        $lastnotifydate = '';
        $lastnotifytype = '';
        $lastid = '';
        $lastcomment = '';
        $blink = '';
        while($row = pdo_fetch($result))
        {
            if($i1 == 0){
                $notifytext .=  "
                    &nbsp;
                    <img class='icon20 notifyclear' src='$iconsource_braxclose_common' style='cursor:pointer;padding-top:10px;' title='Clear Notifications' />
                    <br><br>
                 ";
                
            }
            
            $circular = 'circular';
            //circular2 = large icons
            //if($_SESSION['newbie']=='Y'){
            //    $circular = 'circular2';
            //}
            
            $notifydate = InternationalizeDate($row['notifydate2']);
            $avatar = RootServerReplace($row['avatarurl']);
            if($avatar == "$prodserver/img/faceless.png" || $row['avatarurl'] == ''){
                $avatar = "$rootserver/img/newbie2.jpg";
            }
            if($row['providerid']==1){
                $avatar = "$rootserver/img/techsupport-128.png";
                
            }
            if($_SESSION['daysactive']<2){
                $blink = $beacon;
            }
            if($row['notifytype']=='RP' && $row['notifysubtype']!='TK'){
                $notifytype = "Room";
                
                
                $result2 = pdo_query("1","select comment, encoding, providerid, owner, shareid from statuspost where postid = ? ",array($row['reference']));
                $postactive = false;
                $notifyComment = "";
                $shareid = '';
                if($row2 = pdo_fetch($result2)){
                    
                    $shareid = $row2['shareid'];
                    $notifyComment = strip_tags( html_entity_decode(DecryptPost($row2['comment'],$row2['encoding'],$row2['owner'],"" )));
                    //$notifyComment = removeEmoji($notifyComment );

                    $notifyComment = str_replace("&gt;","",$notifyComment);
                    $notifyComment = str_replace("&lt;","",$notifyComment);
                    $notifyComment = str_replace("<","",$notifyComment);
                    $notifyComment = str_replace(">","",$notifyComment);
                    
                    
                    $notifyComment = "- ".mb_substr($notifyComment, 0, 100)."...";
                    //$notifyComment = htmlspecialchars($notifyComment, ENT_NOQUOTES);
                    $postactive = true;
                }

                if($row['providername']==''){
                    $row['providername']='Anonymous';
                }
                $cleanReference = str_replace(".","",$row['reference']);

                    
                if($postactive){

                    $notifytext .=                         "
                    <div class='mainfont' 
                            style='float:left;display:block;cursor:pointer; 
                            background-color:transparent;color:black;
                            padding-top:3px;
                            padding-bottom:0px;
                            padding-left:0px;
                            padding-right:0px;
                            width:100%;
                            margin:0px'; 
                            text-align:left;'
                        >
                            <table class='gridnorder' style='position:relative'>
                                <tr style='vertical-align:top;text-align:left'>
                                    <td>
                                        <div class='$circular gridnoborder icon30' style='background-color:$global_bottombar_color;margin-right:10px;overflow:hidden;vertical-align:top;position:relative;top:0px;'>
                                            <img class='feed mainbutton' src='$avatar' 
                                                data-roomid='$row[roomid]' data-caller='home'
                                                data-reference='$cleanReference'
                                                style='max-width:100%;overflow:hidden;margin:0' />
                                        </div>

                                    </td>
                                    <td class='feed mainbutton' 
                                        data-roomid='$row[roomid]' data-caller='home'
                                        data-reference='$cleanReference' data-shareid='$shareid'
                                        style='word-break:break-all;word-wrap:break-word;'>
                                        <span class='pagetitle3' style='color:$global_textcolor'>
                                            <span style='color:$global_textcolor2'>$menu_room:</span><span> $row[room]</span>  
                                            <span class='smalltext2'>$notifydate</span> <br>
                                            $row[providername]
                                            <span class='smalltext2' style='color:$global_textcolor2'></span>
                                            <span class='mainfont' style='color:$global_textcolor'>$notifyComment</span>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                    </div>

                    ";
                }
                $lastid = $row['roomid'];

            }


            if($row['notifytype']=='CP' ){
                $notifytype = ucfirst(strtolower($menu_chat));
                
                if($row['stealth']=='Y' && $_SESSION['superadmin']=='Y'){
                    $avatar = "$rootserver/img/newbie2.jpg";
                    $row['providername']='anon';
                }
                
                
                $result2 = pdo_query("1","select status, keyhash, title, radiotitle, encoding, broadcaster from chatmaster where chatid = ? and status='Y' ",array($row['chatid']));
                $postactive = false;
                if($row2 = pdo_fetch($result2)){
                    $postactive = true;
                }
                $title = DecryptText($row2['title'], $row2['encoding'],$row['chatid']);
                $decrypted = removeEmoji( DecryptChat("$row[payload]", "$row[encoding]","$row[chatid]","" ));                
                $decrypted = str_replace("&gt;","",$decrypted);
                $decrypted = str_replace("&lt;","",$decrypted);
                $decrypted = str_replace("<","",$decrypted);
                $decrypted = str_replace(">","",$decrypted);
                $decrypted = htmlspecialchars($decrypted, ENT_NOQUOTES);
                if($decrypted == "Message"){
                    $decrypted = "";
                }
                $decrypted = "$row[providername]<br>".$decrypted;
                $radiotitle = stripslashes(base64_decode($row2['radiotitle']));
                
                if( $postactive &&
                    $decrypted != $lastcomment    
                ) {
                    $lastcomment = $decrypted;
                    if($row['notifysubtype']!='LV' ){
                    
                        
                        $notifytext .=
                        "
                        <div class='mainbutton mainfont' 
                                style='float:left;display:block;cursor:pointer; 
                                background-color:transparent;color:black;
                                padding-top:0px;
                                padding-bottom:0px;
                                padding-left:0px;
                                padding-right:0px;
                                width:100%;
                                margin:0px; 
                                text-align:left;'
                            >
                                <table class='gridnorder' style='position:relative'>
                                    <tr class='setchatsession mainbutton' 
                                        data-chatid='$row[chatid]' data-keyhash='$row2[keyhash]' 
                                        data-caller='home'
                                        style='vertical-align:top;text-align:left'>
                                        <td>
                                            $blink
                                            <div class='$circular gridnoborder icon30' style='background-color:$global_bottombar_color;margin-right:10px;overflow:hidden;vertical-align:top;position:relative;top:0px;'>
                                                <img class='' src='$avatar' 
                                                    style='max-width:100%;overflow:hidden;margin:0' />
                                            </div>
                                        </td>
                                        <td class='' 
                                           style='word-break:break-all;word-wrap:break-word;'>
                                            <span class='pagetitle3' style='color:$global_textcolor'>
                                                <span style='color:$global_textcolor2'>$notifytype: </span>
                                                <span>$title</span>
                                                <span class='smalltext2'> $notifydate</span><br>
                                                <span class='mainfont' style='color:$global_textcolor'>$decrypted</span>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                        </div>
                        ";
                    }
                }
                $lastid = $row['chatid'];
            }

            $lastnotifytype = $row['notifytype'];
            $lastnotifydate = $row['notifydate'];
            $i1++;
        }
        //return "<br><br>Count is $i1<br><br>";
        return $notifytext;
        if($_SESSION['superadmin']=='Y'){
            //$notifytext = "Test";
        }
        if($notifytext!==""){
        }
        return "";
    }
    
    function GetEformNotifications($providerid)
    {
        global $global_icon_check;
        global $global_titlebar_color;
        global $global_textcolor;
        global $global_textcolor2;
        global $iconsource_braxform_common;

        return "";
        //if($_SESSION['inforequest']!='Y'){
        //    return "";
        //}
        $result = pdo_query("1","select * from credentialformtrigger where providerid = ? ",array($provider));
        if(!$row = pdo_fetch($result)){
            return "";
        }
        
        $flag = $global_icon_check;
        

            
            $notifytext =
            "
            <div class='credentialformlist mainbutton mainfont' 
                    data-datanew='Y'
                    style='display:block;cursor:pointer; 
                    background-color:transparent;color:$global_textcolor;
                    padding-top:0px;
                    padding-bottom:0px;
                    padding-left:0px;
                    padding-right:0px;
                    width:90%;max-width:600px;
                    margin:0px; 
                    text-align:left;'
                >
                    <table class='gridnorder'>
                        <tr style='vertical-align:top;text-align:left'>
                            <td>
                                <div class='circular gridnoborder icon30' style='margin-right:10px;overflow:hidden;vertical-align:top;position:relative;top:0px;'>
                                <img class='' src='$iconsource_braxform_common' style='max-height:100%;;overflow:hidden;margin:0' />
                                </div>
                            </td>
                            <td style='word-break:break-all;word-wrap:break-word;'>
                                <span class='mainfont' style='color:$global_textcolor'>
                                    Forms Requested<br>
                                    <b>Important - Please fill out the requested forms</b>
                                    <span class='mainfont' style='color:$global_textcolor2'>$flag</span>
                                </span>
                            </td>
                        </tr>
                    </table>
            </div>
            <br><br>
            ";
        return $notifytext;
    }
        
    
    
    function removeEmoji($text)
    {
        
        return preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1FFFF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
        
    }
    
    ?>