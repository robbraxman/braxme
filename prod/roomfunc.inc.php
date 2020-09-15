<?php
function DesktopInput( $readonly, $roomid, $anonymous_settings, $anonymous_opacity )
{
    global $global_activetextcolor;
    global $textcolor;
    global $iconsource_braxarrowright_common;
    global $menu_sharefile;
    global $menu_sharephoto;
    global $menu_title;
    global $menu_newtopic;
    global $menu_sharephoto;
    
    if($readonly=='Y'){
        return "";
    }
    
    return "
        <img class='icon15 hidecomment' title='Collapse Comment Box' src='../img/minus-gray-128.png' style=float:right;right:0;' />


        <span class='pagetitle3' style='color:$textcolor'><b>$menu_newtopic</b></span><br>
        <input class='commentwidth mainfont' id='statustitle' placeholder='$menu_title' name='title' autocomplete='off' style='background-color:white;' /><br>
        <br>
        <!--
        <textarea class='commentwidth mainfont feedenter' id='statuscomment' placeholder='$menu_newtopic' name='comment'  autocomplete='off' style='padding:5px' rows='5'></textarea>
        -->
        <textarea class='commentwidth feedenter' id='statuscomment' placeholder='$menu_newtopic' name='comment'  style='padding:5px' rows='5'></textarea>
        <span class='statusphoto' style='color:$textcolor;display:none'>
            <br>
            <b>$menu_sharephoto</b><br>
            <input class='commentwidth mainfont' id='statusphoto' type='url' title='Photo Link' value=''>
            <br><br>
        </span>

        <span class='statusfile' style='color:$textcolor;display:none'>
            <br>
            <b>$menu_sharefile</b><br>
            <input class='commentwidth mainfont' id='statusfile' type='url' title='File Link'  value=''>
            <br><br>
        </span>

        <img class='icon25 feed'
              title='Post Message'
              src='$iconsource_braxarrowright_common'
              style='top:0px'
              id='post' data-mode='P' data-mobile=''  data-shareid='' data-roomid='$roomid'  data-selectedroomid='$roomid' 
        />
        <div id='roompostenter' class='icon25 feed'
              style='display:none'
              data-mode='P' data-mobile=''  data-shareid='' data-roomid='$roomid'  data-selectedroomid='$roomid' 
        />
        <div id='roompostreplyenter' class='feedreply'  
            style='display:none'
            data-mode='R' data-mobile='' data-shareid=''
            data-roomid='$roomid' data-selectedroomid='$roomid' data-reference=''
        />

        <br><br>
              <div class='smalltext' style='display:inline-block;height:50px;width:60px;text-align:center;color:gray'>
                  <div class='openstatusphoto photoselect' 
                       id='photoselect_icon' data-target='#statusphoto' data-album='' 
                       data-src='#statusphoto' data-filename='' data-mode='X' data-caller='feed'  style='cursor:pointer;color:$global_activetextcolor'>

                        <!--<img class='icon25' src='../img/brax-photo-round-gold-128.png' style='top:0px;' />-->
                        $menu_sharephoto
                  </div>
                  <br>
              </div>
              <div class='smalltext' style='cursor:pointer;display:inline-block;height:50px;width:60px;text-align:center;color:gray'>
                  <div class='openstatusfile fileselect' 
                       id='fileselect_icon' data-target='#statusfile' data-album='' 
                       data-src='#statusfile' data-filename='' data-link=''  data-caller='room' style='cursor:pointer;color:$global_activetextcolor' >

                        <!--<img class='icon25' src='../img/brax-doc-round-gold-128.png' style='top:0px;' />-->
                        $menu_sharefile
                  </div>
                  <br>
              </div>

              <div class='smalltext' style='$anonymous_opacity;cursor:pointer;display:inline-block;height:50px;width:250px;padding-left:10px;text-align:left;'>
                  <input id='statusanonymous' type='checkbox' title='Anonymous' value='Y' $anonymous_settings style='padding:0;margin:0;position:relative;top:5px'> Anonymous
                      <!--
                  &nbsp;&nbsp;
                  <input id='statusalias' type='checkbox' title='Alias' value='A' $anonymous_settings style='padding:0;margin:0;position:relative;top:5px'> Alias
                  &nbsp;&nbsp;
                  -->
                  <br><br>
              </div>

        <br><br>    
    ";
    
}

