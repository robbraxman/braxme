<?php
session_start();
set_time_limit ( 30 );
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
include("lib_autolink.php");
require_once("room.inc.php");    
require_once("roomuserview.php");
require_once("internationalization.php");
require_once("roomfunc.inc.php");    
 
    $timestart = microtime();
    
    $textcolor = $global_textcolor;
    $backgroundcolor = $global_background;

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("ID",$_SESSION['pid']);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $host = @tvalidator("PURIFY",$_POST['host']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $comment = @mysql_safe_string_unstripped($_POST['comment']);
    $title = @tvalidator("PURIFY",$_POST['title']);
    $link = @tvalidator("PURIFY",$_POST['link']);
    $photo = @tvalidator("PURIFY",$_POST['photo']);
    $video = @tvalidator("PURIFY",$_POST['video']);
    $friendproviderid = @tvalidator("PURIFY",$_POST['friendproviderid']);
    $anonymous = @tvalidator("PURIFY",$_POST['anonymous']);
    $parent = @tvalidator("PURIFY",$_POST['parent']);
    $shareid = @tvalidator("PURIFY",$_POST['shareid']);
    $postid = @tvalidator("PURIFY",$_POST['postid']);
    //$room = @tvalidator("PURIFY",$_POST['room']);
    $selectedroomid = @tvalidator("PURIFY",$_POST['selectedroomid']);
    $page = @tvalidator("PURIFY",$_POST['page']);
    $timezone = @tvalidator("PURIFY",$_POST['timezone']);
    $readonly = @tvalidator("PURIFY",$_POST['readonly']);
    $hostedmode = @tvalidator("PURIFY",$_POST['hostedmode']);
    $find = @tvalidator("PURIFY",$_POST['find']);
    $_SESSION['iscore'] = @tvalidator("PURIFY",$_POST['iscore']);

    $postid_query='';
    if($host == 'Y'){
        $providerid='';
        if($postid !=''){
            $postid_query = " and statuspost.postid < '$postid' ";
        }
    }

    if($selectedroomid == ''){
        $selectedroomid = $roomid;
    }
    
    
    $gotohome = "";
    if($mode == 'HOME'){
        $mode = "";
        $gotohome = "Y";
    }
    
    //$check = "<img src='../img/check-yellow-128.png' style='position:relative;top:3px;height:15px;width:auto;padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $check = $global_icon_check;
    

    if($timezone!=''){
        $_SESSION['timezoneoffset'] =$timezone;       
    }
    /*************************************************
     *  INCLUDE PROCESSING FUNCTIONS FOR DIFFERENT MODES
     */
    /*************************************************
     * 
     * 
     * 
     */
    
    $memberinfo = MemberCheck($providerid, $roomid);
    
    ApplySponsor($providerid, $memberinfo->sponsor);
    
    $sizing = RoomSizing();
    
    /*************************************************
     *  INCLUDE MODE HANDLER
     * 
     *      
     */
    require("room_mode.inc.php");    
    /*************************************************
     * 
     */
    
    $roominfo = RoomInfo($providerid, $roomid, $sizing->mainwidth, $page, $memberinfo);
    //echo $memberinfo->subscribedate;

    if($roominfo->profileflag!='Y'){
        SaveLastFunction($providerid,"R", $roomid);
    } else {
        SaveLastFunction($providerid,"U", $roomid);
        
    }

    
    /**********************************************
     * 
     * D I S P L A Y     S E C T I O N
     * 
     **********************************************/
    
    $anonymous_settings = '';
        $anonymous_opacity = "";
    if( $memberinfo->anonymous == 'N' || $roominfo->profileflag == 'Y') {
        
        $anonymous_settings = "disabled";
        $anonymous_opacity = "opacity:0.5;";
        
    }

    $shareroom = ShareRoom($readonly, "room", $providerid, $roomid,"","", true, $memberinfo->moderator);
    $roomnewpost = DisplayNewPost($readonly, $providerid, $roomid, $roominfo->handle);
    
    $holdingbucket =  MobileInput( $readonly, $roomid, $page, $memberinfo->roomhtml, $sizing->mainwidth, 
                $anonymous_settings );
    $desktopInput = DesktopInput( $readonly, $roomid, $anonymous_settings, $anonymous_opacity );
    
    
    $topbarbuttons = TopBarButtons($readonly, $memberinfo, $providerid, $roomid,  
                $memberinfo->showmembers, $shareroom, 
                $roominfo->profileflag, $roominfo->avatarurl, $roominfo->profileroomid, $roominfo->sponsor, $roominfo->adminroom);
    
    
    $radiolink= RadioLink($memberinfo->radiostation, $roominfo->chatid, $roominfo->chatidquiz);
    $storelink= StoreLink($roominfo->storeurl, $roominfo->store, $roomid, $memberinfo->owner, $roominfo->external );
    $privatetext = PrivateHeader($readonly, $providerid, $roomid, $memberinfo->anonymous, $memberinfo->private, 
                $memberinfo->groupname, $memberinfo->adminonly, $memberinfo->radiostation, $roominfo->profileflag, $roominfo->external );
    
    $roomtitle = RoomTitle($readonly, $roominfo, $privatetext, $radiolink, $storelink );
    
    $ownerbuttons = OwnerButtons( $readonly, $providerid, $roomid, $roominfo, $memberinfo, $find, $shareid );

    $profile = ShowMyProfile($providerid, $memberinfo->owner, $caller, $roominfo->profileflag );
    $topbar =  TopBar( $readonly, $caller, $memberinfo->owner, $roominfo->profileflag, $gotohome );
    $childlinks = GetChildLinks($readonly, $roominfo, $caller);
    $roomfilesmessage = RoomFilesMessage($memberinfo->roomfiles);
    
    if($roominfo->subscriptionpending == 'Y'){
        
        $topbarbuttons = "";
        $roomnewpost = "";
        $childlinks = "";
        $roomfilesmessage = "";
        $ownerbuttons = "";
        $storelink = "";
        $radiolink = "";
        $roominvite = "";
        $holdingbucket = "";
        $desktopinput = "";
        
    }
    /**********************************************
     * 
     * 
     *   START OUTPUT
     * 
     * 
     **********************************************/
    if($roominfo->roomstyle=='forum'){
        include("roomforum.inc.php");    
        exit();
    }
    if(intval($page)<0 || $page == ''){
        $page = 0;
    }
    
    $maxperpage = 20;
    if($roomid == 'All'){
        $maxperpage = 50;
    }
    $limitstart = $page * $maxperpage;
    $limitend = $maxperpage;
    $previouspage = intval($page) - 1;
    $nextpage = intval($page) + 1;
    $previous = " 
                    <img class='feed tapped icon25'
                      data-mode='-' data-roomid='$roomid' data-page='$previouspage'
                    src='$iconsource_braxarrowup_common' style='padding-left:10px' />
                    ";
    if($page == 0){
        $previous = "";
    }
    $next = "
                    <img class='feed tapped icon25'
                    data-mode='+' data-roomid='$roomid' data-page='$nextpage'                    
                    src='$iconsource_braxarrowdown_common' style='padding-left:10px' />
                ";
    
    
    
    echo "$holdingbucket";
    
    echo $topbar;
    
    
    echo $profile;
        
    echo "
        <div style='background-color:transparent;max-width:$_SESSION[innerwidth]px'>";
    
    echo $topbarbuttons;
    
    echo "
        <table class='roomcontent gridnoborder' style='background-color:transparent;width:$sizing->mainwidth;padding:0;margin-left:auto;margin-right:auto;margin-top:0;'>
            ";
    
    echo $roomtitle;
    
    echo $ownerbuttons;

    
    echo $childlinks;
    
    echo $roominfo->tokenpay;
    

    if(  
            $roominfo->subscriptionpending !== 'Y' &&            
            ( $memberinfo->adminonly =='N' || 
                ($memberinfo->adminonly == 'Y' && 
                    ( $memberinfo->owner == $providerid || $memberinfo->moderator == $providerid ))
            )
      ) {
        
        echo
        "
        <tr>
            <td>
                <table class='gridnoborder makecommentowner' style='color:$textcolor;background-color:transparent;width:100%'>
                    <tr class='noaction' data-roomid='$roomid'>
                        <td class='' style='cursor:pointer;;background-color:transparent;padding-left:0px;padding-bottom:13px'> 
                            <br>
                            $roomnewpost
                        </td>
                    </tr>
                    <tr class='makeaction'>
                        <td class='feedpost gridstdborder' style='width:$sizing->mainwidth;background-color:$global_background;padding:10px 20px 10px 20px'> 
                        $desktopInput    
                        </td>
                    </tr>
                </table>
             </td>
        </tr>
        ";
    }


    
    
    
    
    if($previous!=''){
        echo "
                <tr>
                    <td>
                    <center>$previous</center><br>
                    </td>
                </tr>
            ";
    
    }
    
    /*
     *   ROOM FILES NOTIFICATION
     * 
     */
    
    echo $roomfilesmessage;
    
    $limit_to_owner = "";
    
    
    $result = pdo_query("1",
        "
            select statuspost.anonymous, statuspost.encoding, statuspost.postid, 
            statuspost.pin, statuspost.locked,
            providername, provider.name2, statuspost.comment, statuspost.link, 
            provider.active, provider.medal,
            statuspost.photo, statuspost.album, statuspost.video,  statuspost.videotitle, statuspost.roomid,
            avatarurl, alias, statuspost.providerid, statuspost.articleid,
            DATE_FORMAT(date_add(statuspost.postdate,INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), 
                '%m/%d/%y %h:%i%p') as postdate, 
            (select actiontime from statusreads st2 
                where st2.shareid = statuspost.shareid and
                st2.roomid = statuspost.roomid and
                st2.xaccode  not in ('R','D','X') 
             order by actiontime desc limit 1 ) as lastpostdate,

            (select 'Y' from statusreads st2 
                where st2.shareid = statuspost.shareid and
                st2.roomid = statuspost.roomid and
                st2.xaccode ='X' and st2.providerid = $providerid
              ) as flagged,


            statuspost.shareid, statuspost.roomid, statuspost.likes, 
            statuspost.owner,
            (   select distinct providername from provider
                where providerid = statuspost.owner
            ) as ownername,
            (select 'Y' from publicrooms where statuspost.roomid = publicrooms.roomid) as public,
            (select handle from roomhandle where roomhandle.roomid = statuspost.roomid )
                as handle,
            roominfo.anonymousflag, blocked1.blockee, blocked2.blocker,
            provider.profileroomid,
            (select providerid from roommoderator where roommoderator.roomid = statuspost.roomid
             and roommoderator.providerid = $providerid) as moderator,
            statuspost.commentcount, statuspost.title
            from statuspost
            left join provider on statuspost.providerid = provider.providerid
            left join roominfo on statuspost.roomid = roominfo.roomid
            left join blocked blocked1 on blocked1.blockee = statuspost.providerid and blocked1.blocker = $providerid
            left join blocked blocked2 on blocked2.blocker = statuspost.providerid and blocked2.blockee = $providerid

            where statuspost.parent = 'Y' and
            statuspost.roomid  = $roomid
            and '$roominfo->subscriptionpending'!='Y'
            order by  pin desc,  case when lastpostdate is null then postdate else lastpostdate end  desc  limit $limitstart, $limitend 
    ");
    
    
    $postcount = 0;
    while($row = pdo_fetch($result)){
        
        $postcount++;
        $cleanPostid = str_replace(".","",$row['postid']);
        $postdate = InternationalizeDate($row['postdate']);
        
        $comment = FormatComment( "", $row['postid'], $row['owner'], $row['roomid'], 
            $row['encoding'], $row['comment'], $row['title'], $row['photo'], $row['album'],
            $row['video'], $row['link'],"width:$sizing->statuswidth2","Y", 
            $sizing->mainwidth, $sizing->statuswidth2, $row['videotitle'], 
            $row['articleid'], $page, $readonly, $row['blockee'], $row['blocker'] );
        
        
        $posterobj = RoomPosterInfo($row['roomid'], $row['owner'], $row['avatarurl'], $memberinfo->adminroom, $memberinfo->private,
                $row['anonymous'], $row['anonymousflag'], $row['providername'], $row['name2'], 
                $row['alias'], $row['handle'], $row['blockee'], $row['blocker'], $row['medal']  );
        $avatarurl = RootServerReplace(HttpsWrapper($posterobj->avatar));
        $postername = $posterobj->name;
        
        $avatarimg = "<img class='circular avatar1' src='$avatarurl' style='' />";

        
            
        $likebutton = LikeButton("$providerid", $row['likes'], $row['shareid'], 
                $row['postid'], $row['roomid'], $selectedroomid,"","Y",$cleanPostid );
        $deletebutton = DeleteButton("Y","$memberinfo->owner",  "$row[providerid]",$row['moderator'], $providerid, $row['shareid'], 
                $row['postid'], $row['roomid'], $roomid,"top:0px",$cleanPostid );


        
        /**********************************************************
         * 
         * 
         * 
         * 
         * 
         * 
         * 
         * 
         * 
         * 
         */

        if(intval($row['pin'])>0 ){
            echo "
                <tr class='gridnoborder' style='cursor:pointer'>
                    <td class='feed gridnoborder smalltext2' 
                        style='height:15px;width:50px;padding:0;
                        background-color:$global_menu_color;color:white;text-align:center;vertical-align:top;'
                        data-mode='UNPIN' data-postid='$row[postid]'
                        data-roomid='$row[roomid]'  data-shareid='$row[shareid]'
                        style='cursor:pointer'
                        title='Tap to Unpin'
                    >
                        

                    $menu_pinned
                    </td>
                </tr>
                ";
        }
        
        echo DisplayStdRoomPost( 
                
            $readonly, intval($row['locked']),
            $providerid, $row['owner'], $row['roomid'], $row['profileroomid'], $row['shareid'], $row['postid'], 
            $memberinfo, $postdate, $row['handle'],
            $row['active'], $posterobj, $sizing, $avatarurl, $comment,
            $anonymous_settings, $anonymous_opacity,
            $deletebutton, $likebutton, $row['commentcount'] 
                
            );

        
        
    }
    
    
    
    $timeend = microtime();   
    $timeelapsed = $timeend - $timestart;
    echo "</table></div>";
    
    
    
    if($postcount >= $limitend -1) {
        //       $sizing->mainwidth $_SESSION[sizing] $_SESSION[innerwidth] $sizing->padding
        echo "<center><span class=roomcontent>$next</span></center>
             <br>";
    }
    
    /**********************************************************
      * 
      * 
      * 
      * 
      * 
      * 
      * 
      * 
      * 
      * 
      */

    if($roominfo->profileflag =='' && intval($roomid)>1){
        
        echo "<div style='background-color:$global_bottombar_color;width:100%;color:$global_activetextcolor_reverse;text-align:center'>";
        
            echo "
            
                <br>&nbsp;&nbsp;
                <span class='roomcontent' style='color:$global_activetextcolor'>
                <span class='friends' style='cursor:pointer'
                    id='deletefriends' 
                    data-providerid='$providerid' data-roomid='$roomid' data-mode='D' data-caller='room' >
                <img class='icon15 friends tapped' src='../img/delete-circle-white-128.png' />
                $menu_roomunsubscribe
                </span>
                </span><br><br>
                ";
            
            if($memberinfo->mute=='Y'){
                
                echo "
                    <br>&nbsp;&nbsp;
                    <span class='roomcontent' style='color:$global_activetextcolor'>
                    <span class='icon15 mute tapped' style='cursor:pointer'
                        data-roomid='$roomid' >
                        $menu_unmutenotifications
                    </span>
                    </span><br><br>
                    ";
            } else {
                
                echo "
                    <br>&nbsp;&nbsp;
                    <span class='roomcontent' style='color:$global_activetextcolor'>
                        <span class='icon15 mute tapped' style='cursor:pointer'
                        data-roomid='$roomid' >
                        $menu_mutenotifications
                        </span>
                    </span><br><br>
                    ";
                
            }
            if($memberinfo->favorite=='Y'){
                echo "
                    <br>&nbsp;&nbsp;
                    <span class='roomcontent' style='color:$global_activetextcolor'>
                        <span class='icon15 roomfavorite tapped' style='cursor:pointer'
                        data-roomid='$roomid' data-mode='D' >
                        $menu_roomfavoritedelete
                        </span>
                    </span><br><br>
                    ";
            } else {
                echo "
                    <br>&nbsp;&nbsp;
                    <span class='roomcontent' style='color:$global_activetextcolor'>
                        <span class='icon15 roomfavorite tapped' style='cursor:pointer'
                        data-roomid='$roomid' data-mode='A' >
                        $menu_roomfavorite
                        </span>
                    </span><br><br>
                    ";
                
            }
            
        echo "</div>";
    }
    if($roominfo->profileflag =='Y'){
            echo "<br><br><br>";
    }
    echo "</div>";
    
    //echo "<img class='scrolltotop tooltip nonmobile' title='Scroll to Top' src='$rootserver/img/arrowhead-up-gray-128.png' style='height:15px;cursor:pointer;padding-right:10px;padding-top:10px;padding-left:10px;float:left' />";
    
    
function DisplayStdRoomPost( 
        
        $readonly, $locked,
        $providerid, $owner, $roomid, $profileroomid, $shareid, $postid, 
        $memberinfo, $postdate, $handle,
        $active, $posterobj, $sizing, $avatarurl, $comment,
        $anonymous_settings, $anonymous_opacity,
        $deletebutton, $likebutton, $commentcount )
{
    
        global $icon_darkmode;
        global $global_backgroundreverse;
        global $global_activetextcolor;
        global $global_textcolor;
        global $global_background;
        global $rootserver;
        global $menu_replies;
        global $menu_hide;
        global $appname;
        global $iconsource_braxmedal_common;
        
        $braxmedal = "<img class='icon15' src='$iconsource_braxmedal_common' title='Trusted $appname Resource' style='top:0px;bottom:0px;height:15px' />";
        
        
        $cleanPostid = str_replace(".","",$postid);
        $post_pid = "";
        $post_pid_action = '';
    
        if($owner > 0){
            
            $post_pid = $owner;
            $post_pid_action = 'userview';
            
            if(intval($profileroomid)>0){
                $post_pid_action = 'feed';
            }
            if($active=='N'){
                $post_pid_action = '';
            }
            
            if($posterobj->nochat == 'Y' || $post_pid == $providerid ){
                $post_pid = '';
                $post_pid_action = '';
            }
        }
        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "";
        }
        if($readonly == 'Y'){
            $shadow = "gridstdborder";
        } 
        if(intval($_SESSION['innerwidth'])<414){
            $shadow = "";
        }
        $usermedal = "$posterobj->medal";
        if($posterobj->medal=='1'){
            $usermedal = $braxmedal;
        }

        $commentcounttext = '';
        
        if($readonly!='Y'){
            
            $commentbuttons = CommentButtons($providerid, $likebutton, $deletebutton, $memberinfo, $roomid, $postid, $cleanPostid, $shareid, $locked);
            $commentcounttext = "
                                <div class='hideroomcomment roomcommenthideheader' 
                                    data-postid='$postid'  
                                    style='color:$global_activetextcolor;cursor:pointer;display:none'>
                                    $menu_hide $menu_replies
                                </div>
                         ";
            if(intval($commentcount) > 0 ){

                $commentcounttext .=
                        " 
                                <hr style='border:1px solid lightgray'>
                                <div class='showroomcomment roomcommentheader' 
                                    data-shareid='$shareid' data-anchor='$postid'
                                        style='color:$global_activetextcolor;cursor:pointer' data-mode=''>
                                    $commentcount $menu_replies
                                </div>
                        ";
                    $default_subdisplay = "";
                    
            } else {
                
                $commentcounttext .= 
                        "
                                <div class='showroomcomment roomcommentheader' 
                                    data-shareid='$shareid' data-anchor='$postid' data-mode=''
                                        style='color:$global_activetextcolor;cursor:pointer;display:none'>
                                </div>
                        ";
                    $default_subdisplay = "display:none";

            }
            $commentshow =  LastComment($memberinfo->owner, $memberinfo->adminroom, $shareid, $handle, "$providerid", 
                    $roomid, $roomid, $commentcount, $memberinfo->anonymous, 
                    $sizing->mainwidth, $sizing->statuswidth2, $memberinfo->private, $cleanPostid, $readonly );

            $replycomment = DesktopInputReply( $readonly, $roomid, $roomid, $shareid, $postid, 
                            $anonymous_settings, $anonymous_opacity, $locked, 
                            $memberinfo->adminonly, $memberinfo->owner == $providerid  );
        }
            

        /**********
         * 
         * COMMENTS SECTION
         * 
         * *********/




        echo "  
            <tr class='gridnoborder'>
                <td class='commentline mainfont  $shadow' 
                    style='padding:$sizing->padding;width:$sizing->statuswidth;
                    background-color:$global_backgroundreverse;overflow:none;word-wrap:break-word'>          
                    $comment

                    <div style='display:inline-block;padding-left:30px;padding-top:10px;padding-bottom:10px;margin:0;
                        text-align:left;vertical-align:top' >

                        <div class='circular' style='height:30px;width:30px;overflow:hidden;background-color:white'>
                        <img class='$post_pid_action' src='$avatarurl' style='cursor:pointer;min-height:100%;max-width:100%'
                            data-providerid='$post_pid' data-name='$posterobj->name'    
                            data-roomid='$profileroomid' data-caller='$roomid'
                            data-profile='Y'

                            data-mode ='S' data-passkey64=''
                         />
                         </div>

                    </div>
                    <div class='pagetitle3' 
                        style='display:inline-block;vertical-align:top;
                        padding-left:10px;padding-top:10px'>
                            <div class='pagetitle3' style='color:black;'><b>$posterobj->name $usermedal</b></div>
                          <div class=smalltext style='color:black;'>$postdate</div>
                    </div>

                    $commentbuttons


                    <div class='roomcommentsarea' style='display:inline'>
                        <br><br>

                        $commentcounttext

                            <div class='gridnoborder roomcomment' 
                                style='$default_subdisplay;background-color:$global_backgroundreverse;
                                 padding-left:10px;padding-right:10px;padding-top:5px;
                                 width:$sizing->statuswidth2;word-wrap:break-word'>
                                $commentshow
                            </div>
                            $replycomment

                        </div>
                        <br>
                    </div>
                </td>
           </tr>
           <tr>
                <td>                        
                    <br><br><br>
                </td>
           </tr>
        ";
            
}    

function DisplayForumRoomPost( 
        
        $readonly, $locked,
        $providerid, $owner, $roomid, $profileroomid, $shareid, $postid, 
        $memberinfo, $postdate, $handle,
        $active, $posterobj, $sizing, $avatarurl, $comment,
        $anonymous_settings, $anonymous_opacity,
        $deletebutton, $likebutton, $commentcount )
{
    
        global $icon_darkmode;
        global $global_backgroundreverse;
        global $global_activetextcolor;
        global $global_textcolor;
        global $global_background;
        global $rootserver;
        global $menu_replies;
        global $menu_hide;
        global $appname;
        global $iconsource_braxmedal_common;
        
        $braxmedal = "<img class='icon15' src='$iconsource_braxmedal_common' title='Trusted $appname Resource' style='top:0px;bottom:0px;height:15px' />";
        
        $cleanPostid = str_replace(".","",$postid);
        $post_pid = "";
        $post_pid_action = '';
    
        if($owner > 0){
            
            $post_pid = $owner;
            $post_pid_action = 'userview';
            
            if(intval($profileroomid)>0){
                $post_pid_action = 'feed';
            }
            if($active=='N'){
                $post_pid_action = '';
            }
            
            if($posterobj->nochat == 'Y' || $post_pid == $providerid ){
                $post_pid = '';
                $post_pid_action = '';
            }
        }
        
        $usermedal = "";
        if($posterobj->medal=='1'){
            $usermedal = $braxmedal;
        }
        
        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "";
        }
        if($readonly == 'Y'){
            $shadow = "gridstdborder";
        } 
        if(intval($_SESSION['innerwidth'])<414){
            $shadow = "";
        }

        $commentcounttext = '';
        
        if($readonly!='Y'){
            
            $commentcounttext = "";
            if(intval($commentcount) > 0 ){

                $commentcounttext =
                        " 
                            ($commentcount)
                        ";
                    
            } else {
                
                $commentcounttext = 
                        "
                        ";

            }

        }
            

        /**********
         * 
         * COMMENTS SECTION
         * 
         * *********/




        echo "  
            <tr class='gridnoborder'>
                <td class='commentline mainfont  $shadow feed' 
                    data-shareid = '$shareid' data-roomid='$roomid'
                    style='cursor:pointer;padding-bottom:10px;padding-left:20px;padding-right:20px;width:$sizing->statuswidth;
                    background-color:transparent;color:$global_textcolor;overflow:none;word-wrap:break-word'>          
                    <span class='pagetitle2a' style='color:$global_activetextcolor'>$comment</span>

                    $commentcounttext
                    <br>
                    <span class='smalltext2' style='color:$global_textcolor'>$posterobj->name $usermedal $postdate</span>                        

                    </div>
                </td>
           </tr>
        ";
            
}    
function CommentButtons($providerid, $likebutton, $deletebutton, $memberinfo, $roomid, $postid, $cleanPostid, $shareid, $locked)
{
    global $rootserver;
    global $global_background;
    global $global_textcolor;
    
        if($locked > 0 ){
            $lockicon = "$rootserver/img/Key-Lock_120px.png";
        } else {
            $lockicon = "$rootserver/img/Lock-2_120px.png";
        }
    
                
        $commentbuttons = "
                <div id='$cleanPostid'
                    style='padding:5px 30px 0px 20px;color:black;margin:auto'>
                    $likebutton
                    &nbsp;
                    <img class='icon15 showroomcomment stdicon tooltip roomcontrols' src='$rootserver/img/Gey_120px.png'  
                        style='top:0px'
                        data-mode='X'
                        title='View post activity'
                        data-shareid='$shareid' data-anchor='$postid'
                        />
                    &nbsp;
                    <img class='icon15 feed tooltip roomcontrols tapped2' 
                        data-mode='B' data-postid='$postid'
                        data-roomid='$roomid'  data-shareid='$shareid'
                        title='Bump to Top' 
                        src='$rootserver/img/Up-4_120px.png' style='top:0' 
                        />
                    $deletebutton 
                    &nbsp;
                    <img class='icon15 feed tooltip roomcontrols tapped2' 
                        data-mode='FLAG' data-postid='$postid'
                        data-roomid='$roomid'  data-shareid='$shareid'
                        title='Report Objectionable Content' src='$rootserver/img/Flag_120px.png' 
                        style='top:0' 
                        />
                ";         
        if($memberinfo->owner == $providerid || $memberinfo->moderator == $providerid ){
                $commentbuttons .= "                
                        &nbsp;
                        <img class='feed icon15 tooltip roomcontrols tapped2' 
                            data-mode='PIN' data-postid='$postid'
                            data-roomid='$roomid'  data-shareid='$shareid'
                            title='Pin Post' src='$rootserver/img/pin-line-128.png' 
                            style='top:0' 
                            />
                        &nbsp;
                        <img class='feed icon15 tooltip roomcontrols tapped2' 
                            data-mode='LOCK' data-postid='$postid'
                            data-roomid='$roomid'  data-shareid='$shareid'
                            title='Lock Thread' src='$lockicon' 
                            style='top:0' 
                            />
                ";
        }

            
    return $commentbuttons;
}
    
