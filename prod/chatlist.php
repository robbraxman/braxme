<?php
session_start();
require("nohost.php");
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("internationalization.php");

if(ServerTimeOutCheck()){
    
    $arr = array('list'=> "",
                 'chatid'=> "",
                 'noitems' => "T"
                );
        
    echo json_encode($arr);
    
}
require("validsession.inc.php");

$mobile = '';
if($_SESSION['mobilesize']=='Y'){
    $mobile = 'Y';
}

$live_roomid = 898; //#LIVE


    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $lasttime = @tvalidator("PURIFY",$_POST['lasttime']);
    $providerid = @tvalidator("ID",$_POST['providerid']);
    $handle = @tvalidator("PURIFY",$_SESSION['handle']);
    $sort = @tvalidator("PURIFY",$_POST['sort']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $find = @tvalidator("PURIFY",$_POST['find']);
    
    SaveLastFunction($_SESSION['pid'],"C", 0);
    $result = pdo_query("1",
        "
        update provider set chatnotified = now() where providerid = ?
        ",array($providerid));
    $result = pdo_query("1",
        "
        update alertrefresh set lastnotified = null where providerid=? and deviceid = ?
        ",array($providerid,$_SESSION['deviceid']));
    

    $roomdiscovery = "";
    $result = pdo_query("1",
            "select roomdiscovery from provider where providerid = ?",array($_SESSION['pid']));
    if($row = pdo_fetch($result)){
        $roomdiscovery = $row['roomdiscovery'];
        if($roomdiscovery == ''){
            $roomdiscovery = 'Y';
        }
    }



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
    
    $braxchat = "<img class='icon35' src='../img/braxchat.png' style=';padding-right:2px;padding-bottom:0px;margin:0' />";
    $braxchat2 = "<img src='../img/braxchat-square.png' style='position:absolute;top:0px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;margin:0' />";
    $techavatar = "$rootserver/img/agent.jpg";

    //<div class='divbutton3 divbutton_unsel textsend'>SMS Poke - Hey, Testing Only!</div>
    $id = $_SESSION['replyemail'];
    if( $handle!=''){
        $id = $handle;
    }

    
   

    DisplayChatList($providerid, $mode, $find, $sort);
    exit();


    
function DisplayChatList($providerid, $mode, $find, $sort)
{    
    global $icon_darkmode;
    global $headingcolor;
    global $backgroundcolor;
    global $global_textcolor;
    global $global_activetextcolor;            
    global $global_backgroundreverse;
    global $global_background;
    global $global_bottombar_color;
    global $global_textcolor_reverse;
    global $iconsource_braxpeople_common;
    global $iconsource_braxrefresh_common;
    global $iconsource_braxpin_common;
    global $iconsource_braxfind_common;
    global $iconsource_braxtasks_common;
    global $iconsource_braxarrowright_common;
    global $menu_name;
    global $menu_chats;
    global $menu_community;
    global $rootserver;
    global $global_separator_color;
    
    $chatlimit = 200;
    $result = pdo_query("1",
            "select chatlimit from provider where providerid = ?",array($_SESSION['pid']));
    if($row = pdo_fetch($result)){
        $chatlimit = $row['chatlimit'];
        if($chatlimit < 100){
            $chatlimit = 100;
        }
        if($chatlimit > 2000){
            $chatlimit = 2000;
        }
    }
    
    
    $i1 = 0;
    $count = 0;
    $chatid = "";
    $noitems = 'N';
    $listheading = "";
    
    $time1 = microtime(true);
    $time2 = microtime(true);
    
    $techavatar = "$rootserver/img/agent.jpg";
    $sorttext = SortButtons($sort);

    $list = "";
    
    //$chatfunc = "startchatbutton";
    $chatfunc = "starthyperchatbutton";
            
    $list .= "
    <div class='gridnoborder chatlistarea' 
        style='background-color:transparent;color:$global_textcolor;padding-left:0px;margin:0;padding-top:5px'>
        <div style='padding-right:20px;padding-left:20px;padding-top:0px'>
            <div class='pagetitle3' style='display:inline;white-space:nowrap;;color:$global_textcolor;margin-right:10px'>
                <img class='icon30 meetuplist' src='$iconsource_braxpeople_common' title='Find People' />
            </div>
            ";
    if($mode == 'CHAT' || $mode ==''){
        $list .= "
                <div class='pagetitle3' style='display:inline;white-space:nowrap;;color:$global_textcolor;margin-right:10px'>
                    <img class='icon30 selectchatlist' data-mode='CHAT' src='$iconsource_braxrefresh_common' title='Refresh' />
                </div>
                
                <div class='pagetitle3' style='display:inline;white-space:nowrap;;color:$global_textcolor;margin-right:10px'>
                    <img class='icon30 selectchatlist' data-mode='SAVED' src='$iconsource_braxtasks_common' title='Saved Chats' />
                </div>
                ";
    }
    if($mode == 'SAVED'){
        $list .= "
                <div class='pagetitle3' style='display:inline;white-space:nowrap;;color:$global_textcolor;margin-right:10px'>
                    <img class='icon30 selectchatlist' data-mode='CHAT' src='$iconsource_braxrefresh_common' title='Refresh' />
                </div>
                ";
    }
    $pinned = "";
    if($mode == 'SAVED'){
        $pinned = "Saved";
    }
    $list .= "
                <div class='pagetitle3' style='display:inline;white-space:nowrap;;color:$global_textcolor'>
                    <span class='showhiddenarea' style='display:none'>
                        <br><br>
                    </span>
                    <img id='findchatbyname' class='icon30 showhidden' src='$iconsource_braxfind_common' title='Find Existing Chat' style='' />
                    <span class='showhiddenarea' style='display:none'>
                        <input class='inputline dataentry mainfont' id='findchat' placeholder='$menu_name' name='findchat' type='text' size=20 value=''              
                            style='width:220px;padding-left:10px;;margin-bottom:10px;color:$global_textcolor'/>
                        <div id='selectchatlistbutton' class='mainfont selectchatlist' data-mode='CHAT' style='white-space:nowrap;display:inline;cursor:pointer;color:black' data-mode='F'>
                            <img class='icon25'   src='$iconsource_braxarrowright_common' 
                            style='top:3px' >
                        </div>
                    </span>    
                </div>
            </div>
            ";
    $list .= "

            <div style='padding:20px;text-align:center;color:$global_textcolor'>
                <div class='pagetitle' style='color:$global_textcolor'>
                    $menu_chats $pinned
                </div>
            </div>
            ";
    if($mode!=='SAVED' && $_SESSION['roomdiscovery']=='Y'){
    $list .= "

            <div style='padding-bottom:20px;display:inline-block;width:90%'>
                <div class='mainfont roomselect' data-mode='JCOMMUNITY' style='float:right;cursor:pointer;margin-right:20px;color:$global_activetextcolor;'>$menu_community</div>
            </div>
            ";
    }
    
    $sortorder = ' order by pin desc, lastmessage desc';
    
    if($sort == ''){    
        $sortorder = ' order by pin desc, lastmessage desc';
    } else {
        
        $sortorder = ' order by title,providername asc';
        
    }
    if($_SESSION['superadmin']=='Y'){
        //$sortorder = ' order by diff desc';
        
    }
    
    
    $timezone = $_SESSION['timezoneoffset'];
    if($timezone==''){
        $timezone = '-7';
    }
    $modefilter = "";
    if($mode == 'SAVED'){
        $modefilter = 
         " and exists (select * from chatmembers 
            where providerid = $providerid and
                 chatmembers.chatid = chatmaster.chatid and pin='S') 
                 ";
                
        
    } else {
        //$modefilter = 
        // " and exists (select * from chatmembers 
        //    where providerid = $providerid and
        //         chatmembers.chatid = chatmaster.chatid and pin!='S') 
        //         ";
        
    }
    
    
    $findfilter = "";
    if($mode == 'PIN'){
        $findfilter = "
                and
                chatmaster.chatid in 
                (  select chatid from chatmembers 
                   where
                   pin='Y' and chatmembers.providerid = $providerid 
                )
            
                ";
    }
    if($find!=''){
        $findfilter = " 
        and
        (
            (
                chatmaster.chatid in 
                (  select chatid from chatmembers 
                   where
                   chatmaster.chatid = chatmembers.chatid and
                   status='Y' 
                   and chatmembers.providerid in 
                   (  select providerid from provider 
                      where  
                      ( providername like '%$find%' or handle like '@%$find%' )
                      and active='Y'
                   )
                )
                and ( chatmaster.roomid = 0 or chatmaster.roomid is null)
             ) 
             or 
             (
                chatmaster.roomid in 
                (  select roomid from roominfo where chatmaster.roomid = roominfo.roomid and
                   roominfo.room like '%$find%'
                )
             )
         )
        ";
    }
    
    $limit = "limit $chatlimit";
    if($_SESSION['superadmin']=='Y'){
    }
    
    $mobilequery = "
         (
         select concat(p2.providername,' ',p2.handle,'~',p2.avatarurl)
         from chatmembers
         left join provider p2 on chatmembers.providerid = p2.providerid
         where chatmembers.providerid !=$providerid and 
         chatmembers.chatid = chatmaster.chatid and p2.active='Y' 
         order by chatmembers.lastmessage desc limit 1
         ) as chatmembername,
         ";
        
    
   $result = pdo_query("1",
    
        "
        select 
        DATE_FORMAT(date_add(chatmaster.created, 
        interval (?)*(60) MINUTE), '%b %d %h:%i%p') as created, 
            
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
            chatmaster.lastmessage, interval (?)*(60) MINUTE), '%b %d %h:%i%p') as lastmessage2,
        chatcount,
        chatmembers as membercount,

        (select timestampdiff(SECOND, lastread, chatmaster.lastmessage ) from chatmembers 
            where providerid = ? and
                 chatmembers.chatid = chatmaster.chatid) as diff, 
                 
        (select lastread from chatmembers 
            where providerid = ? and
                 chatmembers.chatid = chatmaster.chatid) as lastread,
                 
        (select 'Y' from chatmembers 
            where providerid = ? and
                 chatmembers.chatid = chatmaster.chatid and pin='Y' ) as pin,

        (select 'S' from chatmembers 
            where providerid = ? and
                 chatmembers.chatid = chatmaster.chatid and pin='S' ) as saved,


                
        (select count(*) from chatmessage 
            where chatmaster.chatid = chatmessage.chatid and 
            chatmessage.providerid != ? and status='Y') as chatcountc,
            

                
        (select
        chatmembers.techsupport from chatmembers 
        where chatmaster.chatid = chatmembers.chatid and
        chatmembers.providerid = ? ) as techsupport,

        $mobilequery
            
        chatmaster.keyhash,
        provider.providername,
        provider.stealth

        from chatmaster
        left join provider on chatmaster.owner = provider.providerid
        where chatmaster.status='Y' and chatmaster.chatid in 
        (select chatid from chatmembers 
           where providerid = ? and status='Y'  
        )
        and radiostation = ''
        
        $modefilter
        $findfilter
        $sortorder
        $limit
        ",
           array($timezone,$timezone,
               $providerid,$providerid,
               $providerid, $providerid, 
               $providerid,$providerid,$providerid)
           
    );    

    
    $count = 0;
    $communitycount = 0;
    $listdetail = "";
    $pinlast = '';
    while($row = pdo_fetch($result)){
        
        if($count == 0){
            
            
            $listdetail .= "
            <div class='' 
                style='padding-top:0px;padding-right:10px;padding-left:10px;margin:auto;
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
        if($row['roomid']!==''){
            $communitycount++;
        }
        $saved = "$row[saved]";
        if($row['saved']=='S'){
            $saved = '(Saved)';
        }

        $memberlist = DisplayChatMembersMobile(
            $providerid, $row['chatid'], $title, $row['keyhash'], $row['diff'], $row['lastread'], 
            $row['chatcount'], $row['chatcountc'], $row['membercount'],
            $row['techsupport'], $headingcolor, $backgroundcolor, $techavatar, $row['roomid'], $count, $row['chatmembername'], $row['pin'],$saved );
        
        if($count > 0 && $pinlast == 'Y' && $row['pin']!=='Y' ){
            $list .= "<hr style='opacity:50%;border:1px solid  $global_separator_color'>";       
        }
        $pinlast = $row['pin'];
        
        
        $list .= $memberlist;
        

    }
    if($count > 0){
        
        $listdetail .= "</div>";
        $list .= $listdetail;
        
    }
    
    
   /*
    * This idea of launching to the chat automatically (if unread) seems to not work right
    * if you want to go to some other chat discussion
    * If you have two discussions going, it may become difficult to switch back and forth
    * 
    */
    
    
    
    if($count == 0 && $find =='' ){

        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "";
        }

    } 
            
    
    
    $time3 = microtime(true);

    $e1 = $time2 - $time1;
    $e2 = $time3 - $time1;

    if($mode !='LIVE'){
        
        $community_text = "Tap on 'Community' above to join open community chats.<br><br>";
        if($_SESSION['roomdiscovery']!='Y' || $communitycount > 0){
            $community_text = "";
        }

        //Tip
        if($communitycount > 0){
            $community_text = "";
        }
        if($mode!=='SAVED' && ($count == 0 || $communitycount < 2 )){

            $list .= "
                <br>
                    <div class='circular3' style=';overflow:hidden;margin:auto'>
                        <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                    </div>
                    <div class='smalltext tipbubble' 
                        style='background-color:$global_bottombar_color;margin:auto;color:$global_textcolor_reverse;text-align:center;max-width:300px'>
                        <div class='pagetitle3' style='margin:auto;text-align:center;max-width:500px;padding:20px;color:$global_textcolor_reverse'>
                            $community_text
                            To start a new chat with a specific person, find the person under PEOPLE
                            from the menu and select Start Chat from that person's Profile.
                        </div>
                    </div>
                ";
        }
        if($mode!=='SAVED' && $count > 0){
            $list .= "
                <br><br>
                <div class=smalltext style='padding-left:20px;color:$global_textcolor'>
                Items Display LImit: <input id=chatdisplaylimit class='chatdisplaylimit dataentry' type=numeric value=$chatlimit style='width:50px' />&nbsp;&nbsp;  
                <img class='icon20 setchatdisplaylimit'  src='$iconsource_braxarrowright_common' />
                </div>
                ";
        }
        
        $list .= "
                <br>
            </div>
            ";
    }

    $list = $listheading . $list;

    $list .="   
            </div>";
 
  
    
    
    $arr = array('list'=> "$list",
                 'chatid'=> "$chatid",
                 'noitems' => "$noitems"
                );
        
    
    echo json_encode($arr);
}

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