function DesktopInputReply( $readonly, $selectedroomid, $roomid, $shareid, $postid, $anonymous_settings, $anonymous_opacity,
        $locked, $adminonly, $owned )
{
    global $global_activetextcolor;
    global $textcolor;
    global $backgroundcolor;
    global $iconsource_braxarrowright_common;
    global $menu_sharefile;
    global $menu_sharephoto;
    global $menu_reply;
    
    if($readonly=='Y'){
        return "";
    }
    if($locked > 0 ){
        return "";
    }
    if($adminonly == 'Y' && !$owned){
        return "";
    }
    
    return "
        <span class='noaction makecomment'  data-shareid='$shareid' data-roomid='$roomid' data-reference='$postid'  >
            <input class='mainfont noaction' placeholder='$menu_reply' readonly=readonly name='title' data-shareid='$shareid' data-roomid='$roomid'  
                style='width:100%;min-width:50%;background-color:white;' /><br>
            <br>
        </span>

        <div class=makeaction>
            <img class='hidecomment icon20' src='../img/minus-gray-128.png' style='top:0px;float:right;top;0;right:0;' />

            <br>
            <textarea class='commentwidth replycomment mainfont feedreplyenter' 
                id='replycomment'  
                placeholder='$menu_reply' name='replycomment' style='border-size:1px;padding:5px;' rows=3
                data-shareid='$shareid'
                data-reference='$postid'
            ></textarea>
            <span class='replyphotospan' style='color:$global_activetextcolor;display:none'>
                <br>
                <b>$menu_sharephoto</b><br>
                <input class='replyphoto commentwidth mainfont'  id='replyphoto'  type='url' size=70 title='Photo Link' >
                <br><br>
            </span>

            <span class='replyfilespan' style='color:$global_activetextcolor;display:none'>
                <br>
                <b>$menu_sharefile</b><br>
                <input class='replyfile commentwidth mainfont'  id='replyfile'  type='url' size=70 title='File Link' >
                <br><br>
            </span>
            <img class='icon20 feedreply'  src='../img/Arrow-Right-in-Circle_120px.png'
                    style=''
                    data-mode='R' data-mobile='' data-shareid='$shareid'
                    data-roomid='$roomid' data-selectedroomid='$selectedroomid' data-reference='$postid'
                        />
            
            <br><br>
            <div class='smalltext' style='display:inline-block;height:50px;width:60px;margin-top:5px;text-align:center;color:gray'>
                <div class='openreplyphoto photoselect tapped' 
                    style='cursor:pointer;color:$global_activetextcolor'
                    id='photoselect' 
                    data-target='.replyphoto' data-src='.replyphoto' data-filename='' data-mode='X' data-caller='feed' title='My Photo Library' >
                    $menu_sharephoto

                    <!--<img class='icon25'  src='../img/brax-photo-round-gold-128.png' style='top:0px;' />-->
                </div>
                <br>
            </div>
            <div class='smalltext' style='cursor:pointer;display:inline-block;height:50px;width:60px;margin-top:5px;text-align:center;color:gray'>
                <div class='openreplyfile fileselect tapped' 
                    style='cursor:pointer;color:$global_activetextcolor'
                    id='fileselect' 
                    data-target='.replyfile' data-src='.replyfile' data-filename='' data-link='' data-caller='room'  title='My File Library' >
                    $menu_sharefile

                     <!-- <img class='icon25' src='../img/brax-doc-round-gold-128.png' style='top:0px;' />-->
                </div>
                    <br>
            </div>
            <div class='smalltext' style='$anonymous_opacity;cursor:pointer;display:inline-block;height:50px;width:250px;padding-left:10px;text-align:left;'>
                <input class='replyanonymous' type='checkbox' title='Anonymous' value='Y' $anonymous_settings style='padding:0;margin:0;position:relative;top:5px'> Anonymous
                    <!--
                   &nbsp;&nbsp;
                <input class='replyalias' type='checkbox' title='Alias' value='A' $anonymous_settings style='padding:0;margin:0;position:relative;top:5px'> Alias
                   &nbsp;&nbsp;
                   -->
                <br><br>
            </div>

            <br>

            <br><br>
    ";
    
}

