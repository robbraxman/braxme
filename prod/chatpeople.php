<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

    $providerid = @tvalidator("ID",$_POST['providerid']);
    $find = rtrim(@tvalidator("PURIFY",$_POST['find']));
    $chatid = rtrim(@tvalidator("ID",$_POST['chatid']));
    
    SaveLastFunction($_SESSION['pid'],"C", $chatid);

    //$providerid = $admintestaccount;
    //$chatid = 1217;

    /*****************************
     * 
     * 
     *    MAIN
     * 
     */

        
    $list = "
        <div class='gridnoborder suspendchatrefresh' style='padding:0px;margin:0;background-color:$global_background'>
            ";


    $list .= Title();
    $list .="   
            <div style='padding-left:30px;padding-right:30px;padding-top:0px;padding-bottom:50px;margin:0px;text-align:left;background-color:$global_background;color:$global_textcolor'>
            <br>
        ";
    
    $list .= MemberList($providerid, $find, $chatid);    

    $list .="   
            </div>
        </div>";

    /* $mode is configured/reset in Buttons() */
    
    echo $list;
    exit();


    
    
    
    

    function Title()
    {
        global $appname;
        global $global_titlebar_color;
        global $icon_braxpeople2;
        global $chatid;
        global $icon_braxchat2;
        
        
        $backgroundcolor = $global_titlebar_color;
        $list = "
                <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                    <img class='icon20 setchatsession' data-chatid='$chatid' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                        style='' />
                    &nbsp;
                    &nbsp;
                    <span style='opacity:.5'>
                    </span>
                    <span class='pagetitle2a' style='color:white'>Members</span> 
                </div>
            ";
        return $list;
        
    }

    
    function MemberList($providerid, $find, $chatid)
    {
        global $appname;
        global $rootserver;
        global $global_separator_color;
        global $icon_darkmode;
        global $admintestaccount;
        
        $timezoneoffset = $_SESSION['timezoneoffset'];
        
        $list = "
            <span class='meetuppublicshow' style='display:none;background-color:white;color:black'>
                <div class='pagetitle2a' style='padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:5px'>
                    <b>CHAT MEMBERS</b>
                    <br>
                                
                    <input class='showhidden inputline dataentry mainfont chatmemberfind' id='chatmemberfind' name='chatmemberfind' type='text' size=20 value='$find'              
                        placeholder='Find Name or @handle'
                        style='max-width:200px;background-color:transparent;padding-left:5px;'/>
                        <img class='showhiddenarea icon20 meetuplist' data-mode='P1' src='../img/Arrow-Right-in-Circle_120px.png' title='Start Search'
                        style='display:none;top:8px' >
                </div>
            </span>
            ";

        
        
        $result = pdo_query("1","
        select provider.providername, provider.providerid as otherid,  
        provider.replyemail as otheremail, provider.avatarurl, 
        provider.handle, chatmembers.techsupport, chatmembers.broadcaster,
        provider.profileroomid, chatmaster.keyhash, 
        (select 'Y' from ban where ban.banid = provider.banid and ban.chatid = chatmaster.chatid ) as banned,
        chatmaster.owner, chatmaster.radiostation, provider.publishprofile,
        DATE_FORMAT(
           date_add(chatmembers.lastread, interval (?)*(60) MINUTE),
            '%b %d/%y %a  %h:%i:%s%p'
        ) as seen,
        chatmembers.lastread, chatmembers.techsupport,
        timestampdiff( HOUR, now(), chatmembers.lastread) as hourdiff,
        (select count(*) from chatmembers c2 where c2.chatid = ? and c2.broadcaster is not null and c2.broadcaster > 0 ) as listenercount
        from chatmembers
        left join chatmaster on chatmaster.chatid = chatmembers.chatid
        left join provider on provider.providerid = chatmembers.providerid 
        where chatmembers.chatid = ?
        and provider.active = 'Y' and termsofuse is not null
        order by chatmembers.lastread desc 
        limit 1000
            
            
                ",array($timezoneoffset,$chatid,$chatid));
        $count = 0;
        $joined = "";
        $otherid = "";
        $techsupport = "";
        while($row = pdo_fetch($result)){
            if($row['techsupport']=='Y'){
                $techsupport = 'Y';
            }
            if($row['otherid']!=$providerid && $count < 3){
                $otherid = $row['otherid'];
            }
            
            if($count == 0){

            }
            $count++;
            $id = $row['handle'];
            if($id == ''){
                $id = $row['otheremail'];
            }
            if($find=='@%'){
                $joined = "<br>".$row['joined'];
            }

             $avatar = $row['avatarurl'];
             if($avatar == "$rootserver/img/faceless.png" || $avatar == ''){
                 $avatar = "$rootserver/img/newbie2.jpg";
             }
             
             if($row['publishprofile']!=''){
                 $row['publishprofile']= "<img class='icon15' src='../img/Profile_120px.png' style='margin-top:5px;opacity:.3' title='this user has a profile'/>";
             }
             
            $profileaction = 'feed';
            if(intval($row['profileroomid'])==0){
                $profileaction = 'userview';
            }

            $deleteaction = "";
                $deleteopacity = "opacity:0.3;";
            if($row['owner']==$providerid || $row['otherid']==$providerid){
                $deleteaction = "chatdeleteparty";
                $deleteopacity = "cursor:pointer;opacity:1.0;";
            }
            $radio = 'L';
            if($row['radiostation']=='Y'){
                $radio = 'R';
            }
            $deletebutton = " 
                <img class='$deleteaction smalltext2 icon15' 
                    title='Delete from Chat'
                    src='../img/delete-circle-128.png'
                    data-chatid='$chatid' 
                    data-providerid='$row[otherid]' 
                    data-mode='$radio'
                    style='padding-left:20px;float:left;$deleteopacity' 
                     />
                     ";
            
            if($row['banned']=='Y'){
                $shadowban = " 
                    <img class='managefriends smalltext2 icon15' 
                        title='Unban from Chat'
                        src='../img/add-circle-128.png'
                        data-chatid='$chatid' 
                        data-friendid='$row[otherid]' 
                        data-mode='BAN'
                        style='padding-left:20px;float:left;' 
                         />
                         ";
            } else {
                $shadowban = " 
                    <img class='managefriends smalltext2 icon15' 
                        title='Shadow Ban from Chat'
                        src='../img/arrow-circle-down-128.png'
                        data-chatid='$chatid' 
                        data-friendid='$row[otherid]' 
                        data-mode='BAN'
                        style='padding-left:20px;float:left;' 
                         />
                         ";
                
            }
            if($_SESSION['superadmin']!='Y'){
                $shadowban = '';
            }
            
            
            $keyresend = "";
            if($row['keyhash']!=''){
                $keyresend = 
                        "
                        <div class='smalltext addchatsession'
                            data-chatid='$chatid' data-providerid='$row[otherid]' data-mode='S'
                            title='Resend Private Key'
                            style='float:right;cursor:pointer;color:$global_separator_color;padding-top:0px;margin-left:20px;vertical-align:center;margin-right:20px'>
                            <img class='icon15' src='../img/Key-Lock_120px.png' />
                        </div> 
                        ";
            }
            if($row['broadcaster']>0){
                $keyresend = 
                        "
                        <div class='smalltext' style='color:black'><img class='icon15' src='../img/Headphones_120px.png' style='margin-left:20px;opacity:.2;float:right;margin-right:20px' title='Listening'/></div>
                        ";

            }
            $shadow = "gridstdborder shadow";
            if($icon_darkmode){
                $shadow = "";
            }
            
            
            $list .= "
                <div class='rounded stdlistbox rounded $shadow' 
                    style='display:inline-block;vertical-align:top;text-align:left;background-color:white;margin-bottom:10px;
                    word-wrap:break-word;overflow:hidden;'>
                            <div class='gridnoborder $profileaction' style='cursor:pointer;color:black;padding:15px;;overflow:hidden'
                             data-providerid='$row[otherid]' data-name='$row[providername]'    
                             data-roomid='$row[profileroomid]'
                             data-caller='leave'
                             data-mode ='S' data-title='' data-passkey64='' 
                             >
                                <div class='circular2' style='overflow:hidden;background-color:#a3a3a3;max-height:90%'  title='User Photo'>
                                    <img class='' src='$avatar' style='height:auto;width:100%;'/>
                                </div>
                               $row[providername]<br><span class='smalltext' style='color:gray'>$id $joined
                                <br> $row[seen]</span>
                            </div>
                    $deletebutton $shadowban $keyresend                          
                </div>  
                ";
        }    
        if($_SESSION['superadmin']=='Y'){
            if($count <= 2 ){
                $list .= GetTechNotes($otherid, $chatid);

            }
        }
        $list .= GetFormData( $otherid );
        
        return $list;
    }    
    function GetTechNotes( $otherid, $chatid )
    {
        if($otherid == $admintestaccount){
            return "";
        }
        $technotes = "";
        $result = pdo_query("1","   
            select providerid, useragent, deviceheight, devicewidth, pixelratio, industry, enterprise, notifications,
                notificationflags,
                providername, name2, alias, replyemail, handle, createdate, devicecode,
                (select 'Y' from ban where ban.chatid = $chatid and ban.banid = provider.banid ) as banned,
                (select count(*) from photolib where photolib.providerid = provider.providerid) as photocount,
                (select count(*) from filelib where filelib.providerid = provider.providerid and filelib.status='Y') as filecount,
                (select count(*) from chatmessage where chatmessage.providerid = provider.providerid) as chatcount,
                (select count(*) from statuspost where statuspost.providerid = provider.providerid) as roomcount

                from provider where providerid = $otherid and superadmin is null and techsupport = ''
                ",null);
        if($row = pdo_fetch($result)){
            $technotes .= "<div class='smalltext' style='padding:10px'>";
            $technotes .= "Name $row[providername] - $otherid chatID $chatid<br>";
            $technotes .= "Name2 $row[name2]<br>";
            $technotes .= "Alias $row[alias]<br>";
            $technotes .= "Handle $row[handle]<br>";
            $technotes .= "Email $row[replyemail]<br>";
            $technotes .= "Created $row[createdate]<br>";
            $technotes .= "UserAgent $row[useragent]<br>";
            $technotes .= "Device Specs  $row[devicewidth]x$row[deviceheight]/$row[pixelratio]<br>";
            $technotes .= "Device Code  $row[devicecode]<br>";
            $technotes .= "Enterprise $row[enterprise] - $row[industry]<br>";
            $technotes .= "Notifications $row[notifications] Exclusions- $row[notificationflags]    <br>";
            $technotes .= "Photos/Files $row[photocount]/$row[filecount]<br>";
            $technotes .= "ChatCount/RoomCount $row[chatcount]/$row[roomcount]<br>";
            $technotes .= "Banned $row[banned]<br>";
            $technotes .= "<br>";

            $result = pdo_query("1","   
                select platform, registered, status, error from notifytokens where providerid = ? 
                ",array($otherid));
            while($row = pdo_fetch($result)){
                $token = "NotifyToken $row[platform] - $row[registered] S=$row[status] $row[error]<br>";
                $technotes .= $token;
            }
            $technotes .= "<br><br><br></div>";


            return $technotes;

        }
        return "";
    }
    function GetFormData( $otherid )
    {
        return "";
        if($otherid == $admintestaccount){
            return "";
        }
        $notes="";
        $result = pdo_query("1","   
                ");
        if($row = pdo_fetch($result)){
            $technotes .= "<br><br><br></div>";

            return $notes;

        }
        return "";
    }
