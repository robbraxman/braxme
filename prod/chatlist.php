<?php
session_start();
require("validsession.inc.php");
require("nohost.php");
require_once("config.php");
require_once("crypt.inc.php");
require_once("internationalization.php");

$live_roomid = 898; //#LIVE

$time1 = microtime(true);

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $lasttime = @mysql_safe_string($_POST['lasttime']);
    $providerid = @mysql_safe_string($_POST['providerid']);
    $handle = @mysql_safe_string($_SESSION['handle']);
    $sort = @mysql_safe_string($_POST['sort']);
    $mode = @mysql_safe_string($_POST['mode']);
    $find = @mysql_safe_string($_POST['find']);
    
    if($mode == 'LIVE' ){
        SaveLastFunction($_SESSION['pid'],"L", 0);
    }
    if($mode == 'CHAT' ){
        SaveLastFunction($_SESSION['pid'],"C", 0);
    }
    
    
    if($mode !== 'LIVE'){
        $result = do_mysqli_query("1",
            "
            update notification set displayed = 'Y' where notifytype='CP' and displayed!='Y' and recipientid=$providerid
            ");
    }
    $result = do_mysqli_query("1",
        "
        update alertrefresh set lastnotified = null where providerid=$providerid and deviceid = '$_SESSION[deviceid]'
        ");


    $roomdiscovery = "";
    $result = do_mysqli_query("1",
            "select roomdiscovery from provider where providerid = $_SESSION[pid]");
    if($row = do_mysqli_fetch("1",$result)){
        $roomdiscovery = $row['roomdiscovery'];
        if($roomdiscovery == ''){
            $roomdiscovery = 'Y';
        }
    }

    
    $sorttext = SortButtons($sort);
    if($sort == ''){
    
        $sortorder = ' order by radiostation asc, broadcaster desc, lastmessage desc';
    } else {
        
        $sortorder = ' order by title,providername asc';
        
    }
    if($mode == 'LIVE'){
        $sortorder = ' order by radiostation asc, live desc, lastmessage desc';
        
    }
    
    $timezone = $_SESSION['timezoneoffset'];
    if($timezone==''){
        $timezone = '-7';
    }
    
    $livefilter = "";
    if($mode == 'LIVE' ){
        $livefilter = " and radiostation in ('Y','Q') ";
    }
    if($mode == 'CHAT' ){
        $livefilter = " and radiostation='' ";
    }
    $findfilter = "";
    if($find!=''){
        $findfilter = " 
        and
        chatmaster.chatid in 
        (select chatid from chatmembers 
        where
        status='Y' 
        and chatmembers.providerid in (select providerid from provider where chatmembers.providerid = provider.providerid and providername like '%$find%' )
        )
        and radiostation = ''
        ";
    }
    
    $limit = "";
    if($_SESSION['superadmin']=='Y'){
        $limit = "limit 100";
    }
    
   $result = do_mysqli_query("1",
    
        "
        select 
        DATE_FORMAT(date_add(chatmaster.created, 
        interval ($timezone)*(60) MINUTE), '%b %d %h:%i%p') as created, 
            
        chatmaster.chatid, 
        chatmaster.title,
        chatmaster.radiostation,
        chatmaster.broadcaster,
        chatmaster.broadcastmode,
        chatmaster.radiotitle,
        chatmaster.roomid,
        chatmaster.encoding,
        chatmaster.lastmessage,
        DATE_FORMAT(date_add(
            chatmaster.lastmessage, interval ($timezone)*(60) MINUTE), '%b %d %h:%i%p') as lastmessage2,

        (select count(*) from chatmembers 
            where chatmembers.chatid = chatmaster.chatid ) as membercount,

        (select timestampdiff(SECOND, lastread, chatmaster.lastmessage ) from chatmembers 
            where providerid = $providerid and
                 chatmembers.chatid = chatmaster.chatid) as diff, 
                 
        (select lastread from chatmembers 
            where providerid = $providerid and
                 chatmembers.chatid = chatmaster.chatid) as lastread,

        (select count(*) from chatmessage 
            where chatmaster.chatid = chatmessage.chatid and status='Y') as chatcount,
            
                
        (select count(*) from chatmessage 
            where chatmaster.chatid = chatmessage.chatid and 
            chatmessage.providerid != $providerid and status='Y') as chatcountc,
                
        (select
        chatmembers.techsupport from chatmembers 
        where chatmaster.chatid = chatmembers.chatid and
        chatmembers.providerid = $providerid ) as techsupport,
        
        chatmaster.keyhash,
        provider.providername,
        provider.stealth

        from chatmaster
        left join provider on chatmaster.owner = provider.providerid
        where chatmaster.status='Y' and chatmaster.chatid in 
        (select chatid from chatmembers 
        where providerid = $providerid and status='Y' )
        and (select count(*) from chatmessage where chatmaster.chatid = chatmessage.chatid) > 0
        $findfilter
        $livefilter
        $sortorder
        $limit
        "
           
    );
        /*
        (select count(*) from chatmessage 
            where chatmaster.chatid = chatmessage.chatid and 
            chatmessage.providerid = $providerid and status='Y') as chatcountr,
         * 
         */
$time2 = microtime(true);

   /*
    * 
    */
    //    and chatmembers.providerid=$providerid
   
 
    $backgroundcolor = '#3e4749';
    $headingcolor = '#a1a1a4';
    
    $headingcolor = '#3e4749';
    //$backgroundcolor = '#a1a1a4';
    $backgroundcolor = $global_backgroundreverse;
    
    $add = "<img class='unreadicon' src='../img/add-new-128.png' style='height:12px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $dot = "<img class='icon15' src='$iconsource_braxphone_common' style='position:relative;top:3px' />";
    //$flag = "<img class='icon15 chatalert' title='Checked' src='../img/check-yellow-128.png' style='position:relative;top:3px' />";
    $flagred = "<img class='icon15 chatalert' src='../img/info.png' style='position:relative;top:3px' title='Unread by recipient' />";
    $flag = $global_icon_check;
    
    $braxchat = "<img class='icon35' src='../img/braxchat.png' style=';padding-right:2px;padding-bottom:0px;margin:0' />";
    $braxchat2 = "<img src='../img/braxchat-square.png' style='position:absolute;top:0px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;margin:0' />";
    $techavatar = "$rootserver/img/agent.jpg";

    //<div class='divbutton3 divbutton_unsel textsend'>SMS Poke - Hey, Testing Only!</div>
    $id = $_SESSION['replyemail'];
    if( $handle!=''){
        $id = $handle;
    }

    
    
    
    $i1 = 0;
    $count = 0;
    $chatid = "";
    $noitems = 'N';
    $list = "";
    $listheading = "";
    
    //$chatfunc = "startchatbutton";
    $chatfunc = "starthyperchatbutton";
            
    if($mode != 'LIVE'){
    $list .= "
        <div class='pagetitle2a gridnoborder' 
            style='background-color:$global_titlebar_color;padding-top:0px;
            padding-left:20px;padding-bottom:3px;
            text-align:left;color:white;margin:0'> 
            
            <span style='opacity:.5'>
            $icon_braxchat2
            </span>
            <b>$menu_chats</b>
            <br>
        </div>
        <div class='gridnoborder chatlistarea' 
            style='background-color:transparent;color:$global_textcolor;padding-left:0px;margin:0;padding-top:5px'>
            <div style='padding:20px'>
                <div class='pagetitle3' style='display:inline;white-space:nowrap;;color:$global_textcolor;margin-right:10px'>
                    <img class='icon30 meetuplist' src='$iconsource_braxpeople_common' title='Find People' />
                </div>
                <div class='pagetitle3' style='display:inline;white-space:nowrap;;color:$global_textcolor'>
                    <span class='showhiddenarea' style='display:none'>
                        <br><br>
                    </span>
                    <img class='icon30 showhidden' src='$iconsource_braxfind_common' title='Find Existing Chat' style='' />
                    <span class='showhiddenarea' style='display:none'>
                        <input class='inputline dataentry mainfont' id='findchat' placeholder='$menu_name' name='findchat' type='text' size=20 value=''              
                            style='width:220px;padding-left:10px;;margin-bottom:10px;color:$global_textcolor'/>
                        <div class='mainfont selectchatlist' style='white-space:nowrap;display:inline;cursor:pointer;color:black' data-mode='F'>
                            <img class='icon25'   src='$iconsource_braxarrowright_common' 
                            style='top:3px' >
                        </div>
                    </span>    
                </div>
            </div>
                
            
            <div style='padding:10px;text-align:center;color:$global_textcolor'>
                <br>
                <div class='pagetitle' style='color:$global_textcolor'>
                    $menu_chats
                </div>
                <!--
                <div style='text-align:center'>
                    $sorttext
                </div>
                -->
            </div>
            ";
    }
                

    
    $count = 0;
    $listdetail = "";
    while($row = do_mysqli_fetch("1",$result)){
        
        if($count == 0){
            $listdetail .= "
            <div class='' 
                style='padding-top:0px;padding-right:20px;
                text-align:center;color:$global_textcolor;background-color:$global_background'>";
            
        }
    
        $header = false;
        
        if($row['encoding']!='' && $row['title']!=''){
            //$title='';
            $title = htmlentities( DecryptText( $row['title'], $row['encoding'],$row['chatid'] ),ENT_QUOTES);
        } else {
            $title = htmlentities($row['title'],ENT_QUOTES);
        }
        if($title ==''){
            $title = "";
            
            if( $row['techsupport']=='Y' ){
                $title = "Tech Support";
            }
        }
        
        $count++;

        if($row['radiostation']=='' ){
            $memberlist = DisplayChatMembers(
                $providerid, $row['chatid'], $title, $row['keyhash'], $row['diff'], $row['lastread'], 
                $row['chatcount'], $row['chatcountc'], $row['membercount'],
                $row['techsupport'], $headingcolor, $backgroundcolor, $flag, $techavatar, $row['roomid'], $count );
        } else 
        if($row['radiostation']=='Y'){
            $memberlist = DisplayRadio(
                $providerid, $row['chatid'], $title, $row['keyhash'], $row['diff'], $row['lastread'], 
                $row['chatcount'], $row['chatcountc'], $row['membercount'],
                $row['techsupport'], $headingcolor, $backgroundcolor, $flag, $techavatar, $row['roomid'], $row['broadcaster'], $row['radiotitle'], $row['broadcastmode']);
        } else 
        if($row['radiostation']=='Q'){
            $memberlist = DisplayQuiz(
                $providerid, $row['chatid'], $title, $row['keyhash'], $row['diff'], $row['lastread'], 
                $row['chatcount'], $row['chatcountc'], $row['membercount'],
                $row['techsupport'], $headingcolor, $backgroundcolor, $flag, $techavatar, $row['roomid'], $row['broadcaster'], $row['radiotitle'], $row['broadcastmode']);
        }
        
        $list .= $memberlist;
        

    }
    if($count > 0){
        
        $listdetail .= "</div>";
        $list .= $listdetail;
        
    }
    /*
    if($count == 50){
        $list .= "<br><br>Only 50 chats displayed. Use Search to find other chats<br>";
        
    }
     * 
     */
    
    if($mode == 'LIVE'){
        
        $listheading .= "

            <div class='pagetitle2a gridnoborder' 
                style='background-color:$global_titlebar_color;padding-top:0px;
                padding-left:20px;padding-bottom:3px;
                text-align:left;color:white;margin:0'> 

                <span style='opacity:.5'>
                $icon_braxlive2
                </span>
                <b>$menu_live</b>
                <br>
            </div>
            <div class='gridnoborder' style='background-color:transparent;width:100%'>
            ";
        
        $listheading .= "
            <br>
            </div>
            <div class='gridnoborder chatlistarea' 
                style='background-color:transparent;padding-left:0px;margin:0;color:$global_textcolor'>

                <div style='padding:10px;text-align:center;color:$global_textcolor'>
                    <div class='nonmobile' style='background-color:transparent'>
                        <br>
                    </div>
                    <div class='pagetitle' style='color:$global_textcolor;text-align:center;margin:auto'>
                        $menu_live
                    </div>
                </div>
                ";

    }
    
    
   /*
    * This idea of launching to the chat automatically (if unread) seems to not work right
    * if you want to go to some other chat discussion
    * If you have two discussions going, it may become difficult to switch back and forth
    * 
    */
    
    
    
    
    
    
        if($count == 0 && $find =='' && $mode !='LIVE'){
            
            $shadow = "shadow gridstdborder";
            if($icon_darkmode){
                $shadow = "";
            }
            
            $list .=
                 "
                    <div class='pagetitle3' 
                        style='padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            No open Chat found.<br><br>
                            Start a new chat by searching<br>
                            $icon_braxpeople2&nbsp;<span class='meetuplist' style='color:$global_activetextcolor;cursor:pointer' title='Go to PEOPLE'>PEOPLE</span>.<br><br>
                            Or tap a person's profile photo<br>anywhere in the app.
                        </div>
                        <br>
                    </div>
                    <br><br><br>
                    
                ";
        } else 
        if($count < 2 && $find =='' && $mode =='LIVE' && $roomdiscovery == 'Y'){

                
            $listheading .=
                 "  <center>
                     <br><br>
                    <br><br>
                    <div class='circular3 gridnoborder' style=';overflow:hidden;margin:auto'>
                        <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                    </div>
                    <div class='tipbubble pagetitle2a' style='max-width:200px;padding:30px;color:black;background-color:white' >
                        <div class='roomjoin pagetitle2a' style='display:inline;cursor:pointer;;color:$global_activetextcolor;' data-mode='R' data-roomid='1086' 
                             data-handle='#livestream' data-room='KBRX Livestream' data-action='RADIO2'>
                                <b>Join Live Channels</b>
                        </div>
                     to see the live broadcasts.
                    </div>
                    </center>
                ";
        } else 
        if($count == 0 && $find =='' && $mode =='LIVE' && $_SESSION['enterprise']!='Y' ){
            
            $list .=
                 "
                     <br><br>
                    <div class='pagetitle3 gridnoborder' style='color:$global_textcolor;margin:auto;max-width:500px;padding:20px;text-align:center'>
                        No broadcasts available.
                    <br><br><br>
                    <br><br><br>
                    </div>
                ";
            
        } else 
        if($count == 0  && $find =='' && $mode =='LIVE' && $_SESSION['enterprise']=='Y' ){
            
                    $list .=
                         "
                             <br><br>
                            <div class='pagetitle3 gridnoborder' style='color:$global_textcolor;margin:auto;max-width:500px;padding:20px;text-align:center'>
                                <div class='circular3' style='max-width:200px;overflow:hidden;margin:auto'>
                                    <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                                </div>
                                <div class='tipbubble pagetitle2' style='margin:auto;max-width:200px;padding:30px;color:black;background-color:whitesmoke'>
                                    No live channels for this $enterpriseapp account. You can create a live channel from a room.
                                </div>
                                <br><br><br>
                                Enable SOCIAL MEDIA
                                in My Account Info to see the public channels.
                            <br><br><br>
                            <br><br><br>
                            </div>
                        ";
                
        }
            
            
        
        
        
    
    if($mode =='LIVE' && $roomdiscovery == 'Y'){
        
        if($count == 0){
            
            $list .=
             "
                 
            ";
        }
        $list .= "
                        <br>
                            <br>
                            <br>
                        <div style='background-color:$global_bottombar_color;width:100%;text-align:center;margin:0'>
                            <br>
                            <div class='pagetitle3 gridnoborder feed' data-roomid='$live_roomid' data-caller='live' style='padding-left:20px;padding-right:20px;cursor:pointer;color:white;margin:auto;'>
                                    Share this with your friends! <a href='http://livestream.brax.me' style='text-decoration:none;color:$global_activetextcolor_reverse'>Livestream.brax.me</a>
                                    <br>
                            </div>
                            <br>
                            <div class='roomselect pagetitle3 gridnoborder' style='cursor:pointer;color:$global_activetextcolor_reverse;margin:auto;border:0' data-mode='S'>
                                    Join Public Live Channels
                                    <br>
                            </div>
                            <br>
                            <div class='audioreplay pagetitle3 gridnoborder' style='cursor:pointer;color:$global_activetextcolor_reverse;margin:auto;' data-mode='S'>
                                    Replays
                                    <br>
                            </div>
                            <br>
                            <div class='streamsched pagetitle3 gridnoborder' style='cursor:pointer;color:$global_activetextcolor_reverse;margin:auto;' data-mode='S'>
                                    Schedules
                                    <br>
                            </div>
                            <br>
                            <div class='pagetitle3 gridnoborder feed' data-roomid='$live_roomid' data-caller='live' style='cursor:pointer;color:$global_activetextcolor_reverse;margin:auto;'>
                                    Learn How to Broadcast #live
                                    <br>
                            </div>
                        <br><br>
                        <br>
                        <br>
                        <br>
                        <br>
                        </div>
                  ";

    } else
    if($mode =='LIVE' && $roomdiscovery !== 'Y'){
    
        $list .= "
                        <br>
                            
                        <div style='background-color:$global_bottombar_color;width:100%;text-align:center;margin:0'>
                            <br><br>
                            <div class='pagetitle3 gridnoborder feed' data-roomid='$live_roomid' data-caller='live' style='cursor:pointer;color:$global_activetextcolor_reverse;margin:auto;'>
                                    Learn How to Broadcast #live
                                    <br>
                            </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        </div>
                  ";
    }
    
    
    
$time3 = microtime(true);

$e1 = $time2 - $time1;
$e2 = $time3 - $time1;

    if($mode !='LIVE'){

        $list .= "
            <br>
            <div class='smalltext' 
                style='background-color:$global_bottombar_color;margin:auto;color:$global_activetextcolor_reverse;text-align:center'>
                <br><br>
                <div class='mainfont' style='margin:auto;text-align:center;max-width:500px;padding:20px;color:white'>To start a new chat, find the person under PEOPLE
                and select Start Chat from their Profile.
                </div>
                ";
        
        if($_SESSION['industry']=='medical'){
            $list .= "
                <br><br><br>
                <img src='../img/hipaa.png' style='height:70px' />
                ";
        }
        $list .= "
                <br><br><br>
                <br>
            </div>
            ";
    }

    $list = $listheading . $list;

    /*
if($providerid == 690001027){
    $list .= " <br><br>
        1 $e1
        <br>2 $e2
        
    ";
}
 */
    $list .="   
            </div>";
 
  
    
    
    $arr = array('list'=> "$list",
                 'chatid'=> "$chatid",
                 'noitems' => "$noitems"
                );
        
    
    echo json_encode($arr);
  

function SortButtons($sort)
{
    
    $sortcolor = "black";
    $unsortedcolor = "gray";
    $sortbackground = "white";
    $unsortedbackground = "transparent";
    if($sort == '' ){
    
        $color1 = $sortcolor;
        $color2 = $unsortedcolor;
        $background1 = $sortbackground;
        $background2 = $unsortedbackground;
    } else {
    
        $color1 = $unsortedcolor;
        $color2 = $sortcolor;
        $background2 = $sortbackground;
        $background1 = $unsortedbackground;
    }

    $sorttext = "   <br>
                    <div class='selectchatlist gridstdborder smalltext2 tapped' data-sort='' id='chatsort1'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:150px;
                        background-color:$background1'
                    >
                        <span style='color:$color1'>Timestamp Sort</span> 
                    </div>
                    <div class='selectchatlist gridstdborder smalltext2 tapped' data-sort='1' id='chatsort2'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:150px;
                        background-color:$background2'
                    >
                        <span style='color:$color2'>Name Sort</span> 
                    </div>
                    
                ";
    $sorttext = "";
    
    return $sorttext;
    
}
function DisplayChatMembers(
        $providerid, $chatid, $title, $keyhash, $diff, $lastread, 
        $chatcount, $chatcountc, $membercount,
        $techsupport, $headingcolor, $backgroundcolor, $flag, $techavatar, $roomid, $count)
{
    global $prodserver;
    global $rootserver;
    global $flagred;
    global $dot;
    global $icon_darkmode;
    global $global_textcolor;
    global $global_background;
    global $global_bottombar_color;
    global $iconsource_braxlock_common;
    global $menu_chats;
    
    $backgroundcolor = "$global_background";
    $list = "";
    $alert = "";
    $i1 = 0;
    
    if($roomid > 0){
        
            $avatar = "$rootserver/img/internetradio.png";
            if($roomid > 0 ){
                $result = do_mysqli_query("1", "select photourl from roominfo where roomid = $roomid ");
                if($row = do_mysqli_fetch("1",$result)){
                    $avatar = RootServerReplace($row['photourl']);
                }
            }
            if( ($diff > 0 || $lastread==0) ){
            
                $alert = " ".$flag;
                $i1++;
            } 
            
            $backgroundcolor = "$global_bottombar_color;";
            $color = "white";
            $opacity = "";
            
            
            $shadow = "shadow gridstdborder";
            if($icon_darkmode){
                $shadow = "";
            }
           
            
            $list = 
                "   
                <div class='smalltext2 setchatsession stdlistbox rounded $shadow noselect' 
                    id='setchatsession' 
                    data-chatid='$chatid'  
                    data-channelid=''
                    data-keyhash='$keyhash'
                    style='position:relative;display:inline-block;text-align:left;$opacity;
                    overflow:hidden;
                    color:$global_textcolor;background-color:$global_background;
                    cursor:pointer;font-weight:300;
                    margin-left:20px;margin-bottom:5px;
                    word-wrap:break-word' title='$title'>

                    <div style='float:left;
                        text-align:center;overflow:hidden;width:100%;
                        background-color:$backgroundcolor'>
                            <img class='chatlistphoto1 chatlistimg' src='$avatar' title'$title'
                                style='background-color:$backgroundcolor;width:auto;display:inline' />
                    </div>
                    <div class='chatlistboxtop smalltext' 
                        style='float:left;padding-top:0px;padding-bottom:0px;;width:100%;;
                        background-color:$global_background;color:$global_textcolor'>
                        <center>
                        Group Chat<br>
                        <b>$title</b>
                                <br>
                                &nbsp;&nbsp;$alert <span class='smalltext2' style='color:$global_textcolor;opacity:.5'>($chatcount)</span>
                                <br>
                        </center>
                    </div>
                    <div class='smalltext' title='mountpoint' style='float:left;color:$global_textcolor;text-align:center;width:100%'></div>
                </div>
             ";
            return $list;        
    }
    
    
    $stealth = '';
    if($_SESSION['superadmin']=='Y'){
        $stealth = '';
    }
        /*
         * 
         *  Search for Regular Members in Chat
         */
        //if($_SESSION['superadmin']!='Y'){
        $result3 = do_mysqli_query("1",
        "
             select provider.providername, provider.companyname, provider.avatarurl,
             chatmembers.lastmessage, chatmembers.techsupport, provider.stealth
             from chatmembers
             left join provider on chatmembers.providerid = provider.providerid
             where chatmembers.providerid !=$providerid and 
             chatmembers.chatid = $chatid and provider.active='Y' $stealth
             order by chatmembers.lastmessage desc limit 4
            "
        );
        
        $avatar = "";
        $alert="";
        $lock = "";
        $listavatar = "";
        $listname = "";
        $parties = 0;
        $techsupportflag = "";
        $header = false;
        $i1 = 0;
        while($row3 = do_mysqli_fetch("3",$result3)){
        
            $lock = "<img class='icon15' src='$iconsource_braxlock_common' style='' />";
            if($keyhash==''){
                $lock = '';
            }
            
            $avatar = RootServerReplace($row3['avatarurl']);
            if($avatar == "$prodserver/img/faceless.png"){
                $avatar = "$rootserver/img/newbie2.jpg";
            }
            if($row3['stealth']=='Y' && $_SESSION['superadmin']=='Y'){
                $avatar = "$rootserver/img/newbie2.jpg";
                $row3['providername']='...';
                $chatcount = 1;
            }
            $alert = "";
            
            //    $list .= "$row2[diff], $row2[lastread]<br>";
            if( ($diff > 0 || $lastread==0) ){
            
                $alert = " ".$flag;
                $i1++;
            } 
            if(
                    intval($chatcount)>0 && (
                    //I have not responded
                    //intval($row['chatcountr'])==0 || 
                    //Other has not responded
                    intval($chatcountc)==0 
                    )
            ){
            
                $alert = " ".$flagred;
            }
            
            if( $row3['companyname']!=''){
                $row3['companyname'] = "<br><span class='smalltext'>".$row3['companyname']."</span>";
            }
            
            //Header
            if($parties == 0 ){
            
                $techsupportflag = "";
                if( $techsupport=='Y' || $row3['techsupport']=='Y' ){
                
                    $techsupportflag = " $dot";
                }
                //New Chat ID
                $header = true;
                $shadow = "shadow gridstdborder";
                $extrastyle = '';
                if($icon_darkmode){
                    $shadow = "";
                    $extrastyle = 'filter:brightness(120%);';
                }
                
                $list .= 
                    "   
                    <div class='smalltext2 setchatsession tapped2 stdlistbox rounded $shadow noselect' 
                        id='setchatsession' 
                        data-chatid='$chatid' 
                        data-channelid='' 
                        data-keyhash='$keyhash'
                        style='position:relative;display:inline-block;text-align:left;$extrastyle;
                        overflow:hidden;
                        color:$global_textcolor;background-color:$backgroundcolor;
                        cursor:pointer;font-weight:300;
                        margin-left:20px;margin-bottom:5px;
                        word-wrap:break-word'>
                            <div style='float:left;
                                text-align:center;overflow:hidden;width:100%;
                                background-color:$global_background;color:$global_textcolor;'>

                    ";

                
            }
            $row3['providername']=substr($row3['providername'],0,20);
            if($row3['techsupport']=='Y'){
            
                $row3['providername']='Tech Support';
                $avatar = $techavatar;
            }
            //if($row3['providername']=='Scoperchat' && $providerid == 690001027){
            //    $row3['providername']='Tech Guy';
            //}

            if($parties == 0 && intval($membercount) <= 2 ){
            
                $list .= 
                "   
                                <div class='chatlistphoto1' style='vertical-align:center;overflow:hidden;background-color:$global_bottombar_color'>
                                <img class='chatlistimg' src='$avatar' 
                                    style='background-color:black;width:100%;height:auto;display:inline' />
                                </div>
                                <div>
                                    $row3[providername]
                                    <br>
                                </div>

                 ";
                $listavatar = "";
                $listname = "";
                
            } else 
            if($parties == 0){
            
                $list .= 
                "   
                                &nbsp;&nbsp;
                                <img class='chatlistphoto chatlistimg' src='$avatar' 
                                    style='background-color:black;width:auto;
                                    display:inline;margin-top:10px' />
                                <div>
                                    &nbsp;&nbsp;$row3[providername]
                                </div>

                 ";
                $listavatar = "";
                $listname = "";
            } else 
            if($parties == 1){
            
                $listavatar .= "&nbsp;&nbsp;<img  class='chatlistimg' src='$avatar' style='background-color:black;display:inline;height:30px;width:30px;' />";
                $listname .= 
                "   
                            <div>
                                &nbsp;&nbsp;$row3[providername]
                            </div>

                 ";
            } else
            if($parties == 2){
            
                $listavatar .= "<img class='chatlistimg' src='$avatar' style='background-color:black;display:inline;height:30px;width:30px;' />";
                $listname .= 
                "   
                            <div>
                                &nbsp;&nbsp;$row3[providername]
                            </div>

                 ";
            } else 
            if($parties == 3){
            
                $listavatar .= "<img class='chatlistimg' src='$avatar' style='background-color:black;display:inline;height:30px;width:30px;' />";
                $listname .= 
                "   
                            &nbsp;&nbsp;<b>...</b><br>
                 ";
                                
            }
            $parties++;
        }    
        $list .= $listavatar;
        $list .= $listname;
        /*
         * 
         *  Search for Invited Members in Chat
         */
        if($parties == 0){
        
            $result2 = do_mysqli_query("1",
            
                 "
                 select name, email from invites where providerid=$providerid and chatid=$chatid limit 1
                 "
            );
            if($row2 = do_mysqli_fetch("1",$result2)){
                $avatar = "$rootserver/img/newbie2.jpg";
                
                $header = true;
                
                $list .= 
                    "   
                    <div class='smalltext2 setchatsession tapped2 gridstdborder chatlistbox rounded' 
                        id='setchatsession' 
                        data-chatid='$chatid' data-keyhash='$keyhash'
                        style='position:relative;display:inline-block;text-align:left;
                        overflow:hidden;
                        color:$global_textcolor;background-color:$backgroundcolor;
                        cursor:pointer;font-weight:300;margin:5px;word-wrap:break-word'>
                        
                            <div class='roundedtop chatlistboxtop smalltext2' 
                                style='float:left;padding-top:7px;padding-bottom:10px;;width:100%;;
                                background-color:#5f5f5f;color:white'>
                                &nbsp;&nbsp;$title<br>&nbsp;&nbsp;$lock $alert $techsupport ($chatcount)  <br>
                            </div>
                            <div style='float:left;
                                text-align:center;overflow:hidden;width:100%;
                                background-color:$backgroundcolor;$global_textcolor'>

                                    <img class='chatlistphoto1' src='$avatar' style=';width:100%;display:inline' />
                                    <div>
                                        &nbsp;&nbsp;$row2[name]<br>$row2[email]<br>(Pending)
                                    </div>

                    ";
                
            }
        }        
        if($header){
            $list .= "
                            <div class='smalltext' 
                                style='float:left;padding-top:7px;padding-bottom:10px;;width:100%;;
                                background-color:$backgroundcolor;color:$global_textcolor;height:50%'>
                                &nbsp;&nbsp;$title
                                <br>
                                &nbsp;&nbsp;$lock $alert $techsupportflag <span class='smalltext2' style='color:$global_textcolor;opacity:.5'>($chatcount)</span>
                                <br>
                            </div>
                                        ";
            
            $list .= 
            "   
                        </div>
                        <div class='smalltext2' style='float:left;
                           color:$global_textcolor;text-align:center;
                           width:100%;
                           padding-left:10px;padding-right:10px;
                           padding-top:5px;padding-bottom:10px;
                           overflow:hidden'>
                           $row3[lastmessage]
                       </div>
                   </div>

             ";
        }
        
        
        return $list;
}
function DisplayRadio(
        $providerid, $chatid, $title, $keyhash, $diff, $lastread, 
        $chatcount, $chatcountc, $membercount,
        $techsupport, $headingcolor, $backgroundcolor, $flag, $techavatar, $roomid, $broadcaster, $radiotitle, $broadcastmode )
{
    global $prodserver;
    global $rootserver;
    global $icon_darkmode;
    global $global_bottombar_color;
    global $global_textcolor;
    global $menu_live;
    
    $broadcasttitle = stripslashes(substr(base64_decode($radiotitle),0,15));
    
            $alert = "";
            if( ($diff > 0 || $lastread==0) ){
            
                $alert = " ".$flag;
            } 
    
            $avatar = "$rootserver/img/internetradio.png";
            if($roomid > 0 ){
                $result = do_mysqli_query("1", "select photourl from roominfo where roomid = $roomid ");
                if($row = do_mysqli_fetch("1",$result)){
                    $avatar = $row['photourl'];
                }
            }
            
            $backgroundcolor = "$global_bottombar_color;";
            $color = "white";
            $opacity = "";
            
            $streamhash = substr(hash("sha1", $chatid),0,8);
            $streamid = "chat$streamhash";
            if($broadcaster!=''){
                $result = do_mysqli_query("1", "select providername from provider where providerid = $broadcaster ");
                if($row = do_mysqli_fetch("1",$result)){
                    $broadcastername = substr($row['providername'],0,15);
                }
                
                if($broadcastmode == ''){
                    $live = "<img class='icon15' src='../img/live-on-128.png' /> $alert
                            <br> 
                            <span class='smalltext2' style='color:white'>$broadcastername</span><br>
                            <span class='smalltext2' style='color:white'>$broadcasttitle</span><br>
                            ";
                } else {
                    $live = "<img class='icon15' src='../img/live-on-128.png' /> $alert
                            <br> 
                            <span class='smalltext2' style='color:white'>$broadcastername</span><br>
                            <span class='smalltext2' style='color:white'>$broadcasttitle</span><br>
                            ";
                    
                }
            } else {
                //$live = "<span class='smalltext' style='color:$global_textcolor'><br>Off Air<br>$alert<br></span>";
                $live = "<img class='icon15' src='../img/live-off-128.png' style='filter:brightness(50%)' /> $alert";
                $color = "$global_textcolor";
                $backgroundcolor = "transparent";
                $opacity = "opacity:0.6";
            }
            $shadow = "shadow gridstdborder";
            if($icon_darkmode){
                $shadow = "";
            }
           
            
            $list = 
                "   
                <div class='smalltext2 setchatsession chatlistbox rounded $shadow noselect' 
                    id='setchatsession' 
                    data-chatid='$chatid'  
                    data-channelid='$chatid'
                    data-keyhash='$keyhash'
                    style='position:relative;display:inline-block;text-align:left;$opacity;
                    overflow:hidden;
                    color:$color;background-color:$backgroundcolor;
                    cursor:pointer;font-weight:300;
                    margin-left:20px;margin-bottom:5px;
                    word-wrap:break-word' title='$title'>

                    <div style='float:left;
                        text-align:center;overflow:hidden;width:100%;
                        background-color:$backgroundcolor'>
                            <img class='chatlistphoto1' src='$avatar' title'$title'
                                style='background-color:$backgroundcolor;width:auto;display:inline' />
                    </div>
                    <div class='chatlistboxtop smalltext' 
                        style='float:left;padding-top:0px;padding-bottom:0px;;width:100%;;
                        background-color:$backgroundcolor;color:$color'>
                        &nbsp;&nbsp;<b>$title</b>
                        <!--
                        <br>&nbsp;&nbsp;ID: $streamid 
                        -->
                    </div>
                    <div class='smalltext' title='mountpoint' style='float:left;color:$color;text-align:center;width:100%'></div>
                        <div class='pagetitle3' style='float:left;color:$color;text-align:center;width:100%'>$live</div>
                </div>
             ";
            return $list;
            
}
function DisplayQuiz(
        $providerid, $chatid, $title, $keyhash, $diff, $lastread, 
        $chatcount, $chatcountc, $membercount,
        $techsupport, $headingcolor, $backgroundcolor, $flag, $techavatar, $roomid, $broadcaster, $radiotitle, $broadcastmode )
{
    global $prodserver;
    global $rootserver;
    global $icon_darkmode;
    
    //$broadcasttitle = stripslashes(substr(base64_decode($radiotitle),0,15));
    
            //$alert = "";
            //if( ($diff > 0 || $lastread==0) ){
            
                $alert = " ".$flag;
            //} 
    
            $avatar = "$rootserver/img/internetradio.png";
            if($roomid > 0 ){
                $result = do_mysqli_query("1", "select photourl from roominfo where roomid = $roomid ");
                if($row = do_mysqli_fetch("1",$result)){
                    $avatar = $row['photourl'];
                }
            }
            
            $streamhash = substr(hash("sha1", $chatid),0,8);
            $streamid = "chat$streamhash";
            
                
            $live = "<b class='blink' style='color:firebrick'>Live Quiz</b>
                    <br> 
                    <span class='smalltext' style='color:firebrick'>$title</span><br>
                    ";
            $shadow = "shadow gridstdborder";
            if($icon_darkmode){
                $shadow = "";
            }
                    
            
            $list = 
                "   
                <div class='smalltext2 setchatsession tapped2 chatlistbox rounded $shadow noselect' 
                    id='setchatsession' 
                    data-chatid='$chatid' 
                    data-channelid='$chatid'
                    data-keyhash='$keyhash'
                    style='position:relative;display:inline-block;text-align:left;
                    overflow:hidden;
                    color:black;background-color:white;
                    cursor:pointer;font-weight:300;
                    margin-left:20px;margin-bottom:5px;
                    word-wrap:break-word' title='$title'>

                    <div style='float:left;
                        text-align:center;overflow:hidden;width:100%;
                        background-color:white'>
                            <img class='chatlistphoto1' src='$avatar' title'$title'
                                style='background-color:white;width:auto;display:inline' />
                    </div>
                    <div class='roundedtop chatlistboxtop smalltext' 
                        style='float:left;padding-top:0px;padding-bottom:0px;;width:100%;;
                        background-color:white;color:black'>
                        &nbsp;&nbsp;<b>$title</b>
                    </div>
                    <div class='smalltext' title='mountpoint' style='float:left;color:black;text-align:center;width:100%'></div>
                        <div class='pagetitle3' style='float:left;color:#3d8da5;text-align:center;width:100%'>$live</div>
                </div>
             ";
            

            /*
            $list = 
                "   
                <div class='smalltext2 setchatsession tapped2 gridstdborder chatlistbox rounded shadow noselect' 
                    id='setchatsession' 
                    data-chatid='$chatid' data-keyhash='$keyhash'
                    style='position:relative;display:inline-block;text-align:left;
                    overflow:hidden;
                    color:black;background-color:white;
                    cursor:pointer;font-weight:300;margin:5px;
                    word-wrap:break-word' title='$title'>

                    <div style='float:left;
                        text-align:center;overflow:hidden;width:100%;
                        background-color:white'>
                            <img class='chatlistphoto1' src='$avatar' title'$title'
                                style='background-color:white;width:auto;display:inline' />
                    </div>
                    <br><br>
                    <div class='pagetitle3' style='float:left;padding-top:10px;color:#3d8da5;text-align:center;width:100%'>$live</div>
                </div>
             ";
             * 
             */
            return $list;
            
}
?>