function MobileInput( $readonly, $roomid, $page, $room, $mainwidth, $anonymous_settings )
{
    global $textcolor;
    global $global_activetextcolor;
    global $backgroundcolor;
    global $iconsource_braxarrowleft_common;
    global $iconsource_braxarrowright_common;
    global $menu_sharefile;
    global $menu_sharephoto;
    global $menu_uploadphoto;
    
    if($readonly == 'Y'){
        return "";
    }
    
    return "
            <table id='makeaction'  style='display:none;background-color:transparent;width:$mainwidth;border-collapse:collapse;margin:auto'>
            <tr class='gridnoborder pagetitle3' '>
                    <td class='gridnoborder' style='cursor:pointer;background-color:transparent;color:$textcolor;padding:10px;text-align:left' >
                        <div class='feed tapped' 
                               data-mode=''  data-shareid='' data-roomid='' style='cursor:pointer;color:$textcolor;margin-bottom:10px' >
                               <img class='icon25' src='$iconsource_braxarrowleft_common' style='' />
                               &nbsp;
                                   Back
                        </div>
                        <br>
                        <div id='roomstatusheading' class='pagetitle3;font-weight:bold;color:$textcolor'><b>New Topic</b></div>
                        <input class='commentwidth mainfont' id='roomstatustitle' placeholder='Thread Title' name='title'   x-webkit-speech autocomplete='off' style='background-color:white;margin-bottom:5px' />
                        <textarea class='commentwidth mainfont' id='roomstatuscomment' placeholder='Comment, links, photo, video.' name='comment...'  x-webkit-speech rows=4 style='padding:5px;margin:0'></textarea>
                        <span class='statusphoto' style='color:$textcolor;display:none'>
                        <br><b>$menu_sharephoto</b><br>
                        <input class='commentwidth mainfont' id='roomstatusphoto' type='url' title='Photo Link' value='' >
                        <br><br>
                        </span>
                        <span class='statusfile' style='color:$textcolor;display:none'>
                        <br><b>$menu_sharefile</b><br>
                        <input class='commentwidth mainfont' id='roomstatusfile' type='url' title='File Link'  value=''  >
                        <br><br>
                        </span>
                        





                        <br>
                                <div class='smalltext' style='display:inline-block;height:80px;width:45px;text-align:center;color:$global_activetextcolor'>
                                    <div class='openstatusphoto photoselect tapped' 
                                         id='photoselect_icon' data-target='#roomstatusphoto' data-album='' 
                                         data-src='#roomstatusphoto' data-filename='' data-mode='X' data-caller='feed' >
                                    
                                          <img class='buttonicon' src='../img/brax-photo-round-black-128.png' style='cursor:pointer;position:relative;display:inline;height:30px;width:auto;top:0px;' />
                                    </div>
                                    $menu_sharephoto
                                    <br>
                                </div>
                                <div class='smalltext' style='cursor:pointer;display:inline-block;height:80px;width:45px;text-align:center;color:$global_activetextcolor'>
                                    <div class='openstatusfile fileselect tapped' 
                                         id='fileselect_icon' data-target='#roomstatusfile' data-album='' 
                                         data-src='#roomstatusfile' data-filename='' data-link=''  data-caller='room' >
                                    
                                          <img class='buttonicon' src='../img/brax-doc-round-black-128.png' style='position:relative;display:inline;height:30px;width:auto;top:0px;' />
                                    </div>
                                    $menu_sharefile
                                    <br>
                                </div>
                                <!--
                                 <div class='smalltext' style='display:inline-block;height:80px;width:45px;text-align:center;color:$global_activetextcolor'>
                                    <div class='uploadphoto2 tapped' 
                                         id='photoselect_icon' data-target='#roomstatusphoto' data-album='' 
                                         data-src='#roomstatusphoto' data-filename='' data-mode='X' data-caller='feed' >
                                    
                                          <img class='buttonicon' src='../img/upload-circle-128.png' style='cursor:pointer;position:relative;display:inline;height:30px;width:auto;top:0px;' />
                                    </div>
                                    $menu_uploadphoto
                                    <br>
                                </div>
                                -->
                               <div class='smalltext' style='$anonymous_settings;cursor:pointer;display:inline-block;height:80px;width:140px;padding-left:10px;text-align:left;'>
                                    <input id='roomstatusanonymous' type='checkbox' title='Anonymous' value='Y' $anonymous_settings style='padding:0;margin:0;position:relative;top:5px'> Anonymous
                                    <!--    
                                        <br>
                                    <input id='roomstatusalias' type='checkbox' title='Alias' value='A' $anonymous_settings style='padding:0;margin:0;position:relative;top:5px'> Alias
                                    &nbsp;&nbsp;
                                    -->
                                    <br><br>
                                </div>
                                <div class='smalltext' style='cursor:pointer;display:inline-block;height:80px;width:50px;padding-left:10px;text-align:left;'>
                                    <img src='$iconsource_braxarrowright_common' 
                                        title='Post Message'
                                        class='icon35 feed'
                                        id='roompostcomment' data-mode=''
                                        data-mobile='Y' data-shareid='' data-roomid='$roomid'  data-selectedroomid='$roomid'
                                        style='' 
                                        />
                                    <img class='icon35 feedreply' src='$iconsource_braxarrowright_common' 
                                        title='Post Message'
                                        id='roompostreply' data-mode=''
                                        data-mobile='Y' data-shareid='' data-roomid='$roomid'  data-selectedroomid='$roomid'
                                        style='' 
                                        />
                                    <br>
                                </div>
                        <input id='roomstatusshareid' type='hidden' value=''>
                        <input id='roomstatusreference' type='hidden' value=''>
                        <input id='roomstatuspostid' type='hidden' value=''>
                        <input id='roomstatusmode' type='hidden' value=''>
                        <input id='roomstatuspage' type='hidden' value='$page'>
                        <input id='roomstatusroomid' type='hidden' value='$roomid'>
                        <input id='roomstatusselectedroomid' type='hidden' value='$roomid'>
                        <input id='roomstatusroom' type='hidden' value='$room'>
                        <input id='roomscommentid' type='hidden' value=''>
                        <input id='roomscommentheaderid' type='hidden' value=''>
                        &nbsp;
                    </td>
            </tr>
            </table>
        ";    
}
function TopButtons($roomid, $memberinfo, $showmembers, $readonly, $profileflag )
{
    global $iconsource_braxaddressbook_common;
    global $iconsource_braxfolder_common;
    global $global_textcolor;
    global $menu_whoshere;
    global $menu_roomfiles;
    
    $anonymousflag = $memberinfo->anonymous;    
    
    if($readonly == 'Y' || $profileflag == 'Y'){
        return "";
    }
    
    $disable = "";
    $disablemembers = "";
    $opacityfiles = "opacity:1";
    $opacitymembers = "opacity:1";

    //$disable = "disable";
    //$opacityfiles = 'opacity:0.2';
    
    if($anonymousflag =='Y' && $memberinfo->ownermoderatorflag!='Y'){
        //Disable buttons if anonymous room
        
        //Always Disable Members if All Anonymous
        $disablemembers = "disable1$memberinfo->ownermoderatorflag";
        $opacitymembers = 'opacity:0.2';
    }
    if($showmembers !='Y'){
        //Disable buttons if anonymous room
        $disablemembers = "disable2$showmembers";
        $opacitymembers = 'opacity:0.2';
    }
    
    $accessbuttons1 =
            "
            <div class='smalltext2 friendlist$disablemembers tapped roombutton' id='friendlist' data-caller='room' data-mode=''  title='Room Members'
                data-roomid='$roomid' style='vertical-align:top;color:$global_textcolor;$opacitymembers;margin-left:10px'>
                <img class='icon25' src='$iconsource_braxaddressbook_common' 
                    style='margin-bottom:7px;' />
                <br>$menu_whoshere
                <br>   
            </div>
            <div class='smalltext2 roomfiles$disable tapped roombutton' data-caller='room' data-mode=''  title='Access Shared Files'
                data-roomid='$roomid' style='vertical-align:top;color:$global_textcolor;$opacityfiles'>
                <img class='icon25' src='$iconsource_braxfolder_common' 
                    style='margin-bottom:7px' />
                    $menu_roomfiles
                        <br>
            </div>
            <!--
            <div class='smalltext2 roomevents$disable tapped roombutton' data-caller='room'  data-roomid='$roomid' title='Room Calendar Events'
                style='vertical-align:top;color:$global_textcolor;$opacityfiles'>
                <img class='icon25' src='../img/Calendar-4_120px.png' 
                    style='margin-bottom:7px' />
                <br>Events
                <br><br>
            </div>
            -->
            ";

    return $accessbuttons1;
}

