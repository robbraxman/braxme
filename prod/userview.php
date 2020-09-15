<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require("roommanage.inc.php");
$providerid = @tvalidator("PURIFY",$_SESSION['pid']);
$userid = @tvalidator("PURIFY",$_POST['userid']);
$caller = @tvalidator("PURIFY",$_POST['caller']);

$source = "";
if($caller == ''){
    $caller = 'leave';
}
    $result = pdo_query("1"," 
            select providername, avatarurl, replyemail, handle, publishprofile, publish,
            blocked1.blockee, blocked2.blocker
            from provider 
            left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = $providerid
            left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = $providerid
            where providerid = $userid 
                ");
    $providername = "Unknown";
    if($row = pdo_fetch($result)){
        $providername = $row['providername'];
        $avatarurl = $row['avatarurl'];
        if($row['handle']!=''){
            $row['replyemail']='';
        }

        }
    if(rtrim($providerid) == rtrim($userid) ){
        //$row['handle']='';
        $row['replyemail']='';
        //echo "Not available";
        //exit();
        
    }
    $mytools = "";
    $myrooms = "";
    $myprivaterooms = "";
    
    if(intval($userid)>0){
        $publishprofile = $row['publishprofile'];
        $publishprofile = nl2br($publishprofile);
        if($publishprofile!=''){
            $publishprofile = "<div style='max-width:800px;color:white'><br>$publishprofile</div>";
        }

        $mytools = ShowMyTools($userid, $providerid);
        $myrooms = ShowMyRooms($userid, $providerid, $caller );
        $myprivaterooms = ShowMyPrivateRooms($userid, $providerid, $caller );
        $buttons = UserButtons( $providerid, $userid, $row['handle'], $row['providername'], $row['source'], $row['replyemail'], $row['blockee'] );
    }
    $privacymessage = "";
    
    if($row['publish']!='Y'){
        if($providerid != $userid){
            $myrooms = "";
            $myprivaterooms = "";
        } else {
            $publishprofile = "";
            $privacymessage = "
                <div class='smalltext' style='text-align:left'>
                Your Profile is not visible to the public
                </div>
                    ";
        }
    }
    $backto =  "<img class='leave icon20' src='../img/Arrow-Left-in-Circle-White_120px.png'
             style='cursor:pointer;'
             /> Back 
        <br><br>
        ";
    if( $caller=='find'){
        $backto =  "<img class='meetuplist icon20' src='../img/Arrow-Left-in-Circle-White_120px.png'
                 style='cursor:pointer;'
                 /> Back 
            <br><br>
            ";
        $mytools = "";
        $myprivaterooms = "";
        
    
        
    }

    if($caller == 'none'){
        $backto = "";
        
    }        
    if($providerid == $userid){
        $backto .= "<div class='uploadavatar pagetitle3' style='color:purple;cursor:pointer' >Edit Profile</div>";
    }

if($providerid == $userid){    
?>
    
    <div class='gridstdborder' 
        style='background-color:<?=$global_titlebar_color?>;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
        <img class='icon20 feed' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
             style='' data-providerid='<?=$userid?>' data-caller='<?=$caller?>' data-roomid='<?=$_SESSION['profileroomid']?>' />
        &nbsp;
        <?=$icon_braxroom2?>
        <span class='pagetitle2a' style='color:white'>Manage My Profile</span> 
    </div>
<?php
}
?>

        <div class='mainfont gridstdborder' style='background-color:<?=$global_menu_color?>;color:white;float:left;margin:auto;vertical-align:top;width:100%;padding-left:30px;padding-right:30px;padding-top:10px;margin:0'>
            <?=$mytools?>
            <?=$backto?>

            <span class='nonmobile'>
                <img class='' src='<?=$avatarurl?>' style='float:left;cursor:pointer;max-width:150px;padding-right:20px'
                        data-providerid='<?=$userid?>' data-name='<?=$providername?>'    
                        data-mode ='S' data-passkey64='' data-handle="<?=$row['handle']?>"
                     />

                <div class='nonmobile' style=''>
                    <span class='pagetitle' style='color:white'>
                        <?=$providername?>
                    </span>
                    <?=$buttons?>
                </div>
            </span>
            <span class='formobile'>
                <img class='' src='<?=$avatarurl?>' style='cursor:pointer;max-width:150px;padding-right:20px'
                        data-providerid='<?=$userid?>' data-name='<?=$providername?>'    
                        data-mode ='S' data-passkey64='' data-handle="<?=$row['handle']?>"
                     />

            </span>
            <div class='formobile pagetitle' style='color:white'><b><?=$providername?></b></div>
            <br>
            <div style='color:white'><b><?=$row['handle']?></b></div>
            <?=$publishprofile?>
            <?=$privacymessage?>
            <div class='formobile' style='width:100%;color:white'>
                <br><br>
                <hr>
                <?=$buttons?>
                <br>
                <hr>
                <br>
            </div>
            <br>
                
        </div>
        <div class='mainfont' style='color:white;margin:auto;vertical-align:top;width:80%;padding-left:30px;padding-right:30px;margin:0'>
                <?=$myrooms?>
                <?=$myprivaterooms?>
                <div class='circular3' style="width:100%;opacity:0"></div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
            </div>
        </div>

<?php
function ShowMyRooms($providerid, $watcherid, $caller)
{
    global $global_menu_color;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    
    $roomlinks = "";
    $roomid = "";
    $watcheraction = "feed";
    $result = pdo_query("1","
        select roomid from roominfo where profileflag ='Y' and profileflag is not null 
        and roomid in (select roomid from statusroom 
        where providerid = $providerid and providerid = owner) ");
    if($row = pdo_fetch($result)){
        $roomid = $row['roomid'];
        if($providerid != $watcherid){
            NewProfileRoomMember($roomid, $providerid, $watcherid);
        }
        
    }  else {
        if($providerid == $watcherid ){
            
            $roomid = NewProfileRoom($providerid);
        } else {
            $roomid = "";
            $watcheraction = "";
        }
    }
    
    if($roomid!=''){
    $photourl = "
            <div class='circular2' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                <img src='https://brax.me/img/aboutme2.png' style='height:100%;width:auto;max-width:100%' />
            </div>
            ";
        $child = "<div class='$watcheraction' data-roomid='$roomid'  data-caller='$caller' style='color:black;cursor:pointer;float:left;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                    About Me
                    $photourl
                  </div>";
    $roomlinks .= $child;
    }
    
    $result = pdo_query("1","
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
            and roomhandle.handle not like '#live%'
            and (roominfo.private = 'N')
            and roomhandle.handle not in ('#sayhi','#braxme','#braxtips','#QA')
            and roominfo.groupid is null
	    )
        and statusroom.roomid > 1
        order by roominfo.profileflag desc, roominfo.lastactive desc, roominfo.room asc
    ");
    while($row = pdo_fetch($result)){
        $room = $row['room'];
        $roomhandle = $row['handle'];
        $roomid = $row['roomid'];
        if($row['photourl']==''){
            $row['photourl']='https://brax.me/img/brax-photo-michigan-128.png';
        }
        
        $photourl = "
                <div class='circular2' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                </div>
                ";
        
        $child = "<div class='roomjoin' data-mode='J' data-handle='$roomhandle' data-roomid='$roomid' data-caller='$caller' style='color:purple;cursor:pointer;float:left;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                    <u>$room</u>
                    $photourl
                  </div>";
        $roomlinks .= $child;

    }
    
    if($roomlinks!=''){
        $roomlinksfinal = "<br><div class='pagetitle2' style='width:100%'><b>My Rooms</b></div>";
        $roomlinksfinal .= "
                        <br><br>".$roomlinks.$managerooms;
        $roomlinks =$roomlinksfinal;
    }
    return $roomlinks;    
    
}
function ShowMyPrivateRooms($providerid, $watcherid, $caller)
{
    
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
    while($row = pdo_fetch($result)){
        $room = $row['room'];
        $roomhandle = $row['handle'];
        $roomid = $row['roomid'];
        if($row['photourl']==''){
            $row['photourl']='https://brax.me/img/brax-photo-michigan-128.png';
        }
        if($row['profileflag']=='Y'){
            $row['photourl']='https://brax.me/img/aboutme.png';
        }
        $photourl = "
                <div class='circular2' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                </div>
                ";
            $child = "<div class='feed' data-roomid='$roomid' data-caller='$caller' style='color:purple;cursor:pointer;float:left;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                        <u>$room</u>
                        $photourl
                      </div>";
        $roomlinks .= $child;

    }
    if($roomlinks!=''){
        $roomlinksfinal = "<br><br><br><br><br><div class='pagetitle2' style='float:left;padding-top:20px;padding-bottom:20px;width:100%'><b>My Private Rooms</b></div>".$roomlinks;
        $roomlinks =$roomlinksfinal;
    }
    return $roomlinks;    
    
}
function UserButtons($providerid, $userid, $handle, $providername, $source, $replyemail, $blockee )
{
    $buttons = "";
    if( $providerid != $userid ){
        $buttons .= "
            <div class='chatinvite'
                    data-providerid='$userid' data-name='$providername'    
                     data-handle='$handle'
                    data-mode ='S' data-passkey64='' style='cursor:pointer'>
                <img class='icon30' src='../img/chat-line-white-128.png'
                     style='top:8px;position:relative'
                     /> Start Chat
            </div>
            ";

        if( $blockee=='' ){

            $buttons .= "
            <div class='blockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 data-source='$source' style='cursor:pointer;bottom:margin:10px'>
                <img class='icon30' src='../img/block-line-white-128.png'
                     style='top:8px;position:relative'
                     /> Block 
            </div>
            ";
        } else {
            $buttons .= "
            <div class='unblockbutton'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'
                 data-source='$source' style='cursor:pointer'>
            <img class='icon30' src='../img/check-round-white-128.png'
                 style='cursor:pointer;;top:8px;position:relative;bottom:margin:10px'
                 data-name='$providername' data-email='$replyemail' data-handle='$handle'             
                 data-source='$source'
                 /> Unblock 
            </div>
            ";
        } 
    } else {
        $buttons .= "
            <div class='' style='opacity:.3' >
                <img class='icon30' src='../img/chat-line-white-128.png'
                     style='top:8px;position:relative;'
                     /> Private Chat
            </div>
            <div class='' style='opacity:.3;bottom:margin:10px' >
                <img class='icon30' src='../img/block-line-white-128.png'
                     style='top:8px;position:relative;'
                     /> Block 
            </div>
            ";
        
    }
    return $buttons;
    
}
function ShowMyTools($providerid, $watcherid)
{
    global $global_menu_color;
    if($providerid != $watcherid){
        return "";
    }
    if($_SESSION['superadmin']!='Y' ){
        //return "";
    }
    $mytools = "
        <div style='text-align:text;white-space:nowrap;min-width:300px'>
            <!--
            <div class='pagetitle2' style='padding-top:20px;padding-bottom:20px;width:100%'><b>My Data</b></div>
            -->
            
            <div class='divbuttontext divbuttontext_unsel photolibrary mainbutton' style='background-color:$global_menu_color;color:white;'><img class='icon20' src='../img/brax-photo-round-white-128.png' style=';position:relative;top:8px;' /> My Photos</div>
            <div class='divbuttontext divbuttontext_unsel doclib mainbutton' style='background-color:$global_menu_color;color:white;'><img class='icon20' src='../img/brax-doc-round-white-128.png'  style='position:relative;top:8px'/> My Files</div>
            <div class='divbuttontext divbuttontext_unsel friends mainbutton' style='background-color:$global_menu_color;color:white;'><img class='icon20' src='../img/brax-room-round-white-128.png'  style='position:relative;top:8px'/> My Rooms</div>
            <br><br><br>
        </div>
            ";
    
    return $mytools;

}