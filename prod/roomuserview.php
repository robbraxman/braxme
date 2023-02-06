<?php
require_once("internationalization.php");
require_once("lib_autolink.php");
require_once("room_technotes.php");

function MyProfileOutput(
        $providerid,
        $roomowner,
        $mytools,
        $myphotos,
        $myrooms,
        $myprivaterooms,
        $avatarurl,
        $providername,
        $handle,
        $buttons,
        $buttonsmobile,
        $publishprofile,
        $privacymessage,
        $caller,
        $gift,
        $profileroomid,
        $medal
        )
{

    global $global_separator_color;
    global $global_menu_color;
    global $global_profile_color;
    global $global_profiletext_color;
    global $global_titlebar_color;
    global $rootserver;
    global $installfolder;
    global $iconsource_braxarrowright_common;
    global $iconsource_braxgift_common;
    global $appname;
    
    global $menu_gift;
    global $menu_thanks;
    global $iconsource_braxcheck_common;

    $technotes = "";
    if($_SESSION['superadmin']=='Y' || $_SESSION['techsupport']=='Y'){
        $technotes = GetTechNotes($roomowner);
    }
    
    $donate = "&nbsp";
    
    if( $roomowner!=$providerid ){
    
        $donate .= "<br>
                <img class='icon20 showhidden' src='../img/gift-white-128.png' placeholder='Give a gift in tokens' title='Give a gift in tokens'  />
                <span class='showhiddenarea' style='display:none'>
                  &nbsp;&nbsp;
                ";
        if($gift == 'X'){
            
            $donate .= "
                    <form id='donateform' method='POST' action='$rootserver/$installfolder/tokendonate.php?mode=select&account=$roomowner' 
                    style='text-decoration:none;color:white;'>
                    <input id='donatetokens' name='tokens' type=number max=1000 min=1 placeholder='Tokens' style='width:80px;height:30px;' />&nbsp;
                    <input id='donateaccount' name='account' value='$roomowner' type='hidden' />
                    <input id='donateprofileroomid' name='roomid' value='$profileroomid' type='hidden' />
                    <input type='image' class='icon15' src='../img/arrow-circle-right-white-128.png' style='background-color:transparent;padding:0;margin:0;position:relative;top:5px'  />
                    </form>
                ";
    
        }
        
        $donate .= "

                    <form id='donateform0' method='POST' action='$rootserver/$installfolder/gift.php?&account=$roomowner' target='functioniframe'
                    style='background-color:#1b1b1b;text-decoration:none;color:white;'>
                    <img class='icon25 showhidden' src='../img/fistbump-white-128.png' title='Kudos' style='position:relative;top:12px'  />
                    <input id='donatetokens' name='tokens' type=hidden value='0' style='width:100px;height:30px;' />&nbsp;
                    <input id='donateaccount' name='account' value='$roomowner' type='hidden' />
                    <input id='donatemode' name='mode' value='K' type='hidden' />
                    <input id='donateprofileroomid' name='roomid' value='$profileroomid' type='hidden' />
                    <input type='image' class='icon15 gift' src='../img/arrow-circle-right-white-128.png' title='Send Kudos' style='background-color:transparent;padding:0;margin:0;position:relative;top:5px'  />
                    </form>
                    &nbsp;&nbsp;&nbsp;
                    <form id='donateform1' method='POST' action='$rootserver/$installfolder/gift.php?&account=$roomowner' target='functioniframe'
                    style='background-color:#1b1b1b;text-decoration:none;color:white;'>
                    <img class='icon20 showhidden' src='../img/heart-white-128.png' title='Thanks' style='position:relative;top:7px'  />
                    <input id='donatetokens' name='tokens' type=hidden value='0' style='width:100px;height:30px;' />&nbsp;
                    <input id='donateaccount' name='account' value='$roomowner' type='hidden' />
                    <input id='donatemode' name='mode' value='T' type='hidden' />
                    <input id='donateprofileroomid' name='roomid' value='$profileroomid' type='hidden' />
                    <input type='image' class='icon15 thanks' src='../img/arrow-circle-right-white-128.png' title='Send Kudos' style='background-color:transparent;padding:0;margin:0;position:relative;top:5px'  />
                    </form>
                </span><br><br>";
    }
    $medalstatus = "";
    if($medal == 1){
       $medalstatus = "<span class='mainfont' style='color:$global_profiletext_color;;margin:auto;vertical-align:top;;padding-right:30px;padding-top:10px;;margin:0'>
                            <img class='icon15' src='$iconsource_braxcheck_common' /> Trusted $appname Resource<br><br>
                       </span>";
       
        
    }
    $avatar = ShowMyAvatar($avatarurl, $providerid, $roomowner, $caller);
    $followers = ShowMyFollowers( $roomowner);
    $following = ShowFollowing( $roomowner, $providerid);
    $mystore = ShowMyStore($roomowner);
    $giftscore = ShowMyScore($roomowner);
    
    
    $output =  "
    <div class='gridnoborder hearts' style='position:relative;top:20px;margin-bottom:20px;background-color:$global_profile_color;color:$global_profiletext_color;width:100%;display:inline-block'>
        <div class='formobile' style='padding-left:20px'>
                $avatar
                <div pagetitle' style='color:$global_profiletext_color'><b>$providername</b> $followers $giftscore</div>
                <div><b>$handle</b></div>
                <br>
                $medalstatus
                <div style=';padding-top:0px'>
                    $buttonsmobile
                    <div style='max-width:100px'>$following</div>
                </div>
                <br>
                $donate
                $publishprofile
                $privacymessage
                <br>
                $mystore
                $myphotos
                $myrooms
                
        </div>
        <div class='nonmobile mainfont' style='color:$global_profiletext_color;float:left;margin:auto;vertical-align:top;width:80%;padding-left:20px;padding-right:30px;padding-top:10px;margin:0'>

            <span class='pagetitle' style='color:$global_profiletext_color'>
                $providername<br>
            </span>
            $avatar
            <div class='' style='display:inline-block;margin-left:20px;margin-right:20px;color:$global_profiletext_color;vertical-align:top'>
                <div ><b>$handle</b> $followers $following $giftscore</div>    
                <br>
                $buttons
            </div>
            $donate
            $medalstatus
            $publishprofile
            $privacymessage
            <br>
            $mystore
            $myphotos
            $myrooms
        </div>
    </div>
    <div class='' style=';float:left;width:100%;overflow:hidden'>
        $technotes
    </div>
    ";
    return $output;
}
function ShowMyProfile($providerid, $roomowner, $caller, $profileflag)
{
    global $rootserver;
    
    if($profileflag!='Y'){
        return "";
    }
    $result = pdo_query("1"," 
            select providername, avatarurl, replyemail, handle, publishprofile, publish, gift,
            blocked1.blockee, blocked2.blocker, provider.profileroomid, medal
            from provider 
            left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = ?
            left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = ?
            where providerid = ? 
                ",array($providerid,$providerid,$roomowner));
    $providername = "Unknown";
    if($row = pdo_fetch($result)){
        $medal = $row['medal'];
        $providername = $row['providername'];
        $avatarurl = $row['avatarurl'];
        
        if($avatarurl=="$rootserver/img/faceless.png"){
            //$row['avatarurl']="$rootserver/img/newbie.jpg";
        }
        if($avatarurl==""){
           $avatarurl = "$rootserver/img/newbie.jpg"; 
        }
        
        
        $handle =$row['handle'];
        if($row['handle']!=''){
            $row['replyemail']='';
        }
        $gift = $row['gift'];
        $profileroomid = $row['profileroomid'];

    }
    if(rtrim($providerid) == rtrim($roomowner) ){
        //$row['handle']='';
        $row['replyemail']='';
        //echo "Not available";
        //exit();
        
    }
    $mytools = "";
    $myrooms = "";
    $myprivaterooms = "";
    $myphotos = "";
    
    if(intval($roomowner)>0){
        $publishprofile = $row['publishprofile'];
        $publishprofile = nl2br($publishprofile);
        
        $publishprofile = autolink($publishprofile, 50, ' class="chatlink" target="_blank" ', false);
        
        if($publishprofile!=''){
            $publishprofile = "<div style='max-width:800px'><br>$publishprofile</div>";
        } else {
            
        }

        $mytools = ShowMyTools($roomowner, $providerid, $caller);
        $myphotos = ShowMyPhotos($roomowner, $providerid, $caller );
        $myrooms = ShowMyRooms($roomowner, $providerid, $caller );
        //$myprivaterooms = ShowMyPrivateRooms($roomowner, $providerid, $caller );
        $buttons = UserButtons( $providerid, $roomowner, $row['handle'], $row['providername'], $row['replyemail'], $row['blockee'], $caller,"" );
        $buttonsmobile = UserButtons( $providerid, $roomowner, $row['handle'], $row['providername'], $row['replyemail'], $row['blockee'], $caller,"Y" );
    }
    $privacymessage = "";
    
    return MyProfileOutput(
        $providerid,
        $roomowner,
        $mytools,
        $myphotos,
        $myrooms,
        $myprivaterooms,
        $avatarurl,
        $providername,
        $handle,
        $buttons,
        $buttonsmobile,
        $publishprofile,
        $privacymessage,
        $caller,
        $gift,
        $profileroomid,
        $medal
        );
    
    
}
function ShowMyPhotos($providerid, $watcherid, $caller)
{
    global $global_menu_color;
    global $global_activetextcolor_reverse;
    global $global_titlebar_color;
    global $rootserver;
    global $menu_mysharedphotos;
    
    if($_SESSION['superadmin']!='Y'){
    //    return "";
    }
    $result = pdo_query("1","select * from photolibshare where providerid = ?  limit 1",array($providerid));
    if($row = pdo_fetch($result)){
        return "
                            <div class='photolibshare rounded' data-userid='$providerid' "
                . "             style='width:250px;cursor:pointer;padding-left:10px;"
                . "             background-color:$global_titlebar_color;color:white'>
                                <img class='icon30' src='../img/braxphoto-white.png' />
                                $menu_mysharedphotos
                            </div>  
                            <br><br>
                                    ";
        //return "";
    }
    return "";

    
}
function ShowMyRooms($providerid, $watcherid, $caller)
{
    global $global_menu_color;
    global $global_activetextcolor_reverse;
    global $global_profiletext_color;
    global $rootserver;
    global $menu_myrooms;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    
    $roomlinks = "";
    $roomid = "";
    $watcheraction = "feed";
    
    
    $result = pdo_query("1","
        select roomhandle.handle, statusroom.roomid, roominfo.room, 
        roominfo.photourl, roominfo.profileflag, roomhandle.public, roominfo.groupid
        from statusroom 
        left join  roominfo on statusroom.roomid = roominfo.roomid
        left join  roomhandle on statusroom.roomid = roomhandle.roomid 
        where owner = ? and statusroom.providerid = statusroom.owner
        and roominfo.anonymousflag!='Y'
        and rsscategory = '' 
        and roominfo.external = 'N'
        and (
            roomhandle.handle!=''
            and roomhandle.handle not like '#live%'
            and (roominfo.private = 'N')
            and roomhandle.handle not in 
            ('#sayhi','#braxme','#braxtips','#QA','#braxportal','#braxtokens','#braxbasics')
	    )
        and statusroom.roomid > 1
        and (roominfo.showinprofile in ('Y','') or roominfo.showinprofile is null)
        order by roominfo.profileflag desc, roominfo.lastactive desc, roominfo.room asc
    ",array($providerid));
    while($row = pdo_fetch($result)){
        $room = $row['room'];
        $roomhandle = $row['handle'];
        $roomid = $row['roomid'];
        if($row['photourl']==''){
            $row['photourl']="$rootserver/img/gradient1.JPG";
        }
        
        $photourl = "
                <div class='circular2 gridnoborder' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                </div>
                ";
        
        $child = "<div class='roomjoin' data-mode='J' data-handle='$roomhandle' data-roomid='$roomid' data-caller='$caller' style='color:$global_activetextcolor_reverse;cursor:pointer;float:left;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                    $room
                    $photourl
                  </div>";
        $roomlinks .= $child;

    }
    
    if($roomlinks!=''){
        $roomlinksfinal = "<div class='smalltext' style='float:left;width:100%'>";
        $roomlinksfinal .= "<span class='pagetitle2a' style='color:$global_profiletext_color'><b>$menu_myrooms</b></span><br><br>".$roomlinks.
                          "</div>";
        $roomlinks =$roomlinksfinal;
    }
    return $roomlinks;    
    
}
function ShowMyPrivateRooms($providerid, $watcherid, $caller)
{
    global $global_activetextcolor_reverse;
    global $rootserver;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    if($providerid!=$watcherid ){
        return "";
    }
    
    $roomlinks = "";
    $watcheraction = "feed";
    
    $result = pdo_query("1","
        select roomhandle.handle, statusroom.roomid, roominfo.room, 
        roominfo.photourl, roominfo.profileflag from statusroom 
        left join  roominfo on statusroom.roomid = roominfo.roomid
        left join  roomhandle on statusroom.roomid = roomhandle.roomid 
        where owner = ? and statusroom.providerid = statusroom.owner
        and radiostation!='Y' and radiostation!='Q' and rsscategory = '' 
        and (
            roomhandle.handle is null or
            roominfo.private = 'Y' or
            roominfo.anonymousflag='Y'
	    )
        and roominfo.profileflag!='Y'
        and statusroom.roomid > 1
        and roominfo.showinprofile !='N'
        order by roominfo.profileflag desc, roominfo.room asc
    ",array($providerid));
    while($row = pdo_fetch($result)){
        $room = $row['room'];
        $roomhandle = $row['handle'];
        $roomid = $row['roomid'];
        if($row['photourl']==''){
            $row['photourl']="$rootserver/img/gradient1.JPG";
        }
        if($row['profileflag']=='Y'){
            $row['photourl']="$rootserver/img/aboutme.png";
        }
        $photourl = "
                <div class='circular2' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                </div>
                ";
            $child = "<div class='feed' data-roomid='$roomid' data-caller='$caller' style='color:$global_activetextcolor_reverse;cursor:pointer;float:left;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                        <u>$room</u>
                        $photourl
                      </div>";
        $roomlinks .= $child;

    }
    if($roomlinks!=''){
        //$roomlinksfinal = "<br><br><br><br><br><div class='pagetitle2' style='float:left;padding-top:20px;padding-bottom:20px;width:100%'><b>My Private Rooms</b></div>".$roomlinks;
        //$roomlinks =$roomlinksfinal;
    }
    return $roomlinks;    
    
}
function UserButtons($providerid, $userid, $handle, $providername, $replyemail, $blockee, $caller, $mobile )
{
    global $menu_startchat;
    global $menu_resumechat;
    global $menu_block;
    global $menu_unblock;
    global $menu_follow;
    global $menu_friendadd;
    
    $chatobj = IsThereExistingChat($providerid, $userid );
    
    $buttons = "";
    $buttonsmobile = "";
    if( $providerid != $userid ){
        
        if( $chatobj->chatid !=  0){
            $buttons .= "
                <div class='setchatsession'
                        data-chatid = '$chatobj->chatid'
                        data-keyhash = '$chatobj->keyhash'
                        data-mode ='S' data-passkey64='' style='cursor:pointer'>
                    <img class='icon15' src='../img/chat-line-white-128.png'
                         style='top:8px;position:relative'
                         /> $menu_resumechat
                </div>
                ";
            
            $buttonsmobile .= "
                <div class='setchatsession'
                        data-chatid = '$chatobj->chatid'
                        data-keyhash = '$chatobj->keyhash'
                        data-mode ='S' data-passkey64='' style='margin:bottom:0px;cursor:pointer'>
                    <img class='icon25' src='../img/chat-line-white-128.png'
                         style='top:10px;position:relative'
                         /> $menu_resumechat
                </div>
                ";
            
            
        }
        $buttons .= "
            <div class='chatinvite'
                    data-providerid='$userid' data-name='$providername'    
                     data-handle='$handle'
                    data-mode ='S' data-passkey64='' style='cursor:pointer'>
                <img class='icon15' src='../img/chat-line-white-128.png'
                     style='top:8px;position:relative'
                     /> $menu_startchat
            </div>
            ";
        $buttonsmobile .= "
            <div class='chatinvite'
                    data-providerid='$userid' data-name='$providername'    
                     data-handle='$handle'
                    data-mode ='S' data-passkey64='' style='margin:bottom:0px;cursor:pointer'>
                <img class='icon25' src='../img/chat-line-white-128.png'
                     style='top:10px;position:relative'
                     /> $menu_startchat
            </div>
            ";
        
        $buttons .= "
            <div class='managefriends'
                    data-friendid='$userid' 
                     data-handle='$handle'
                    data-caller='$caller'
                    data-mode='A'  style='cursor:pointer'>
                <img class='icon15' src='../img/Add-White_120px.png'
                     style='top:8px;position:relative'
                     /> $menu_friendadd
            </div>
            ";
        $buttonsmobile .= "
            <div class='managefriends'
                    data-friendid='$userid' 
                     data-handle='$handle'
                    data-caller='$caller'
                    data-mode='A'  style='margin:bottom:0px;cursor:pointer'>
                <img class='icon25' src='../img/Add-White_120px.png'
                     style='top:10px;position:relative'
                     /> $menu_friendadd
            </div>
            ";
        
        $buttons .= "
            <div class='managefriends'
                    data-friendid='$userid' 
                     data-handle='$handle'
                    data-caller='$caller'
                    data-mode='AF'  style='cursor:pointer'>
                <img class='icon15' src='../img/Add-White_120px.png'
                     style='top:8px;position:relative'
                     /> $menu_follow
            </div>
            ";
        $buttonsmobile .= "
            <div class='managefriends'
                    data-friendid='$userid' 
                     data-handle='$handle'
                    data-caller='$caller'
                    data-mode='AF'  style='margin:bottom:0px;cursor:pointer'>
                <img class='icon25' src='../img/Add-White_120px.png'
                     style='top:10px;position:relative'
                     /> $menu_follow
            </div>
            ";
        
        
            

        if( $blockee=='' ){

            $buttons .= "
            <div class='blockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:0px'>
                <img class='icon15' src='../img/block-line-white-128.png'
                     style='top:8px;position:relative'
                     /> $menu_block
            </div>
            ";
            $buttonsmobile .= "
            <div class='blockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:0px'>
                <img class='icon25' src='../img/block-line-white-128.png'
                     style='top:10px;position:relative'
                     /> $menu_block
            </div>
            ";
            
        } else {
            $buttons .= "
            <div class='unblockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:10px'>
                <img class='icon15' src='../img/check-round-white-128.png'
                    style='cursor:pointer;;top:8px;position:relative'
                    /> $menu_unblock
            </div>
            ";
            $buttonsmobile .= "
            <div class='unblockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:0px'>
                <img class='icon25' src='../img/check-round-white-128.png'
                     style='cursor:pointer;;top:10px;position:relative'
                    /> $menu_unblock
            </div>
            ";
        } 
        if($_SESSION['superadmin']=='Y' ){
            $buttons .= "<br>
                <div class='showhidden2'
                        style='cursor:pointer;'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Admin Functions
                </div>
                ";
        }
        
        if($_SESSION['superadmin']=='Y' ){
            
            $buttons .= 
                "<div class='showhiddenarea2' style='display:none'>";
            $buttons .= "
                <div class='hidehidden2'
                        style='cursor:pointer;'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Hide Admin Functions
                </div>
                <br>
                ";
            $buttons .= "
                <div class='shadowban'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Shadow Ban
                </div>
                ";
            $buttons .= "
                <div class='postwipe'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Wipe Post
                </div>
                ";
            $buttons .= "
                <div class='profilerestrict'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Profile Restrict
                </div>
                ";
            $buttons .= "
                <div class='postrestrict'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Post Restrict
                </div>
                ";
            $buttons .= "
                <div class='hardrestrict'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Hard Restrict
                </div>
                ";
            $buttons .= "
                <div class='iprestrict'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> IP Restrict
                </div>
                ";
            
            $buttons .= "
                <div class='inactivate'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Inactivate
                </div>
                ";
            $buttons .= "
                <div class='activate'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Activate
                </div>
                ";
            $buttons .= "
                <div class='notifydisablebug'
                        data-userid='$userid' 
                        style='cursor:pointer'>
                    <img class='icon15' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> Disable Notifications
                </div>
                ";
            
            $buttons .= 
                "</div>";

            
        }
        
        
        
    } else {
    }
    if($mobile == 'Y'){
        $buttontext = $buttonsmobile;
    } else {
        $buttontext = $buttons;
    }
    if($providerid == $userid){
        $buttontext = '';
    }
    return $buttontext;
    
}
function ShowMyTools($providerid, $watcherid, $caller)
{
    global $global_menu_color;
    global $menu_myphotos;
    global $menu_myfiles;
    global $menu_myrooms;
    
    if($providerid != $watcherid){
        return "";
    }
    if($caller !='none'){
        return "";
    }
    //if($_SESSION['superadmin']=='Y' ){
        return "";
    //}
    
    return $mytools;

}
function ShowMyAvatar($avatarurl, $providerid, $roomowner, $caller)
{
    global $prodserver;
    global $global_activetextcolor_reverse;
    global $menu_edit;
    
    $blink = "";
    if($avatarurl == "$prodserver/img/faceless.png"){
        $blink = "blinklong";
    }
    $avatar = "";
    if($providerid == $roomowner && $caller == 'none' ){
        
        
        $avatar .= 
           "<div class='circular2 gridnoborder' style='display:inline-block;width:150px;height:150px;;margin-right:20px;margin-bottom:10px;cursor:pointer;' title='Edit profile photo and bio'>";

        $avatar .= "
            <img class='gridnoborder uploadavatar' src='$avatarurl' style='width:100%;'
                 />
              ";
        $avatar .= "</div>";
        $avatar .= "<br>
            <div class='$blink uploadavatar pagetitle3' style='color:$global_activetextcolor_reverse;cursor:pointer' >$menu_edit </div>
                <br>
            ";
        
    } else {
        
        $avatar .= "<div class='circular2' style='display:inline-block;width:150px;height:150px;margin-right:20px;margin-bottom:10px;cursor:pointer;' title='User photo'>";

        $avatar .= "
            <img class='gridnoborder' src='$avatarurl' style='width:100%;'
                 />
              ";
        $avatar .= "</div>";
        
    }
    return $avatar;
}
function IsThereExistingChat($providerid, $recipientid )
{
    $result = pdo_query("1","
        select chatid, keyhash from chatmaster 
        where 
        chatid in
        (select chatid from chatmembers where chatmembers.providerid=?
            and chatmaster.chatid = chatmembers.chatid )
        and chatid in    
        (select chatid from chatmembers where chatmembers.providerid=?
            and chatmaster.chatid = chatmembers.chatid )
        and (select count(*) from chatmembers where chatmaster.chatid = chatmembers.chatid) = 2
            ",array($providerid,$recipientid));
    if($row = pdo_fetch($result)){
        $array['chatid'] = $row['chatid'];
        $array['keyhash'] = $row['keyhash'];
        return (object) $array;
    }
    $array['chatid'] = 0;
    $array['keyhash'] = "";
    return (object) $array;
}
function ShowMyScore( $providerid)
{
    global $iconsource_braxmedal_common;
    global $iconsource_braxgiftround_common;
    
    $score1 = 0;
    $score2 = 0;
    
    /*
    $result = pdo_query("1","
        SELECT score from provider
        where providerid = ? 
    ",array($providerid));
    if($row = pdo_fetch($result)){
        $score1 += intval($row['score']);
    }
     * 
     */
    $result = pdo_query("1","
        SELECT count(*) as score from gifts
        where owner = ? 
    ",array($providerid));
    if($row = pdo_fetch($result)){
        $score2 += intval($row['score']);
    }
    $scoretext = "";
    if($score1 > 0) {

        $scoretext = "
            <span class='smalltext' title='Reputation Score'>
                <img class='icon15' src='$iconsource_braxmedal_common' 
                    style='margin-left:20px'/> $score1
            </span>";
    }
    if($score2 > 0) {
        $scoretext .= "
            <span class='gift smalltext' title='Kudos score'>
                <img class='icon15' src='$iconsource_braxgiftround_common' 
                    style='margin-left:20px'/> $score2
            </span>";
    }
    return $scoretext;

}

function ShowMyFollowers( $providerid)
{
    global $iconsource_braxmedal_common;
    global $iconsource_braxgiftround_common;
    
    $followers = 0;
    $followertext = '';
    
    $result = pdo_query("1","
        SELECT count(*) as count from followers
        where providerid = ? 
    ",array($providerid));
    if($row = pdo_fetch($result)){
        $followers += intval($row['count']);
    }
    if($followers > 0){
        $followertext = "
            <img class='icon15 followers mainbutton' data-userid='$providerid' src='../img/people-white.png' placeholder='Followers' title='Followers' style='margin-left:20px' /> <span class='smalltext'>$followers</span>
            ";
    }
    
    
    return $followertext;

}
function ShowFollowing( $roomowner, $providerid )
{
    global $iconsource_braxmedal_common;
    global $iconsource_braxgiftround_common;
    global $global_textcolor;
    global $global_background;
    
    $following = '';
    
    $result = pdo_query("1","
        SELECT * 
        from followers
        left join provider on followers.followerid =provider.providerid
        where followers.providerid = ?  and followers.followerid=?
        and provider.active = 'Y'
    ",array($roomowner,$providerid));
    if($row = pdo_fetch($result)){
        $following = "<br><div class='smalltext managefriends' data-friendid='$roomowner' data-mode='UF' style='cursor:pointer;border-radius:5px;text-align:center;background-color:white;color:black;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;margin-top:5px'>Following</div>";
    }
    
    
    return $following;

}


function ShowMyStore($owner)
{
    global $global_menu_color;
    global $global_activetextcolor_reverse;
    global $global_store_color;
    global $global_profiletext_color;
    global $rootserver;
    global $menu_myrooms;
    global $customsite;
    global $installfolder;
    
    if($customsite){
        return "";
    }

    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    $store = '';
    $result = pdo_query("1","select store from provider where providerid = ? ",array($owner));
    if($row = pdo_fetch($result)){
        $store = $row['store'];
    }
    if($store != 'Y'){
        return "";
    }
    
    $roomid = '';
    $result = pdo_query("1","select handle from roomhandle where roomid in 
                ( SELECT roomid FROM braxproduction.roominfo where store='Y' and external = 'Y' and roomid in 
                  ( select roomid from statusroom where owner = ? and owner = providerid ) 
                )
            ",array($owner));
    
    $handle = '';
    if($row = pdo_fetch($result)){
        $handle = substr($row['handle'],1);
    }
    
    //if($owner == $_SESSION['pid'] || $_SESSION['superadmin']=='Y'){
        return "
                    <div class='userstore rounded' data-roomid='$roomid' data-owner='$owner' style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_store_color;color:white'>
                        <img class='icon30' src='../img/store-128.png'>
                        Visit My Online Store 
                    </div>  
                    <br><br>
            ";
        
    //}
    
    return "
            <a href='$rootserver/$installfolder/host.php?f=_store&h=$handle&p=$owner&version=$_SESSION[version]' target=_blank >
                <div class='rounded' data-roomid='$roomid' data-owner='$owner' style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_store_color;color:white'>
                    <img class='icon30' src='../img/store-128.png'>
                    Visit My Online Store 
                </div>  
            </a>
                        <br><br>
        ";
    
    
}