function ShareOptions($readonly, $profileflag )
{
    
    global $iconsource_braxinvite_common;
    global $global_textcolor;
    global $menu_roominvite;
    
        if($readonly == 'Y' || $profileflag == 'Y'){
            return "";
        }


        $shareoptions =  " 
                <div class='smalltext2 roomshareoptions tapped roombutton'  title='Share Room and Invite'
                    style='vertical-align:top;color:$global_textcolor;'>
                    <img  class='icon25' src='$iconsource_braxinvite_common' 
                        style='margin-bottom:7px' />
                    <br>$menu_roominvite
                    <br><br>
                </div>
                ";

    return $shareoptions;
}

function EnterpriseButtons($providerid, $memberinfo, $roomid, $readonly, $profileflag )
{
    global $iconsource_braxlock_common;
    global $iconsource_braxcredentials_common;
    global $global_textcolor;
    
    
    if($readonly == 'Y' || $profileflag == 'Y'){
        return "";
    }
    if($_SESSION['industry']=='' || $_SESSION['industry']=='none'){
        return "";
    }
    
    $enterprise = "";
        if( $_SESSION['enterprise']=='Y' || $memberinfo->moderator == $providerid ){
            if($memberinfo->owner == $providerid || $memberinfo->moderator == $providerid){
                $enterprise = "
                <span class='nonmobile'>
                <div class='smalltext2 credentialformsetup tapped roombutton' data-caller='room' data-mode='ROOMFORMREQUEST'  data-roomid='$roomid' title='Manage eForms'
                    style='vertical-align:top;color:$global_textcolor;'>
                    <img class='icon25' src='$iconsource_braxcredentials_common' 
                        style='margin-bottom:7px' />
                    <br>Form
                    <br>Request<br>
                </div>
                </span>
                    ";
            }
        }
        return $enterprise;
}
function PrivateHeader($readonly, $providerid, $roomid, $anonymousflag, $private, $groupname, $adminonly, $radiostation, $profileflag, $external )
{
    global $iconsource_braxlock_common;
    
    if($readonly == 'Y' || $profileflag == 'Y'){
        return "";
    }
    
    $privatetext = "";
    
    $adminonlytext = "";
    if($adminonly == 'Y'){
        $adminonlytext = " - Owner Posting Only";
    }
    if($radiostation=='Y' ){
        $privatetext = "<span style='color:gray'>Broadcast Channel</span>";
    } else
    if( $groupname !='' && $private!=='Y'){
        $privatetext = "<span style='color:gray'>$groupname Only</span>";
    } else
    if( intval($roomid)==1 || 
        ($private=='N' && $external=='N' && 
        intval($roomid!=1) && $anonymousflag!='Y')){

        $privatetext = "<span style='color:gray'>Open Membership $adminonlytext</span>";
    } else 
    if( $external == 'Y'  ){

        $privatetext = "<span style='color:gray'>Website Visibility Only</span>";
    } else 
    if( $private==='N' &&
        intval($roomid!=1) && 
        $anonymousflag=='Y'){
        
        $privatetext = "<span style='color:gray'>Anonymous Posts Only</span>";
        
    } else 
    if( $private==='Y' &&
        intval($roomid!=1) && 
        $anonymousflag=='Y'){
        $privatetext = "<span style='color:gray'>Private and Anonymous</span>";
        
    } else {
        /*
            $privatetext = "

                <tr class='gridnoborder'>
                    <td class='gridcell smalltext' style='width:100%;padding:5px;background-color:#21313F;color:white;text-align:center;border-top-left-radius:10px;border-top-right-radius:10px'>
                    Private Membership
                        <img class='icon15 roomedit tapped' src='../img/delete-circle-white-128.png'  
                            id='deletefriends' 
                            data-providerid='$providerid' data-roomid='$roomid' data-mode='D'
                            style='float:right;position:relative;top:0px'
                        />
                    </td>
                </tr>
                ";
         * 
         */
        $privatetext = "<span style='color:gray'><img class='icon15' src='$iconsource_braxlock_common' style='top:3px' /> Private Membership</span>";

        }
    return $privatetext; 
}
function LeaveRoomButton($readonly, $providerid, $roomid, $profileflag, $memberinfo, $sponsor)
{
    global $iconsource_braxclose_common;
    global $textcolor;
    global $global_textcolor;
    global $menu_leave;
    global $menu_room;
    
        $owner = $memberinfo->owner;
        $private = $memberinfo->private;
    
        if($readonly == 'Y' || $profileflag == 'Y' || $private == 'Y' || $sponsor!=''){
            return "";
        }
        return;
        $button = "
        <div class='smalltext2 friends tapped roombutton' id='deletefriends' data-providerid='$providerid'  data-roomid='$roomid' data-mode='D' title='Delete from Room' data-caller='room'
            style='vertical-align:top;color:$global_textcolor;margin-left:15px'>
            <img class='icon30' src='$iconsource_braxclose_common' 
                style='margin-bottom:7px' />
            <br>$menu_leave
            <br>$menu_room<br>
        </div>
            ";
        return $button;
}