function DisplayChatMembersMobile(
        $providerid, $chatid, $title, $keyhash, $diff, $lastread, 
        $chatcount, $chatcountc, $membercount,
        $techsupport, $headingcolor, $backgroundcolor,  $techavatar, $roomid, $count, $chatmemberraw, $pin,$saved)
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
    global $global_icon_check;
    global $global_icon_heart;
    global $global_icon_pin;
    global $global_icon_pin_gray;
    global $global_textcolor_reverse;
    
    
    
    $tmp = explode('~',$chatmemberraw);
    $chatmembername = $tmp[0];
    $avatarurl = "";
    if(isset($tmp[1])){
        $avatarurl = $tmp[1];
    }
    
    //$backgroundcolor = "$global_bottombar_color";
    $backgroundcolor = $global_background;
    $list = "";
    $alert = "";
    $i1 = 0;
    
    $lock = "<img class='icon15' src='$iconsource_braxlock_common' style='' />";
    if($keyhash==''){
        $lock = '';
    }
    
    $alert = "";
    if( $pin == 'Y'){
        $alert = " ".$global_icon_pin_gray;
        
    }
    
    if( ($diff > 0 || $lastread==0) ){

        $alert = " ".$global_icon_check;
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
    $chatcounttext = "($chatcount)";
    if($chatcount == ""){
        $chatcounttext = "No Response";
    }
    
    $extrastyle = '';
    if($icon_darkmode){
        $shadow = "";
        $extrastyle = 'filter:brightness(120%);';
    }
    $shadow = "shadow gridstdborder";
    if($icon_darkmode){
        $shadow = "";
    }
    
    
    if($roomid > 0){
        
            $avatar = "$rootserver/img/internetradio.png";
            if($roomid > 0 ){
                $result = pdo_query("1", "select photourl from roominfo where roomid = ? ",array($roomid));
                if($row = pdo_fetch($result)){
                    $avatar = RootServerReplace($row['photourl']);
                }
            }
            if( ($diff > 0 || $lastread==0) ){
                if( $pin == 'Y'){
                    $alert = " ".$global_icon_pin;
                } else {
                    $alert = " ".$global_icon_check;
                }
                $i1++;
            } 
            
            //$backgroundcolor = "$global_bottombar_color;";
            $color = "white";
            $opacity = "";
            
            
           
            
            $list = 
                "   
                <div class='smalltext2 setchatsession rounded $shadow noselect' 
                    id='setchatsession' 
                    data-chatid='$chatid'  
                    data-channelid=''
                    data-keyhash='$keyhash'
                    style='width:90%;max-width:300px;position:relative;display:inline-block;text-align:left;$opacity;$extrastyle;
                    overflow:hidden;
                    color:$global_textcolor;background-color:$backgroundcolor;
                    cursor:pointer;font-weight:300;
                    margin-left:10px;margin-bottom:5px;;
                    word-wrap:break-word' title='$title'>
                    <table style='padding-left:0px;gridnoborder;width:100%'>
                    <tr>
                        <td style='width:50px;background-color:$backgroundcolor'>
                            <div class='circular gridnoborder' style='background-color:black;overflow:hidden'>
                                  <img class='' src='$avatar' style='margin:0;height:100%;max-width:100%' />
                            </div>
                        </td>
                        <td style='width:200px'>
                            <div class='smalltext' style='
                                width:200px;
                                text-align:left;overflow:hidden;width:100%;padding-left:5px;padding-top:5px;
                                padding-bottom:5px;
                                background-color:$backgroundcolor;color:$global_textcolor'>
                                <span class='smalltext' style='color:$global_textcolor;'><b>$title</b></span><br>
                                $chatmembername
                                <br>
                                <span class='smalltext2' style='color:$global_textcolor;opacity:.5'>$chatcounttext $saved</span>
                                <br>

                            </div>
                        </td>
                        <td style='text-align:right;width:50px;padding-right:10px'>
                            $alert     
                        </td>
                    </tr>
                    </table>
                </div>
             ";
            return $list;        
    }
    


    //    $list .= "$row2[diff], $row2[lastread]<br>";
            
            

    //New Chat ID
    $header = true;
                
    $list .= 
        "   
        <div class='smalltext2 setchatsession tapped2 rounded $shadow noselect' 
            id='setchatsession' 
            data-chatid='$chatid' 
            data-channelid='' 
            data-keyhash='$keyhash'
            style='width:90%;;max-width:300px;position:relative;display:inline-block;text-align:left;$extrastyle;
            overflow:hidden;
            color:$global_textcolor;background-color:$backgroundcolor;
            cursor:pointer;font-weight:300;
            margin-left:10px;margin-bottom:5px;
            word-wrap:break-word'>

        ";

                
    //$chatmembername=substr($chatmembername,0,20);

    if(intval($membercount) <= 2 ){
        $chatmembertext = $chatmembername;

    } else {
        $memberothers = $membercount - 1;
        $chatmembertext =  "$chatmembername +$memberothers others";
    } 
    

    /*
     * 
     *  Search for Invited Members in Chat
     */
    if($membercount == 1){

        $result2 = pdo_query("1",

             "
             select name, email from invites where providerid=? and chatid=? limit 1
             ",array($providerid, $chatid)
        );
        if($row2 = pdo_fetch($result2)){
            $avatar = "$rootserver/img/newbie2.jpg";

            $header = true;

            $list .= 
                "   
                <div class='smalltext2 setchatsession tapped2 gridstdborder rounded' 
                    id='setchatsession' 
                    data-chatid='$chatid' data-keyhash='$keyhash'
                    style='position:relative;display:inline-block;text-align:left;
                    overflow:hidden;
                    color:$global_textcolor;background-color:$backgroundcolor;
                    cursor:pointer;font-weight:300;margin:5px;word-wrap:break-word'>

                        <div class='roundedtop smalltext' 
                            style='float:left;padding-top:7px;padding-bottom:10px;;width:100%;;
                            background-color:$backgroundcolor;color:$global_textcolor'>
                            &nbsp;&nbsp;$title<br>&nbsp;&nbsp;$lock $alert $techsupport $chatcounttext $saved<br>
                        </div>
                        <div style='float:left;
                            text-align:center;overflow:hidden;width:100%;
                            background-color:$backgroundcolor;color:$global_textcolor'>

                                <div class='pagetitle3'>
                                    &nbsp;&nbsp;$row2[name]<br>$row2[email]<br>(Pending)
                                </div>

                ";

        }
    }
    if($header){

        if($title == ''){
            $title = 'Private Chat';

        }
        $list .= "
                    <table style='padding-left:0px;gridnoborder;width:100%'>
                    <tr>
                        <td style='width:50px;background-color:$backgroundcolor'>
                            <div class='circular gridnoborder' style='background-color:black;overflow:hidden'>
                                <img class='' src='$avatarurl' style='height:100%;background-color:black;margin:0;max-width:100%' />
                            </div>
                        </td>
                        <td style='width:200px'>
                            <div class='smalltext' style='
                                width:200px;
                                text-align:left;overflow:hidden;width:100%;padding-left:5px;padding-top:5px;
                                padding-bottom:5px;
                                background-color:$backgroundcolor;color:$global_textcolor'>
                                <span class='smalltext' style='color:$global_textcolor;'>$chatmembertext</span><br>
                                <b>$title</b>
                                <br>
                                <span class='smalltext2' style='color:$global_textcolor;opacity:.5'>$chatcounttext $saved</span>
                                <br>

                            </div>
                        </td>
                        <td style='text-align:right;width:50px;padding-right:10px'>
                            $alert $lock   
                        </td>
                    </tr>
                    </table>
                ";

        $list .= 
        "   
       </div>

         ";
}
        
        
return $list;

}

?>