function LastComment( $owner, $adminroom, $shareid, $handle, $providerid, $roomid, $selectedroomid, $commentitems, $anonymousflag, $mainwidth, $statuswidth2, $private, $scrollreference, $readonly )
{
    global $rootserver;
    //global $owner;
    global $appname;
    global $iconsource_braxmedal_common;

        $braxmedal = "<img class='icon15' src='$iconsource_braxmedal_common' title='Trusted $appname Resource' style='top:0px;bottom:0px;height:15px' />";
    
    $commentshow = "";
    if($commentitems > 5 ){
        $commentshow = "<span class='pagetitle3'><b>...</b></span><br><br>";
    }
    if($commentitems == 0 ){
        return "";
    }
        
    $result2 = pdo_query("1",
         
        "
            select anonymous, encoding, postid, providername, comment, link, photo, album, video, videotitle,
            avatarurl, alias, providerid, name2, postdate2, postdate, flagged, active, medal,
            shareid, roomid, likes, owner, public, handle, private, anonymousflag, 
            blockee, blocker, profileroomid, moderator
            
            from 
            (
				select statuspost.anonymous, statuspost.encoding, postid, providername, statuspost.comment, statuspost.link, statuspost.photo, statuspost.album, 
                                statuspost.video, statuspost.videotitle,
				avatarurl, alias, statuspost.providerid, provider.name2, statuspost.postdate as postdate2, provider.active, provider.medal,
				DATE_FORMAT(date_add(statuspost.postdate, INTERVAL $_SESSION[timezoneoffset]  HOUR), '%m/%d/%y %h:%i%p') as postdate, 
                                (select 'Y' from statusreads st2 
                                    where st2.shareid = statuspost.shareid and
                                    st2.roomid = statuspost.roomid and
                                    st2.xaccode ='X' and st2.providerid = $providerid
                                  ) as flagged,
                                    
				statuspost.shareid, statuspost.roomid, statuspost.likes, statuspost.owner, 
				(select 'Y' from publicrooms where statuspost.roomid = publicrooms.roomid 
				 ) as public,            
                                provider.handle,
                               roominfo.private,
                               roominfo.anonymousflag, blocked1.blockee, blocked2.blocker,
                               provider.profileroomid,
                                (select providerid from roommoderator where roommoderator.roomid = statuspost.roomid
                                 and roommoderator.providerid = $providerid) as moderator
                                from statuspost
				left join provider on statuspost.providerid = provider.providerid
                                left join roominfo on statuspost.roomid = roominfo.roomid
                                left join blocked blocked1 on blocked1.blockee = statuspost.providerid and blocked1.blocker = $providerid
                                left join blocked blocked2 on blocked2.blocker = statuspost.providerid and blocked2.blockee = $providerid
				where parent!='Y' and shareid='$shareid'

				 order by  statuspost.postdate desc limit 5
            ) as s2
            order by postdate2 asc

        "
        );
            
    $i = 0;
    while($row2 = pdo_fetch($result)){

        $comment = FormatComment( "", $row2['postid'], $row2['owner'], $roomid, $row2['encoding'], 
                $row2['comment'], '', $row2['photo'], $row2['album'], $row2['video'], $row2['link'], "","N",  
                $mainwidth, $statuswidth2, $row2['videotitle'], 0, "0", $readonly,
                $row2['blockee'], $row2['blocker']);
        
        $posterobj = RoomPosterInfo( $roomid, $row2['owner'],$row2['avatarurl'], $adminroom, $private,
                $row2['anonymous'], $anonymousflag, $row2['providername'], $row2['name2'], 
                $row2['alias'], $handle, $row2['blockee'], $row2['blocker'], $row2['medal']  );
        $postername = $posterobj->name;

        $avatarurl2 = RootServerReplace(HttpsWrapper($posterobj->avatar));

        $cleanPostid = str_replace(".",'',$row2['postid']);
        $post_pid = $row2['providerid'];
        $post_pid_action = "userview";
        
        if(intval($row2['profileroomid'])>0){
            $post_pid_action = 'feed';
        }
        if($row2['active']=='N'){
            $post_pid_action = '';
        }
        
        if($posterobj->nochat == 'Y' || $providerid == $post_pid){
            $post_pid = '';
            $post_pid_action = '';
        }
        $usermedal = "";
        if($posterobj->medal=='1'){
            $usermedal = $braxmedal;
        }
        $avatarimg = "<div class='circular' style='height:30px;width:30px;overflow:hidden;margin-right:10px;position:relative;top:0px'>
                <img class='$post_pid_action' src='$avatarurl2' 
                style='max-width:100%;min-height:100%;cursor:pointer;'
                data-providerid='$post_pid' data-name='$postername'    
                data-roomid='$row2[profileroomid]' data-caller='$selectedroomid'
                data-profile='Y'
                data-mode ='S' data-passkey64=''
                />
                </div>";
        
        
        $like2button = LikeButton($providerid, $row2['likes'], $shareid, $row2['postid'], $row2['roomid'], $selectedroomid,"float:right","N",$scrollreference);
        $deletebutton = DeleteButton("N", $owner, $row2['owner'], $row2['moderator'], $providerid, $row2['shareid'], $row2['postid'], $roomid, $roomid,"float:right;padding-left:15px;top:5px",$scrollreference );
        
        $postdate = InternationalizeDate($row2['postdate']);
        
        $postdate = "                
                <div class=smalltext style='color:gray;'>$postdate</div>
            ";
        if($readonly!='Y'){
            $commentshow .= "
                $deletebutton
                $like2button 
                    ";
        }
        $commentshow .= "
                <div style='display:inline-block'>
                        $avatarimg
                </div>
                <div id='$cleanPostid' class='roomothertext' data-reply='$postername' style='display:inline-block;vertical-align:top;'>
                        <span class='pagetitle3' style='color:black;'><b>$postername $usermedal</b></span><br>
                        $postdate
                </div>
                <br>
               $comment<br>
                <br>
                
                ";

        
    }    
    return $commentshow;
}
?>