function GetChildLinks($readonly, $roominfo, $caller )
{
    global $rootserver;
    global $global_activetextcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $global_background;
    global $global_menu_color;
    
    $parentroomhandle = $roominfo->parentroomhandle;
    $parentroomid = $roominfo->parentroomid;
    $handle = $roominfo->handle;
    
    if($parentroomhandle == $handle){
        $parentroomhandle = "";
        $parentroomid = "";
    }
    
    if($readonly =='Y'){
        return "";
    }
    $count = 0;
    //if($handle == ''){
    //    return "";
    //}
    $childlinks = "";
    $childlinks = "<div class='gridstdborder tabs tabs-style-tzoid' style='background-color:lightgray;'><nav><ul style='text-align:left'>";
    if($parentroomid!=''){
        $child = "<li class='feed smalltext' data-roomid='$parentroomid' data-caller='$caller' style='background-color:transparent;
                text-align:left;min-width:100%;color:white;cursor:pointer;float:left;padding-left:20px;
                padding-right:10px;padding-top:10px;padding-bottom:10px;margin-top:1px;margin-bottom:1px;margin-left:1px'>
                    <img class='icon15' src='../img/Arrow-Left-in-Circle-White_120px.png'' />
                    $parentroomhandle
                </li>";
        $childlinks .= $child;
        $count++;
    }
    
    $result = do_mysqli_query("1","
        select distinct roominfo.room, roominfo.roomid, roomhandle.handle from roominfo 
        left join roomhandle on roominfo.roomid = roomhandle.roomid
        where parentroom='$handle' and '$handle'!=''  and roominfo.external!='Y' 
        and roomhandle.handle!=''
        and roominfo.roomid in (select roomid from statusroom where statusroom.owner =  $roominfo->ownerid)
        
        order by roominfo.childsort desc, roominfo.room asc
    ");
    /*
        and roomid in (select roomid from statusroom where 
        statusroom.owner = statusroom.providerid and statusroom.owner = $roominfo->ownerid and 
        roominfo.roomid = statusroom.roomid )
     * 
     */
    
    while($row = do_mysqli_fetch("1",$result)){
        $room = $row['room'];
        $roomhandle = $row['handle'];
        $roomid = $row['roomid'];
        $id = substr($roomhandle,1);
        if($roomhandle!=''){
            $child = "<li class='feed smalltext' data-roomid='$roomid' data-caller='$caller'
                        style='text-align:left;color:white;background-color:transparent;cursor:pointer;
                        min-width:150px;max-width:250px;float:left;
                        padding-left:20px;padding-top:10px;padding-bottom:10px;padding-right:10px;
                        margin-top:1px;margin-bottom:1px;margin-left:1px;'>$room</li>";
        }
        $childlinks .= $child;
        $count++;

    }
    $childlinks .= "</ul></nav></div>";

    $childrow = "";
    if($childlinks!='' && $count > 0){
        
        $childrow = 
        "
        <tr>
            <td style='background-color:$global_background'>
            $childlinks
            </td>
        </tr>    
        ";
    }
    
    
    return $childrow;
    
}
function AvatarButton($readonly, $profileflag, $avatarurl, $ownerid, $profileroomid, $anonymousflag )
{
    
    if($readonly == 'Y'){
        return "";
    }
    if($anonymousflag == 'Y'){
        return "";
    }
    
    $action = "feed";
    if(intval($profileroomid)==0){
        $action = "userview";
    }
    $avatarbutton = "
            <div class='$action circular' 
                style='float:right;height:30px;width:30px;overflow:hidden;background-color:white;margin-right:20px;margin-top:10px'
                data-providerid='$ownerid' data-roomid='$profileroomid'>
                <img class='' src='$avatarurl' style='cursor:pointer;min-height:100%;max-width:100%' />
             </div>
             ";
    
    if( $profileflag =='Y'){
        $avatarbutton = "";
    }
    return $avatarbutton;
}


function TopBarButtons($readonly, $memberinfo, $providerid, $roomid, $showmembers, 
        $shareroom, $roomOwner_profileflag, $avatarurl, $profileroomid, $sponsor, $adminroom )
{
    if($readonly == 'Y'){
        return "";
    }
    if($adminroom == 'Y'){
        return "<br>";
    }
    
    $avatarbutton = AvatarButton($readonly, $roomOwner_profileflag, $avatarurl, $memberinfo->owner, $profileroomid, $memberinfo->anonymous );
    
    
    $accessbuttons1 =  TopButtons($roomid, $memberinfo, $showmembers, $readonly, $roomOwner_profileflag );
    $enterprise = EnterpriseButtons($providerid, $memberinfo, $roomid, $readonly, $roomOwner_profileflag );
    $shareoptions = ShareOptions($readonly, $roomOwner_profileflag );
    $leaveroombutton =  LeaveRoomButton($readonly, $providerid, $roomid, $roomOwner_profileflag, $memberinfo, $sponsor);
 
    $topbar = "
        <div class='roomcontent' style='vertical-align:top'>
            $avatarbutton
            <span class='nonmobile' style='vertical-align:top'>
            $accessbuttons1
            $enterprise
            $shareoptions
            $leaveroombutton
            </span>
            <span class='formobile' style='padding-left:0px;padding-right:10px'>
            $accessbuttons1
            $enterprise
            $shareoptions
            $leaveroombutton
            </span>
            <div class='shareoptions' style='display:none;text-align:center'>
            <br><br>
            $shareroom
            </div>
        </div>
        ";
    
    
    return $topbar;
}
function BackAction($caller, $readonly, $profileflag )
{
    $backto = "roomselect";
    if($profileflag=='Y'){
        $backto = "tilebutton";
    }
    
    if($readonly == 'Y'){
        $backto = "tilebutton";
    }
    
    if($caller=='home' ){
        $backto = "tilebutton";
    }
    
    
    if($caller=='find' ){
        $backto = "meetuplist";
    }
    
    if( $caller=='leave'){
        $backto = "leave";
    }
    
    if( $caller=='live'){
        $backto = "selectchatlist";
    }
    
    
    if( $caller=='none'){
        $backto = "restart";
        
    }
    //Back to Prior Room - Roomid specified
    if( intval($caller)> 0 ){
        $backto = 'feed';
    }

    return $backto;
}

function TopBar( $readonly, $caller, $owner, $profileflag, $gotohome )
{
    global $global_titlebar_color;
    global $icon_braxroom2;
    global $menu_myprofile;
    global $menu_room;
    global $menu_userprofile;
    global $global_menu_text_color;
    global $global_textcolor;
    global $iconsource_braxarrowleft_common;
    
    if($readonly=='Y'){
        return "";
    }
    
    $topbartitle = $menu_room;
    if($readonly == 'Y'){
        $topbartitle = ucfirst("$_SESSION[sponsorname] Home");
    }
    
    if($profileflag == 'Y'){
        $topbartitle = $menu_userprofile;
        if($owner == $_SESSION['pid'] && $caller == 'none'){
            $topbartitle = $menu_myprofile;
        }
    }
    if($gotohome=='Y'){
        $readonly = "Y";
    }
    $backto = BackAction($caller, $readonly, $profileflag );
    $mode = 'LIVE';
    if($backto == 'roomselect'){
        $mode = '';
    }
    
    $topbar = "
        <span class='roomcontent'>
            <div class='gridnoborder $backto' 
                data-providerid='$owner' data-caller='$caller' data-roomid='$caller' data-mode='$mode'
                style='background-color:transparent;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;cursor:pointer' >
                <img class='icon20' Title='Back to Home' src='$iconsource_braxarrowleft_common' 
                    style='' 
                    />
                &nbsp;
                <span style='opacity:.5'>
                $icon_braxroom2
                </span>
                <span class='pagetitle2a' style='color:$global_textcolor'>$topbartitle</span> 
            </div>
        </span>
        ";
    return $topbar;
}
function RadioLink($radiostation, $roomOwner_chatid, $roomOwner_chatidquiz )
{
    global $global_titlebar_color;
    $radiolink = "";
    if($radiostation=='Y'){
        $radiolink = "<center> 
                        <br>
                        <div class='divbuttontext setchatsession' data-chatid='$roomOwner_chatid' style='background-color:#3d8da5;color:white'>
                            Go to LIVE Streaming Channel 
                            <img class='icon15' src='../img/arrowhead-right-white-01-128.png' />
                        </div>  
                      </center><br>";
    }
    if($radiostation=='Q' && $roomOwner_chatidquiz!='' ){
        $radiolink = "<center> 
                        <br>
                        <div class='divbuttontext setchatsession' data-chatid='$roomOwner_chatidquiz' style='background-color:$global_titlebar_color;color:white'>
                            Go to LIVE QUIZ 
                            <img class='icon15' src='../img/arrowhead-right-white-01-128.png' />
                        </div>  
                      </center><br>";
    }
    return $radiolink;
}

function OwnerButtons( $readonly, $providerid, $roomid, $roominfo, $memberinfo, $find, $shareid )
{
    global $iconsource_braxrefresh_common;
    global $iconsource_braxgear_common;
    global $iconsource_braxchatbubble_common;
    global $iconsource_braxradiotower_common;
    global $iconsource_braxfind_common;
    global $iconsource_braxarrowright_common;
    global $iconsource_braxarrowleft_common;
    global $global_textcolor;
    
    $memberinfoonly = 'display:none';
    $memberinfoonly2 = 'display:none';
    $memberinfoonly3 = 'display:none';
    $forumonly = 'display:none';
    
    $ownerbuttons = "";
    if($providerid == $memberinfo->owner || $_SESSION['superadmin']=='Y' ){
        $memberinfoonly = 'display:inline';
        $memberinfoonly2 = $memberinfoonly;
        $memberinfoonly3 = $memberinfoonly;
        if($memberinfo->anonymous == 'Y'){
            $memberinfoonly2 = 'display:none';
            $memberinfoonly3 = 'display:none';
        }
    } else 
    if($providerid == $memberinfo->moderator ){
        $memberinfoonly = 'display:none';
        $memberinfoonly2 = 'display:inline';
        $memberinfoonly3 = 'display:inline';
        if($memberinfo->anonymous == 'Y'){
            $memberinfoonly2 = 'display:none';
            $memberinfoonly3 = 'display:none';
        }
    }
    if($roominfo->roomstyle == 'forum'){
        $memberinfoonly2 = 'display:none';
        $memberinfoonly3 = 'display:inline';
        $forumonly = 'display:inline';
        
    }
    if($shareid !=''){
        $forumonly = 'display:none';
        
    }
    
    if($readonly!='Y' && $roominfo->profileflag ==''){
        
        if($shareid == ''){
            $icon_refresh = "$iconsource_braxrefresh_common";
        } else {
            $icon_refresh = "$iconsource_braxarrowleft_common";
        }
        
        $ownerbuttons =
        "  
        <tr class='gridnoborder pagetitle3' style='margin:0;border:0;padding:0'>
            <td class='gridnoborder' style='cursor:pointer;background-color:transparent;color:black;padding:0 0 0 0;text-align:left'>
                    <div class='feed tapped' style='$memberinfoonly3'  data-roomid='' title='Refresh Data'>
                        <img class='icon25' src='$icon_refresh' style='top:10px;padding-left:10px' />
                    </div>
                    <span style='$forumonly'>
                    &nbsp;&nbsp;&nbsp;
                    <div class='showhidden tapped' style='display:inline'  data-mode='' data-roomid='$roomid' 
                        data-roomid='$roomid' data-radiostation='$roominfo->radiostation' title='Search Room'>
                        <img class='icon25' title='Search Room' src='$iconsource_braxfind_common' style='top:10px;' />
                    </div>
                    </span>
                    
                    <span style='$memberinfoonly'>
                    &nbsp;&nbsp;&nbsp;
                    </span>
                    <div class='friends tapped' style='$memberinfoonly'  data-mode='E' data-caller='friendlist' data-roomid='$roomid' title='Settings'>
                        <img class='icon25' src='$iconsource_braxgear_common' style='top:10px;$memberinfoonly' />
                    </div>
                    &nbsp;&nbsp;&nbsp;
                    <div class='chatinvite tapped' style='$memberinfoonly2'  data-mode='S' data-caller='friendlist' data-roomid='$roomid' title='Swawn Chat'>
                        <img class='icon25' title='Spawn a Chat' src='$iconsource_braxchatbubble_common' style='top:10px;$memberinfoonly2' />
                    </div>
                    ";
        
        if( (   ($memberinfo->ownermoderatorflag == 'Y' && $roominfo->radiostation=='Q') || 
                $_SESSION['enterprise']=='Y' || $_SESSION['superadmin']=='Yx'
            ) 
                && $roominfo->profileflag!='Y'){
            if($roominfo->radiostation!='Q'){
                $roominfo->radiostation = 'Y';
            }
            $ownerbuttons .=
            "
                    &nbsp;&nbsp;&nbsp;
                    <div class='chatinvite tapped' style='$memberinfoonly2'  data-mode='S' data-caller='friendlist' 
                        data-roomid='$roomid' data-radiostation='$roominfo->radiostation' title='Spawn Channel'>
                        <img class='icon25' title='Spawn a Broadcast Station' src='$iconsource_braxradiotower_common' style='top:10px;$memberinfoonly2' />
                    </div>
            ";
        }
        
            $ownerbuttons .=
            "
                    <br style='$memberinfoonly'><br style='$memberinfoonly'>
                    <div class='showhiddenarea'>
                        <input class='showhiddenarea inputline dataentry mainfont' id='roomsearch' name='roomsearch' type='text' size=20 value='$find'              
                            placeholder='Search'
                            style='display:none;max-width:200px;background-color:transparent;padding-left:5px;;color:$global_textcolor'/>
                            <img class='showhiddenarea icon25 feed' data-mode='' data-roomid='$roomid' src='$iconsource_braxarrowright_common' title='Start Search'
                            style='display:none;top:8px' >
                        <br><br>
                    </div>

            </td>
        </tr>
            ";
    }
    return $ownerbuttons;
    
    
}

function RoomTitle($readonly, $roominfo, $privatetext, $radiolink, $storelink )
{ 
    global $global_background;
    global $global_textcolor;
    global $global_web_background;
    
    if($roominfo->photourl2!='' && $readonly == 'Y'){
        return "
         <tr class='gridnoborder' style='margin:0;border:0;'>
            <td class='gridnoborder' style='background-color:transparent;color:$global_textcolor;margin:0;padding:0 10 0 10;text-align:center'>
                <br>
                <br>
                <br>
            </td>
        </tr>
        ";
    }
    if($readonly =='Y'){
        $global_background = $global_web_background;
    }
    if($roominfo->roomstyle == 'forum'){
        $radiolink = '';
        $storelink = '';
    }
    
    
    $roomtitle = 
        "
         <tr class='gridnoborder' style='margin:0;border:0;'>
            <td class='gridnoborder' style='background-color:transparent;color:$global_textcolor;margin:0;padding:0 10 0 10;text-align:center'>
                <div class='formobile'><br></div>
                <div class='roomposttitle' style='color:$global_textcolor;padding-left:20px;padding-right:20px'><b>$roominfo->room</b></div>
                <div class='pagetitle3' style='color:$global_textcolor;padding-left:20px;padding-right:20px'>$roominfo->handle $roominfo->roomdesc</div>
                <div class='pagetitle3' style='color:$global_textcolor;padding-left:20px;padding-right:20px'>$privatetext</div>
                $radiolink $storelink
                <br>
            </td>
        </tr>
        ";
    return $roomtitle;
}
function RoomFilesMessage($roomfiles)
{
    global $global_background;
    global $global_textcolor;
    global $check;
    global $menu_roomfiles;
    
    if($roomfiles == ''){
        return "";
    }
    return "
        <tr style='background-color:$global_background'>
            <td class='mainfont' style='padding:10px;color:$global_textcolor'>
                <b>$check $menu_roomfiles</b> $roomfiles
                <br>
                <br>
            </td>
        </tr>
        ";
    
}
function StoreLink($link, $store, $roomid, $roomowner, $external )
{
    global $global_titlebar_color;
    global $global_store_color;
    if($external == 'Y'){
        return "";
    }
    if($store == 'Y'){
        $storelink = "<center> 
                        <br>
                        <div class='userstore' data-roomid='$roomid' data-owner='$roomowner' style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_store_color;color:white'>
                            <img class='icon30' src='../img/store-128.png'>
                            Visit My Online Store 
                        </div>  
                        <br><br>
                        

                      </center><br>";
        return $storelink;
        
    }
    if($link == ''){
        return "";
    }
    
        $storelink = "<center> 
                        <br>
                        <a href='$link' style='text-decoration:none;' target='_blank'>
                        <div class='divbuttontext' style='background-color:$global_store_color;color:white'>
                            Visit the Online Store 
                        </div>  
                        </a>
                      </center><br>";
    return $storelink;
}

?>
