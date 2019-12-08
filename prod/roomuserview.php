<?php
require_once("internationalization.php");
require_once("lib_autolink.php");

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
        $publishprofile,
        $privacymessage,
        $caller,
        $gift,
        $profileroomid
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
    
    global $menu_gift;
    global $menu_thanks;

    $technotes = "";
    if($_SESSION['superadmin']=='Y'){
        $technotes = GetTechNotes($roomowner);
    }
    
    
    $donate = "&nbsp";
    if( $roomowner!=$providerid ){
    
    $donate .= "<br>
                <img class='icon30 showhidden' src='../img/gift-white-128.png' placeholder='Give a gift in tokens' title='Give a gift in tokens'  />
                <span class='showhiddenarea' style='display:none'>
                ";
    if($gift == 'Y'){
        $donate .= "
                    <form id='donateform' method='POST' action='$rootserver/$installfolder/tokendonate.php?mode=select&account=$roomowner' 
                    style='text-decoration:none;color:white;'>
                    <input id='donatetokens' name='tokens' type=number max=1000 min=1 placeholder='Tokens' style='width:80px;height:30px;' />&nbsp;
                    <input id='donateaccount' name='account' value='$roomowner' type='hidden' />
                    <input id='donateprofileroomid' name='roomid' value='$profileroomid' type='hidden' />
                    <input type='image' class='icon15' src='../img/arrow-circle-right-white-128.png' style='background-color:transparent;padding:0;margin:0;position:relative;top:5px'  />
                    </form>
                    <div class='formobile'><br></div>
                ";
    }
    $donate .= "
                    
                    <form id='donateform0' method='POST' action='$rootserver/$installfolder/gift.php?&account=$roomowner' target='functioniframe'
                    style='background-color:#1b1b1b;text-decoration:none;color:white;'>
                    <img class='icon25 showhidden' src='../img/fistbump-white-128.png' title='Kudos' style='position:relative;top:7px'  />
                    <span class='nonmobile'>$menu_gift</span>
                    <input id='donatetokens' name='tokens' type=hidden value='0' style='width:100px;height:30px;' />&nbsp;
                    <input id='donateaccount' name='account' value='$roomowner' type='hidden' />
                    <input id='donatemode' name='mode' value='K' type='hidden' />
                    <input id='donateprofileroomid' name='roomid' value='$profileroomid' type='hidden' />
                    <input type='image' class='icon15 gift' src='../img/arrow-circle-right-white-128.png' title='Send Kudos' style='background-color:transparent;padding:0;margin:0;position:relative;top:5px'  />
                    </form>
                    &nbsp;&nbsp;&nbsp;
                    <form id='donateform1' method='POST' action='$rootserver/$installfolder/gift.php?&account=$roomowner' target='functioniframe'
                    style='background-color:#1b1b1b;text-decoration:none;color:white;'>
                    <img class='icon25 showhidden' src='../img/heart-white-128.png' title='Thanks' style='position:relative;top:7px'  />
                    <span class='nonmobile'>$menu_thanks</span>
                    <input id='donatetokens' name='tokens' type=hidden value='0' style='width:100px;height:30px;' />&nbsp;
                    <input id='donateaccount' name='account' value='$roomowner' type='hidden' />
                    <input id='donatemode' name='mode' value='T' type='hidden' />
                    <input id='donateprofileroomid' name='roomid' value='$profileroomid' type='hidden' />
                    <input type='image' class='icon15 thanks' src='../img/arrow-circle-right-white-128.png' title='Send Kudos' style='background-color:transparent;padding:0;margin:0;position:relative;top:5px'  />
                    </form>
                </span><br><br>";
    }
 
    
    
    $avatar = ShowMyAvatar($avatarurl, $providerid, $roomowner, $caller);
    $score = ShowMyScore( $roomowner);
    $followers = ShowMyFollowers( $roomowner);
    $following = ShowFollowing( $roomowner, $providerid);
    $mystore = ShowMyStore($roomowner);
    $output =  "
    <div class='gridnoborder hearts' style='background-color:$global_profile_color;width:100%;float:left'>
        <div class='mainfont' style='color:$global_profiletext_color;float:left;margin:auto;vertical-align:top;width:80%;padding-left:20px;padding-right:30px;padding-top:10px;margin:0'>
            $mytools

            <span class='nonmobile'>
                <span class='pagetitle' style='color:$global_profiletext_color'>
                    $providername<br>
                </span>
                $avatar
                <div class='' style='display:inline-block;margin-left:20px;;color:$global_profiletext_color;vertical-align:top'>
                    <div ><b>$handle</b> $followers $score $following </div>
                    <br>
                    $buttons
                </div>
            </span>
            <span class='formobile'>
                $avatar
            </span>
        </div>
        <div class='mainfont' style='color:$global_profiletext_color;margin-left:0;margin-top:20px;vertical-align:top;width:80%;padding-left:30px;padding-right:30px;'>
                <div class='formobile pagetitle' style='color:$global_profiletext_color'><b>$providername</b></div>
                <br>
                <div class='formobile' ><b>$handle</b> $followers $score $following</div>
                <span class='formobile'>
                    $buttons
                </span>
                $donate
                $publishprofile
                $privacymessage
                <br>
                $mystore
                $myphotos
                $myrooms
                $myprivaterooms
        </div>
    </div>
    <div style='float:left;width:100%'>
        $technotes
        <br><br>
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
    $result = do_mysqli_query("1"," 
            select providername, avatarurl, replyemail, handle, publishprofile, publish, gift,
            blocked1.blockee, blocked2.blocker, provider.profileroomid
            from provider 
            left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = $providerid
            left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = $providerid
            where providerid = $roomowner 
                ");
    $providername = "Unknown";
    if($row = do_mysqli_fetch("1",$result)){
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
        $buttons = UserButtons( $providerid, $roomowner, $row['handle'], $row['providername'], $row['replyemail'], $row['blockee'], $caller );
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
        $publishprofile,
        $privacymessage,
        $caller,
        $gift,
        $profileroomid
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
    $result = do_mysqli_query("1","select * from photolibshare where providerid = $providerid  limit 1");
    if($row = do_mysqli_fetch("1",$result)){
        return "
                            <div class='photolibshare' data-userid='$providerid' style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_titlebar_color;color:white'>
                                <img class='icon30' src='../img/braxphoto-white.png'>
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
    
    
    $result = do_mysqli_query("1","
        select roomhandle.handle, statusroom.roomid, roominfo.room, 
        roominfo.photourl, roominfo.profileflag, roomhandle.public, roominfo.groupid
        from statusroom 
        left join  roominfo on statusroom.roomid = roominfo.roomid
        left join  roomhandle on statusroom.roomid = roomhandle.roomid 
        where owner = $providerid and statusroom.providerid = statusroom.owner
        and roominfo.anonymousflag!='Y'
        and radiostation!='Y' and radiostation!='Q' and rsscategory = '' 
        and (
            roomhandle.handle!=''
            and roomhandle.public = 'Y'
            and roomhandle.handle not like '#live%'
            and (roominfo.private = 'N')
            and roomhandle.handle not in 
            ('#sayhi','#braxme','#braxtips','#QA','#braxportal','#braxtokens','#braxbasics')
            and roominfo.groupid is null
	    )
        and statusroom.roomid > 1
        order by roominfo.profileflag desc, roominfo.lastactive desc, roominfo.room asc
    ");
    while($row = do_mysqli_fetch("1",$result)){
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
        $roomlinksfinal .= "<span class='pagetitle2a' style='color:$global_profiletext_color'><b>$menu_myrooms</b></span><br><br>".$roomlinks."</div>";
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
    
    $result = do_mysqli_query("1","
        select roomhandle.handle, statusroom.roomid, roominfo.room, 
        roominfo.photourl, roominfo.profileflag from statusroom 
        left join  roominfo on statusroom.roomid = roominfo.roomid
        left join  roomhandle on statusroom.roomid = roomhandle.roomid 
        where owner = $providerid and statusroom.providerid = statusroom.owner
        and radiostation!='Y' and radiostation!='Q' and rsscategory = '' 
        and (
            roomhandle.handle is null or
            roominfo.private = 'Y' or
            roominfo.anonymousflag='Y'
	    )
        and roominfo.profileflag!='Y'
        and statusroom.roomid > 1
        order by roominfo.profileflag desc, roominfo.room asc
    ");
    while($row = do_mysqli_fetch("1",$result)){
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
function UserButtons($providerid, $userid, $handle, $providername, $replyemail, $blockee, $caller )
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
                    <img class='icon20' src='../img/chat-line-white-128.png'
                         style='top:8px;position:relative'
                         /> $menu_resumechat
                </div>
                ";
            
            $buttonsmobile .= "
                <div class='setchatsession'
                        data-chatid = '$chatobj->chatid'
                        data-keyhash = '$chatobj->keyhash'
                        data-mode ='S' data-passkey64='' style='margin:bottom:0px;cursor:pointer'>
                    <img class='icon35' src='../img/chat-line-white-128.png'
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
                <img class='icon20' src='../img/chat-line-white-128.png'
                     style='top:8px;position:relative'
                     /> $menu_startchat
            </div>
            ";
        $buttonsmobile .= "
            <div class='chatinvite'
                    data-providerid='$userid' data-name='$providername'    
                     data-handle='$handle'
                    data-mode ='S' data-passkey64='' style='margin:bottom:0px;cursor:pointer'>
                <img class='icon35' src='../img/chat-line-white-128.png'
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
                <img class='icon20' src='../img/Add-White_120px.png'
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
                <img class='icon35' src='../img/Add-White_120px.png'
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
                <img class='icon20' src='../img/Add-White_120px.png'
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
                <img class='icon35' src='../img/Add-White_120px.png'
                     style='top:10px;position:relative'
                     /> $menu_follow
            </div>
            ";
        
        
        if($_SESSION['enterprise']=='Y' && $_SESSION['industry']!=='' && $_SESSION['industry']!=='personal'){
            
            $buttons .= "
                <div class='credentialformsetup'
                        data-clientid='$userid' 
                        data-mode ='DISPLAYFORM' data-passkey64='' style='cursor:pointer'>
                    <img class='icon20' src='../img/credentials-white-128.png'
                         style='top:8px;position:relative'
                         /> View Forms
                </div>
                ";
            $buttonsmobile .= "
                <div class='credentialformsetup'
                        data-clientid='$userid' 
                        data-mode ='DISPLAYFORM' data-passkey64='' style='margin:bottom:0px;cursor:pointer'>
                    <img class='icon35' src='../img/credentials-white-128.png'
                         style='top:10px;position:relative'
                         /> View Forms
                </div>
                ";
        }
            

        if( $blockee=='' ){

            $buttons .= "
            <div class='blockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:0px'>
                <img class='icon20' src='../img/block-line-white-128.png'
                     style='top:8px;position:relative'
                     /> $menu_block
            </div>
            ";
            $buttonsmobile .= "
            <div class='blockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:0px'>
                <img class='icon35' src='../img/block-line-white-128.png'
                     style='top:10px;position:relative'
                     /> $menu_block
            </div>
            ";
            
        } else {
            $buttons .= "
            <div class='unblockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:10px'>
            <img class='icon20' src='../img/check-round-white-128.png'
                 style='cursor:pointer;;top:8px;position:relative'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'             
                 
                 /> $menu_unblock
            </div>
            ";
            $buttonsmobile .= "
            <div class='unblockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 style='cursor:pointer;margin-bottom:0px'>
            <img class='icon35' src='../img/check-round-white-128.png'
                 style='cursor:pointer;;top:10px;position:relative'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'             
                 
                 /> $menu_unblock
            </div>
            ";
        } 
    } else {
    }
    $buttontext = "
        <span class='nonmobile'>$buttons</span>
        <span class='formobile'><hr>$buttonsmobile<br><hr></span>
        ";
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
    $mytools = "
        <div style='text-align:text;white-space:nowrap;min-width:300px'>
            
            <div class='divbuttontext2 divbuttontext_unsel photolibrary mainbutton smalltext' style='border:0;background-color:$global_menu_color;color:white;'><img class='icon25' src='../img/brax-photo-round-white-128.png' style=';position:relative;top:8px;' /> $menu_myphotos</div>
            <div class='divbuttontext2 divbuttontext_unsel doclib mainbutton smalltext' style='border:0;background-color:$global_menu_color;color:white;'><img class='icon25' src='../img/brax-doc-round-white-128.png'  style='position:relative;top:8px'/> $menu_myfiles</div>
            <div class='divbuttontext2 divbuttontext_unsel friends mainbutton smalltext' style='border:0;background-color:$global_menu_color;color:white;'><img class='icon25' src='../img/brax-room-round-white-128.png'  style='position:relative;top:8px'/> $menu_myrooms</div>
            <br><br><br>
        </div>
            ";
    
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
        
        
        $avatar .= "<div class='circular2 gridnoborder' style='display:inline-block;width:150px;height:150px;;margin-right:20px;margin-bottom:10px;cursor:pointer;' title='Edit profile photo and bio'>";

        $avatar .= "
            <img class='gridnoborder uploadavatar' src='$avatarurl' style='width:100%;'
                 />
              ";
        $avatar .= "</div>";
        $avatar .= "
            <div class='$blink uploadavatar pagetitle3' style='color:$global_activetextcolor_reverse;cursor:pointer' >$menu_edit </div>
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
    $result = do_mysqli_query("1","
        select chatid, keyhash from chatmaster 
        where 
        chatid in
        (select chatid from chatmembers where chatmembers.providerid=$providerid
            and chatmaster.chatid = chatmembers.chatid )
        and chatid in    
        (select chatid from chatmembers where chatmembers.providerid=$recipientid
            and chatmaster.chatid = chatmembers.chatid )
        and (select count(*) from chatmembers where chatmaster.chatid = chatmembers.chatid) = 2
            ");
    if($row = do_mysqli_fetch("1",$result)){
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
    
    $result = do_mysqli_query("1","
        SELECT score from provider
        where providerid = $providerid 
    ");
    if($row = do_mysqli_fetch("1",$result)){
        $score1 += intval($row['score']);
    }
    $result = do_mysqli_query("1","
        SELECT count(*) as score from gifts
        where owner = $providerid 
    ");
    if($row = do_mysqli_fetch("1",$result)){
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
    
    $result = do_mysqli_query("1","
        SELECT count(*) as count from followers
        where providerid = $providerid 
    ");
    if($row = do_mysqli_fetch("1",$result)){
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
    
    $result = do_mysqli_query("1","
        SELECT * 
        from followers
        left join provider on followers.followerid =provider.providerid
        where followers.providerid = $roomowner  and followers.followerid=$providerid
        and provider.active = 'Y'
    ");
    if($row = do_mysqli_fetch("1",$result)){
        $following = "<br><div class='smalltext managefriends' data-friendid='$roomowner' data-mode='UF' style='cursor:pointer;border-radius:5px;text-align:center;background-color:white;color:black;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;margin-top:5px'>Following</div>";
    }
    
    
    return $following;

}


function GetTechNotes( $otherid  )
{
    global $rootserver;
    global $installfolder;
    if($otherid == 690001027){
        return "";
    }
    //return "";
    $technotes = "<div class='smalltext' style='padding:10px'>";
    $result = do_mysqli_query("1","   
        select useragent, deviceheight, devicewidth, pixelratio, industry, enterprise, notifications, notificationflags,
            providername, name2, alias, replyemail, handle, createdate, devicecode, sponsor, roomdiscovery, colorscheme, language,
            accountstatus, iphash, iphash2, ipsource, joinedvia, timezone, store, web, roomcreator, broadcaster, publish,
            (select count(*) from provider p2 where p2.iphash2 = provider.iphash2 and active='Y') as multi,
            (select count(*) from photolib where photolib.providerid = provider.providerid) as photocount,
            (select count(*) from chatmessage where chatmessage.providerid = provider.providerid) as chatcount,
            (select count(*) from filelib where filelib.providerid = provider.providerid and filelib.status='Y') as filecount
            
            from provider where providerid = $otherid and superadmin is null and techsupport = ''
            ");
            //(select count(*) from chatmessage where chatmessage.providerid = provider.providerid) as chatcount,
            //(select count(*) from statuspost where statuspost.providerid = provider.providerid) as roomcount
    if($row = do_mysqli_fetch("1",$result)){
        $technotes .= "Name $row[providername] - $otherid <br>";
        $technotes .= "Name2 $row[name2]<br>";
        $technotes .= "Publish Profile $row[publish]<br>";
        $technotes .= "Room=$row[roomcreator] Web=$row[web] Store=$row[store]<br>";
        $technotes .= "Alias $row[alias]<br>";
        $technotes .= "Handle $row[handle]<br>";
        $technotes .= "Email $row[replyemail]<br>";
        
        $result2 = do_mysqli_query("1","   
            select username, password, startdate, ip from bytzvpn where providerid = $otherid and status='Y'
            ");
        while($row2 = do_mysqli_fetch("1",$result2)){
            $technotes .= "BytzVPN $row2[username] / $row2[password] ($row2[startdate]) $row2[ip]<br>";
        }
        
        $technotes .= "Color $row[colorscheme]<br>";
        $technotes .= "Language $row[language]<br>";
        $technotes .= "Created $row[createdate]<br>";
        $technotes .= "UserAgent $row[useragent]<br>";
        $technotes .= "JoinedVia $row[joinedvia]<br>";
        $technotes .= "Ip Hash $row[iphash]<br>";
        $technotes .= "Ip Hash2 $row[iphash2]<br>";
        $technotes .= "Ip Source $row[ipsource]<br>";
        $technotes .= "Timezone $row[timezone]<br>";
        $technotes .= "Muti-Accounts $row[multi]<br>";
        $technotes .= "Device Specs  $row[devicewidth]/$row[deviceheight]/$row[pixelratio]<br>";
        $technotes .= "Device Code  $row[devicecode]<br>";
        $technotes .= "Enterprise $row[enterprise] - Industry $row[industry] Sponsorlist $row[sponsor]<br>";
        $technotes .= "Enterprise Account Status $row[accountstatus]<br>";
        $technotes .= "Notifications $row[notifications] Exclusions $row[notificationflags]<br>";
        $technotes .= "Photos/Files $row[photocount]/$row[filecount]<br>";
        $technotes .= "ChatCount/RoomCount $row[chatcount]/<br>";
        //$technotes .= "ChatCount/RoomCount $row[chatcount]/$row[roomcount]<br>";
        $technotes .= "Sponsor $row[sponsor]/ SocialMedia $row[roomdiscovery]<br>";
        $technotes .= "<br><br>";
        
        $result2 = do_mysqli_query("1","   
            select providername, handle, createdate, lastaccess from provider where iphash2 = '$row[iphash2]' and '$row[iphash2]'!='' and active='Y'
            ");
        while($row2 = do_mysqli_fetch("1",$result2)){
            $technotes .= "$row2[providername] $row2[handle] $row2[createdate] $row2[lastaccess]<br>";
        }
        $technotes .= "<br><br>";

        
        $result = do_mysqli_query("1","   
            select platform, arn, token, registered, status, error from notifytokens where providerid = $otherid 
                and status!='E' order by registered desc limit 5
            ");
        while($row = do_mysqli_fetch("1",$result)){
            $gcm = '';
            $apn = '';
            $shorttoken = substr($row['token'],0,10);
            $token = "NotifyToken $row[platform] - $shorttoken...<br>$row[arn]<br>$row[registered] S=$row[status] $row[error]<br>";
            if($row['platform']=='ios'){
                $apn = $row['token'];
            } else {
                $gcm = $row['token'];
            }
            $technotes .= $token;
            $test = "<a href='$rootserver/$installfolder/notifytokentest.php?mode=&apn=$apn&gcm=$gcm&pid=$otherid'>Register ARN Endpoint</a>&nbsp;&nbsp;&nbsp;";
            $test .= "<a href='$rootserver/$installfolder/notifytokentest.php?mode=D&apn=$apn&gcm=$gcm&pid=$otherid'>Delete Token</a><br>";
            $technotes .= $test;
        }
        $technotes .= "<br><br>";
        $result = do_mysqli_query("1","   
            select roomhandle.handle, roominfo.room, roominfo.external, roomhandle.public, roominfo.roominvitehandle,
            roominfo.autochatuser, roominfo.parentroom,
            (select count(*) from statusroom where roominfo.roomid = statusroom.roomid ) as membercount
            from roominfo
            left join roomhandle on roominfo.roomid = roomhandle.roomid
             where roominfo.roomid in (
                select roomid from statusroom where 
                owner=$otherid and statusroom.roomid = roominfo.roomid and statusroom.owner = statusroom.providerid
             ) 
             and profileflag!='Y'
             order by external desc
            ");
        $technotes .= "<div class='smalltext'>Rooms<br>";
        while($row = do_mysqli_fetch("1",$result)){
            $public = '';
            if($row['public']=='Y'){
                $public = 'discoverable';
            }
            $website = '';
            if($row['external']=='Y'){
                $website = "- website  #$row[roominvitehandle]";
                $public = '';
            }
            $log = "<br>[$row[handle]] $row[room] $website $row[autochatuser] $public ($row[membercount]) $row[parentroom]";
            $technotes .= $log;
        }
        
        $result = do_mysqli_query("1","   
            select periscopestreamkey, youtubestreamkey, twitchstreamkey from restream where providerid =  $otherid         
            ");
        if($row = do_mysqli_fetch("1",$result)){
            $technotes .= "<b>Restream</b><br>";
            $technotes .= "Periscope=".$row['periscopestreamkey']." Youtube=".$row['youtubestreamkey']." Twitch=".$row['twitchstreamkey'].
            "<br><br>";
        }
        
        $result = do_mysqli_query("1","   
            select notification.notifydate, notification.notifytype, notification.status, notification.providerid,
            chatmaster.title, chatmaster.encoding, chatmaster.chatid, provider.providername as sender
            from notification 
            left join chatmaster on notification.chatid = chatmaster.chatid
            left join provider on notification.providerid = provider.providerid
            where recipientid =  $otherid order by notifydate desc limit 100        
            ");
        $technotes .= "<div class='smalltext2'>Notifications<br>";
        while($row = do_mysqli_fetch("1",$result)){
            $title = htmlentities( DecryptText( $row['title'], $row['encoding'],$row['chatid'] ),ENT_QUOTES);            
            //$title = base64_decode($row['title']);
            $notificationlog = "<br>$row[notifydate] $row[status] - $row[notifytype] - Sender $row[providerid] [$title] $row[sender]";
            $technotes .= $notificationlog;
        }
        
        
        $technotes .= "</div>";
        $technotes .= "<br><br>";
        
        //$technotes .= "</div></div>";
        
        
        return $technotes;
        
    }
    return "";
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
    $result = do_mysqli_query("1","select store from provider where providerid = $owner ");
    if($row = do_mysqli_fetch("1",$result)){
        $store = $row['store'];
    }
    if($store != 'Y'){
        return "";
    }
    
    $roomid = '';
    $result = do_mysqli_query("1","select handle from roomhandle where roomid in 
                ( SELECT roomid FROM braxproduction.roominfo where store='Y' and external = 'Y' and roomid in 
                  ( select roomid from statusroom where owner = $owner and owner = providerid ) 
                )
            ");
    
    $handle = '';
    if($row = do_mysqli_fetch("1",$result)){
        $handle = substr($row['handle'],1);
    }
    
    //if($owner == $_SESSION['pid'] || $_SESSION['superadmin']=='Y'){
        return "
                    <div class='userstore' data-roomid='$roomid' data-owner='$owner' style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_store_color;color:white'>
                        <img class='icon30' src='../img/store-128.png'>
                        Visit My Online Store 
                    </div>  
                    <br><br>
            ";
        
    //}
    
    return "
            <a href='$rootserver/$installfolder/host.php?f=_store&h=$handle&p=$owner&version=$_SESSION[version]' target=_blank >
                <div class='' data-roomid='$roomid' data-owner='$owner' style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_store_color;color:white'>
                    <img class='icon30' src='../img/store-128.png'>
                    Visit My Online Store 
                </div>  
            </a>
                        <br><br>
        ";
    
    
}