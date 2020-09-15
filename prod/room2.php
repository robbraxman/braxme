<?php
session_start();
set_time_limit ( 30 );
require("validsession.inc.php");
require_once("config.php");
require_once("crypt.inc.php");
include("lib_autolink.php");
require_once("room.inc.php");    
require_once("roomuserview.php");
 
    $timestart = microtime();

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("PURIFY",$_SESSION['pid']);
    $host = @tvalidator("PURIFY",$_POST['host']);
    
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
    $filtername = rtrim(@tvalidator("PURIFY",$_POST['filtername']));
    $filterterm = rtrim(@tvalidator("PURIFY",$_POST['filterterm']));
    $filterdate = @tvalidator("PURIFY",$_POST['filterdate']);
    $room = @tvalidator("PURIFY",$_POST['room']);
    $roomid = @tvalidator("PURIFY",$_POST['roomid']);
    $selectedroomid = @tvalidator("PURIFY",$_POST['selectedroomid']);
    $page = @tvalidator("PURIFY",$_POST['page']);
    $timezone = @tvalidator("PURIFY",$_POST['timezone']);
    $trimcolor = @tvalidator("PURIFY",$_POST['trimcolor']);
    $readonly = @tvalidator("PURIFY",$_POST['readonly']);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
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
    
    if($filterdate == ''){
        $filterdate = '3015-05-01';
    }
    SaveLastFunction($providerid,"R", $roomid);
    
    
    //$check = "<img src='../img/check-yellow-128.png' style='position:relative;top:3px;height:15px;width:auto;padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
    $check = $global_icon_check;
    
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
                    src='../img/arrow-circle-up-128.png' style='padding-left:10px' />
                    ";
    if($page == 0){
        $previous = "";
    }
    $next = "
                    <img class='feed tapped icon25'
                    data-mode='+' data-roomid='$roomid' data-page='$nextpage'                    
                    src='../img/arrow-circle-down-128.png' style='padding-left:10px' />
                ";

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
    
    $roomobj = MemberCheck($providerid, $roomid);
    //$roomid = $roomobj->roomid;
    $roomForSql = $roomobj->roomforsql;
    $roomHtml = $roomobj->roomhtml;
    $owner = $roomobj->owner;
    $showmembers = $roomobj->showmembers;
    $roomowneronly = "display:none";
    $roomowneronly2 = "display:none";
    if($providerid == $roomobj->owner || $_SESSION['superadmin']=='Y' ){
        $roomowneronly = 'display:inline';
        $roomowneronly2 = $roomowneronly;
        if($roomobj->anonymous == 'Y'){
            $roomowneronly2 = 'display:none';
        }
        $showmembers = "Y";
    } else 
    if($providerid == $roomobj->moderator ){
        $roomowneronly = 'display:none';
        $roomowneronly2 = 'display:inline';
        if($roomobj->anonymous == 'Y'){
            $roomowneronly2 = 'display:none';
        }
        $showmembers = "Y";
    }
    $moderator = "";
    if($roomobj->moderator!=''){
        $owner = $roomobj->moderator;
        $moderator = $roomobj->moderator;
        $showmembers = "Y";
    }
    $ownername = $roomobj->ownername;
    $handle = $roomobj->handle;
    $sponsor = $roomobj->sponsor;
    $anonymousflag = $roomobj->anonymous;
    $member = $roomobj->member;
    $private = $roomobj->private;
    $groupname = $roomobj->groupname;
    $adminonly = $roomobj->adminonly;
    $adminroom = $roomobj->adminroom;
    $unread = $roomobj->unread;
    $radiostation = $roomobj->radiostation;
    $radiomode = "";
    if($radiostation=='Y'){
        $radiomode = "";
    }
    
    ApplySponsor($providerid, $sponsor);
    
    $sizing = RoomSizing();
    //$mainwidth = $sizing->mainwidth;
    //$statuswidth = $sizing->statuswidth;
    //$statuswidth2 = $sizing->statuswidth2;
    //$padding = $sizing->padding;
    
    /*************************************************
     *  INCLUDE MODE HANDLER
     * 
     *      
     */
    require("room_mode.inc.php");    
    /*************************************************
     * 
     */
    $ownerinfo = RoomOwner($providerid, $roomid, $sizing->mainwidth, $page);
   
    $roomOwner = $ownerinfo->roomOwner;
    $roomOwner_room = $ownerinfo->room;
    $roomOwner_ownername = $ownerinfo->ownername;
    $roomOwner_roomdesc = $ownerinfo->roomdesc;
    $roomOwner_photourl = $ownerinfo->photourl;
    $roomOwner_handle = $ownerinfo->handle;
    $roomOwner_chatid = $ownerinfo->chatid;
    $roomOwner_chatidquiz = $ownerinfo->chatidquiz;
    $roomOwner_parentroomid = $ownerinfo->parentroomid;
    $roomOwner_parentroomhandle = $ownerinfo->parentroomhandle;
    $roomOwner_profileflag = $ownerinfo->profileflag;
    $roomOwner_avatarurl = $ownerinfo->avatarurl;
    $roomOwner_profileroomid = $ownerinfo->profileroomid;
    $roomOwner_ownerid = $ownerinfo->ownerid;
    $roomOwner_postcount = $ownerinfo->postcount;
    
    if($readonly == 'Y'){
        //$roomOwner_handle = "";
    }
    
    if($roomOwner_profileflag == 'Y'){
        $roomOwner_room = "ABOUT ME";
    }

    $room_display_mode = 'ROOM';

    
    /**********************************************
     * 
     * D I S P L A Y     S E C T I O N
     * 
     **********************************************/
    
    $anonymous_settings = '';
        $anonymous_opacity = "";
    if( $anonymousflag == 'N' || $roomOwner_profileflag == 'Y') {
        $anonymous_settings = "disabled";
        $anonymous_opacity = "opacity:0.5;";
    }
    

    $radiolink= RadioLink($radiostation, $roomOwner_chatid, $roomOwner_chatidquiz);
    
    $holdingbucket =  MobileInput( $roomid, $page, $room, $sizing->mainwidth, $anonymous_settings, $anonymous_opacity, $readonly );
    $topbarbuttons = TopBarButtons($roomobj, $providerid, $owner, $handle, $roomid, $anonymousflag, $showmembers, $readonly, $adminonly, $shareroom, $private, $roomOwner_profileflag, $roomOwner_avatarurl, $roomOwner_profileroomid);
    $privatetext = PrivateHeader($providerid, $roomid, $anonymousflag, $private, $groupname, $adminonly, $radiostation, $readonly, $roomOwner_profileflag );

    $profile = ShowMyProfile($providerid, $roomOwner_ownerid, $caller, $roomOwner_profileflag );
    $topbar =  TopBar( $caller, $owner, $readonly, $roomOwner_profileflag );
    $childlinks = GetChildLinks($roomOwner_parentroomhandle, $roomOwner_parentroomid, $roomOwner_handle);
    $desktopInput = DesktopInput( $roomid, $anonymous_settings, $anonymous_opacity );

    /**********************************************
     * 
     * 
     *   START OUTPUT
     * 
     * 
     **********************************************/
    
    
    echo "$holdingbucket";
    
    echo $topbar;
    
    echo $profile;
    
    echo $topbarbuttons;
    
    echo "
        <table class='roomcontent gridnoborder' style='background-color:transparent;width:$sizing->mainwidth;padding:0;margin-left:auto;margin-right:auto;margin-top:0;'>
         <tr class='gridnoborder' style='margin:0;border:0;'>
            <td class='gridnoborder' style='background-color:white;color:black;margin:0;padding:0 0 0 0;text-align:center'>
                <div class='pagetitle' style='color:black;padding-left:20px;padding-right:20px'><b>$roomOwner_room</b></div>
                <div class='pagetitle2a' style='color:black;padding-left:20px;padding-right:20px'>$roomOwner_handle $roomOwner_roomdesc</div>
                <div class='pagetitle3' style='color:red;padding-left:20px;padding-right:20px'>$privatetext</div>
                $radiolink
                <br>
            </td>
        </tr>
        ";
    
    if($readonly!='Y' && $roomOwner_profileflag ==''){
        
        echo
        "  
        <tr class='gridnoborder pagetitle3' style='margin:0;border:0;padding:0'>
            <td class='gridnoborder' style='cursor:pointer;background-color:transparent;color:black;padding:0 0 0 0;text-align:left'>
                    <div class='feed tapped' style='$roomowneronly'  data-roomid='' title='Refresh Data'>
                        <img class='icon25' src='../img/Refresh_120px.png' style='top:10px;padding-left:10px' />
                    </div>
                    &nbsp;&nbsp;&nbsp;
                    <div class='friends tapped' style='$roomowneronly'  data-mode='E' data-caller='friendlist' data-roomid='$roomid' title='Room Settings'>
                        <img class='icon25' src='../img/Gear_120px.png' style='top:10px;$roomowneronly' />
                    </div>
                    &nbsp;&nbsp;&nbsp;
                    <div class='chatinvite tapped' style='$roomowneronly2'  data-mode='S' data-caller='friendlist' data-roomid='$roomid' title='Chat for Room'>
                        <img class='icon25' title='Spawn a Chat for Room' src='../img/Speach-Bubble_120px.png' style='top:10px;$roomowneronly2' />
                    </div>
                    ";
        
        if(($_SESSION['enterprise']=='Y' || $_SESSION['superadmin']=='Yx') && $roomOwner_profileflag!='Y'){
            
            echo "
                    &nbsp;&nbsp;&nbsp;
                    <div class='chatinvite tapped' style='$roomowneronly2'  data-mode='S' data-caller='friendlist' data-roomid='$roomid' data-radiostation='Y' title='Channel for Room'>
                        <img class='icon25' title='Spawn a Broadcast Station' src='../img/Communication-Tower-2_120px.png' style='top:10px;$roomowneronly2' />
                    </div>
                    ";
        }
        
        echo "
                    <br style='$roomowneronly'><br style='$roomowneronly'>
            </td>
        </tr>
        ";
    }

    
    if($childlinks!=''){
        
        echo
        "
        <tr>
            <td style='padding-left:10px'>
            $childlinks
            </td>
        </tr>    
        ";
    }

    if( $readonly !='Y' && ($adminonly =='N' || ($adminonly == 'Y' && $owner == $providerid))) {
        
        echo
        "
        <tr>
            <td>
                  <table class='gridnoborder makecommentowner' style='background-color:transparent;width:100%'>
                  <tr class='noaction' data-roomid='$roomid'>
                      <td class='' style='cursor:pointer;;background-color:transparent;padding-left:0px;padding-bottom:13px'> 

                          $roomtip
                      </td>
                  </tr>
                  <tr class='makeaction'>
                      <td class='feedpost gridstdborder' style='width:$sizing->mainwidth;background-color:transparent;padding:10px 20px 10px 20px'> 
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
    
    if( $roomobj->roomfiles!=''){
        echo "
        <tr style='background-color:white'>
            <td class='mainfont' style='padding:10px;'>
                <b>$check Room Files Updated</b> $roomobj->roomfiles<br>
            </td>
        </tr>
        <tr>
            <td>
                <br>
            </td>
        </tr>
        ";
    }
    

    
    $result = do_mysqli_query("1",
        "
            select statuspost.anonymous, statuspost.encoding, statuspost.postid, 
            statuspost.pin, statuspost.locked,
            providername, provider.name2, statuspost.comment, statuspost.link, 
            statuspost.photo, statuspost.video,  statuspost.videotitle, statuspost.roomid,
            avatarurl, alias, statuspost.providerid, statuspost.articleid,
            DATE_FORMAT(date_add(statuspost.postdate,INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), 
                '%b %d/%y %a %h:%i %p') as postdate, 
            (select actiontime from statusreads st2 
                where st2.shareid = statuspost.shareid and
                st2.roomid = statuspost.roomid and
                st2.xaccode  not in ('R','D') 
             order by actiontime desc limit 1 ) as lastpostdate,
            statuspost.shareid, statuspost.room, statuspost.roomid, statuspost.likes, 
            statuspost.owner,
            (   select distinct providername from provider
                where providerid = statuspost.owner
            ) as ownername,
            (select 'Y' from publicrooms where statuspost.roomid = publicrooms.roomid) as public,
            (select handle from roomhandle where roomhandle.roomid = statuspost.roomid )
                as handle,
            roominfo.anonymousflag, blocked1.blockee, blocked2.blocker,
            provider.profileroomid
            from statuspost
            left join provider on statuspost.providerid = provider.providerid
            left join roominfo on statuspost.roomid = roominfo.roomid
            left join blocked blocked1 on blocked1.blockee = statuspost.providerid and blocked1.blocker = $providerid
            left join blocked blocked2 on blocked2.blocker = statuspost.providerid and blocked2.blockee = $providerid

            where statuspost.parent = 'Y' and
            statuspost.roomid  = $roomid
            order by  pin desc, lastpostdate  desc  limit $limitstart, $limitend 
    ");
    
    
    $postcount = 0;
    while($row = do_mysqli_fetch("1",$result)){
        
        $postcount++;
        $cleanPostid = str_replace(".","",$row['postid']);
        
        $comment = "<br>";
        if($row['blockee']!=''){
            $comment = "&nbsp;&nbsp;(Blocked content)";
        }
        if($row['blockee']=='' && $row['blocker']==''){
            $comment = FormatComment( $row['postid'], $row['owner'], $row['roomid'], $row['encoding'], $row['comment'], $row['photo'], 
                $row['video'], $row['link'],"width:$sizing->statuswidth2","Y", 
                $sizing->mainwidth, $sizing->statuswidth2, $row['videotitle'], $row['articleid'], $page, $readonly );
        }
        
        
        $posterobj = RoomPosterInfo($row['roomid'], $row['owner'], $row['avatarurl'], $adminroom, $private,
                $row['anonymous'], $row['anonymousflag'], $row['providername'], $row['name2'], 
                $row['alias'], $row['handle'] );
        $avatarurl = HttpsWrapper($posterobj->avatar);
        $postername = $posterobj->name;
        if($avatarurl == "$rootserver/img/faceless.png"){
            $avatarurl = "$rootserver/img/egg-blue.png";
        }
        if($row['blockee']!='' || $row['blocker']!=''){
            $avatarurl = "$rootserver/img/egg-blue.png";
            $postername = "Unknown";
        }
        
        if($roomid!=='All'){
            $avatarimg = "<img class='circular avatar1' src='$avatarurl' style='' />";
        } else {
            $avatarimg = "<img class='icon30' src='$avatarurl' style='position:relative;top:0px;' />";
            //$avatarimg = "";
        }
        if( InternetTooSlow()){
            //$avatarimg = '';
        }

        
            
        $likebutton = LikeButton("$providerid", $row['likes'], $row['shareid'], 
                $row['postid'], $row['roomid'], $selectedroomid,"","Y",$cleanPostid );
        $deletebutton = DeleteButton("Y","$owner", "$row[providerid]", $providerid, $row['shareid'], 
                $row['postid'], $row['roomid'], $roomid,"top:0px",$cleanPostid );

        $roomof = htmlentities(strtoupper($row['room']));
        $roomowner = htmlentities($row['ownername']);
        $roomof1 = strtoupper($row['room']);
        $roomfit = $row['room'];
        if( strlen($row['room'])>25) {
            $roomfit = preg_replace('/\s+?(\S+)?$/', '', substr($row['room'], 0, 25));
        }
        $roomof1A = strtoupper($roomfit);//." <span style='color:gray'>of $roomowner</span>";
        if($row['handle']!=''){
            $roomof1 = $row['handle'];
        }
        $jumptoroomid = "$row[roomid]";
        if($roomid!='All' && $roomid!='') {
            $jumptoroomid = "All";
            $roomof1 = "";
        }
        
        
        $pin = "";
        if(intval($row['pin'])>0){
            $pin = "<img class='feed icon15' data-mode='UNPIN' data-postid='$row[postid]' data-roomid='$row[roomid]' data-shareid='$row[shareid]'  src='../img/pin-red-128.png' style='float:right;top:0px' />";
        }
        $locked = intval($row['locked']);

        
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
        $post_pid = "";

        if(intval($row['pin'])>0 && $readonly!='Y'){
            echo "
                <tr class='gridnoborder' style='cursor:pointer'>
                    <td class='feed gridstdborder smalltext2 shadow' style='height:15px;width:50px;padding:0;background-color:$global_menu_color;color:white;text-align:center;vertical-align:top;'
                        data-mode='UNPIN' data-postid='$row[postid]'
                        data-roomid='$row[roomid]'  data-shareid='$row[shareid]'
                        style='cursor:pointer'
                        title='Tap to Unpin'
                    >
                        

                    Pinned
                    </td>
                </tr>
                ";
        }
        if($row['owner'] > 0){
            $post_pid = $row['owner'];
            $post_pid_action = 'userview';
            
            if(intval($row['profileroomid'])>0){
                $post_pid_action = 'feed';
            }
            
            if($posterobj->nochat == 'Y' || $post_pid == $providerid ){
                $post_pid = '';
                $post_pid_action = '';
            }
            echo "
                <tr class='gridnoborder'>
                    <td class='gridstdborder mainfont shadow' style='width:50px;padding:0;background-color:white;text-align:left;vertical-align:top'>
                    </td>
                </tr>
                ";
        }

        echo "
                <tr class='gridnoborder rounded'>
                    <td class='gridstdborder commentline mainfont  shadow' 
                        style='padding:$sizing->padding;width:$sizing->statuswidth;background-color:white;overflow:none;word-wrap:break-word'>          
                        $comment
                        <div style='display:inline-block;padding-left:20px;padding-top:10px;padding-bottom:10px;margin:0;text-align:left;vertical-align:top' >

                            <div class='circular' style='height:30px;width:30px;overflow:hidden;background-color:white'>
                            <img class='$post_pid_action' src='$avatarurl' style='cursor:pointer;min-height:100%;max-width:100%'
                                data-providerid='$post_pid' data-name='$postername'    
                                data-roomid='$row[profileroomid]' data-caller='$roomid'
                                data-mode ='S' data-passkey64=''
                             />
                             </div>

                        </div>
                        <div class='pagetitle3 feed tooltip' style='cursor:pointer;display:inline-block;vertical-align:top;padding-left:10px;padding-top:10px' 
                            data-roomid='$row[roomid]' title='Go to Room'>
                                <div class='pagetitle3' style='color:black;'><b>$postername</b></div>
                                    $roomof1
                        </div>
                ";
        
        
        if($readonly == 'Y'){
            $cleanPostid = str_replace(".","",$row['postid']);
            echo "
                    <div id='$cleanPostid'
                        style='padding:5px 15px 0px 15px;color:black;margin:auto'>
                        <div class='roomcommentsarea' style='display:inline'>
                ";

        } else {
                
                $cleanPostid = str_replace(".","",$row['postid']);
                echo "
                        <div id='$cleanPostid'
                            style='padding:5px 20px 0px 20px;color:black;margin:auto'>
                            $likebutton
                            &nbsp;
                            <!--
                            <img class='roomcontrolbutton icon20' src='$rootserver/img/more-orange.png'  style='top:0px'/>
                            -->
                            <img class='icon15 showroomcomment stdicon tooltip roomcontrols' src='$rootserver/img/Gey_120px.png'  
                                style='top:0px'
                                data-mode='X'
                                title='View post activity'
                                data-shareid='$row[shareid]' data-anchor='$row[postid]'
                                />
                            &nbsp;
                            <img class='icon15 feed tooltip roomcontrols tapped2' 
                                data-mode='B' data-postid='$row[postid]'
                                data-roomid='$row[roomid]'  data-shareid='$row[shareid]'
                                title='Bump to Top' 
                                src='$rootserver/img/Up-4_120px.png' style='top:0' 
                                />
                            $deletebutton 
                            &nbsp;
                            <img class='icon15 feed tooltip roomcontrols tapped2' 
                                data-mode='FLAG' data-postid='$row[postid]'
                                data-roomid='$row[roomid]'  data-shareid='$row[shareid]'
                                title='Report Objectionable Content' src='$rootserver/img/Flag_120px.png' 
                                style='top:0' 
                                />
                     ";         
                if($owner == $providerid){
                    echo "                
                            &nbsp;
                            <img class='feed icon15 tooltip roomcontrols tapped2' 
                                data-mode='PIN' data-postid='$row[postid]'
                                data-roomid='$row[roomid]'  data-shareid='$row[shareid]'
                                title='Pin Post' src='$rootserver/img/pin-line-128.png' 
                                style='top:0' 
                                />
                    ";
                    if($locked > 0 ){
                        $lockicon = "$rootserver/img/Key-Lock_120px.png";
                    } else {
                        $lockicon = "$rootserver/img/Lock-2_120px.png";
                    }
                    echo "                
                            &nbsp;
                            <img class='feed icon15 tooltip roomcontrols tapped2' 
                                data-mode='LOCK' data-postid='$row[postid]'
                                data-roomid='$row[roomid]'  data-shareid='$row[shareid]'
                                title='Lock Thread' src='$lockicon' 
                                style='top:0' 
                                />
                    ";
                }

                echo "
                            <div class=smalltext style='color:gray;float:right'>$row[postdate]</div>
                        <div class='roomcommentsarea' style='display:inline'>
                        <br><br>
                        ";
            }
            
            $result2 = do_mysqli_query("1",
                "
                    select count(*) as commenttotal from
                    statuspost where parent!='Y' and shareid='$row[shareid]'
                ");
            $row2 = do_mysqli_fetch("1",$result2);
            $commentitems = $row2['commenttotal'];

            /**********
             * 
             * COMMENTS SECTION
             * 
             * *********/
            echo "  
                                <div class='hideroomcomment roomcommenthideheader' 
                                    data-postid='$row[postid]'  style='color:$global_activetextcolor;cursor:pointer;display:none'>
                                    Hide Comments
                                </div>
                         ";
            if(intval($commentitems) > 0 )
            {
                echo " 
                                <hr style='border:1px solid lightgray'>
                                <div class='showroomcomment roomcommentheader' 
                                    data-shareid='$row[shareid]' data-anchor='$row[postid]'
                                        style='color:$global_activetextcolor;cursor:pointer' data-mode=''>
                                    $commentitems Comments
                                </div>
                        ";
                    $default_subdisplay = "";
            } else {
                
                echo " 
                                <div class='showroomcomment roomcommentheader' 
                                    data-shareid='$row[shareid]' data-anchor='$row[postid]' data-mode=''
                                        style='color:$global_activetextcolor;cursor:pointer;display:none'>
                                </div>
                        ";
                    $default_subdisplay = "display:none";

            }

            $commentshow = '';
            if($roomid!='All'){
                $commentshow =  LastComment($owner, $adminroom, $row['shareid'], $row['handle'], "$providerid", 
                        $row['roomid'], $selectedroomid, $commentitems, $anonymousflag, 
                        $sizing->mainwidth, $sizing->statuswidth2, $private, $cleanPostid, $readonly );
            }
            
            $replycomment = "";
            if(
               ( $locked == 0 && $readonly!='Y' && ( $adminonly =='N' || $owner == $providerid ) ) 
            ){
                
                $replycomment = DesktopInputReply( $roomid, $row['roomid'], $row['shareid'], $row['postid'], $anonymous_settings, $anonymous_opacity  );
            }        
                    

            echo "  
                                <div class='gridnoborder roomcomment' 
                                    style='$default_subdisplay;background-color:white;
                                     padding-left:0px;padding-right:10px;padding-top:5px;
                                     width:$sizing->statuswidth2;word-wrap:break-word'>
                                $commentshow
                                </div>
                                $replycomment
                        </div>
                        <br>
                    </div>
            ";
        
        echo "
                
                    </td>
               </tr>
               <tr>
                    <td>                        
                            <br><br><br>
                    </td>
               </tr>
            ";
        
        
        
    }
    
    $timeend = microtime();   
    $timeelapsed = $timeend - $timestart;
    echo "</table></div>";
    if($postcount >= $limitend -1) {
        //       $sizing->mainwidth $_SESSION[sizing] $_SESSION[innerwidth] $sizing->padding
        echo "<center><span class=roomcontent>$next</span></center>
             <br>";
    }
    if($readonly!='Y' && $roomOwner_profileflag ==''){
        
        if(intval($roomid) > 1 ){

            echo "
            
                <br>&nbsp;&nbsp;
                <span class='roomcontent' style='color:black'>
                <img class='icon15 friends tapped' src='../img/Close_120px.png' style='' 
                id='deletefriends' 
                data-providerid='$providerid' data-roomid='$roomid' data-mode='D' data-caller='room' />
                Remove me from Room
                </span><br><br>
                ";
            echo "
                <div class='privacytip smalltext' style='padding:20px;margin:auto;cursor:pointer;color:firebrick;text-align:center'>
                    <b>Privacy Tips</b>
                </div>
                ";
        }
        
    }
    
    
    
    //echo "<img class='scrolltotop tooltip nonmobile' title='Scroll to Top' src='$rootserver/img/arrowhead-up-gray-128.png' style='height:15px;cursor:pointer;padding-right:10px;padding-top:10px;padding-left:10px;float:left' />";
    
    
function LastComment( $owner, $adminroom, $shareid, $handle, $providerid, $roomid, $selectedroomid, $commentitems, $anonymousflag, $mainwidth, $statuswidth2, $private, $scrollreference, $readonly )
{
    global $rootserver;
    global $owner;
    
    $commentshow = "";
    if($commentitems > 5 ){
        $commentshow = "<span class='pagetitle3'><b>...</b></span><br><br>";
    }
        
    $result2 = do_mysqli_query("1",
         
        "
            select anonymous, encoding, postid, providername, comment, link, photo, video, videotitle,
            avatarurl, alias, providerid, name2, postdate2, postdate,
            shareid, roomid, likes, owner, public, handle, private, anonymousflag, blockee, blocker, profileroomid
            
            from 
            (
				select statuspost.anonymous, statuspost.encoding, postid, providername, statuspost.comment, statuspost.link, statuspost.photo, statuspost.video, statuspost.videotitle,
				avatarurl, alias, statuspost.providerid, provider.name2, statuspost.postdate as postdate2,
				DATE_FORMAT(date_add(statuspost.postdate, INTERVAL $_SESSION[timezoneoffset]  HOUR), '%b %d %a %h:%i %p') as postdate, 
				statuspost.shareid, statuspost.roomid, statuspost.likes, statuspost.owner, 
				(select 'Y' from publicrooms where statuspost.roomid = publicrooms.roomid 
				 ) as public,            
                                provider.handle,
                               roominfo.private,
                               roominfo.anonymousflag, blocked1.blockee, blocked2.blocker,
                               provider.profileroomid
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
    while($row2 = do_mysqli_fetch("1",$result2)){

        $comment = "";
        if($row2['blockee']!=''){
            $comment = "(Blocked content)";
        }
        if($row2['blockee']=='' && $row2['blocker']==''){
            $comment = FormatComment( $row2['postid'], $row2['owner'], $roomid, $row2['encoding'], $row2['comment'], $row2['photo'], $row2['video'], $row2['link'], "","N",  $mainwidth, $statuswidth2, $row2['videotitle'], 0, "0", $readonly);
        }
        
        $posterobj = RoomPosterInfo( $roomid, $row2['owner'],$row2['avatarurl'], $adminroom, $private,
                $row2['anonymous'], $anonymousflag, $row2['providername'], $row2['name2'], 
                $row2['alias'], $handle );
        $postername = $posterobj->name;

        $avatarurl2 = HttpsWrapper($posterobj->avatar);
        if($avatarurl2 == "$rootserver/img/faceless.png"){
            $avatarurl2 = "$rootserver/img/egg-blue.png";
        }
        if($row2['blockee']!='' || $row2['blocker']!=''){
            $avatarurl2 = "$rootserver/img/egg-blue.png";
            $postername = "Unknown";
        }

        $cleanPostid = str_replace(".",'',$row2['postid']);
        $post_pid = $row2['providerid'];
        $post_pid_action = "userview";
        
        if(intval($row2['profileroomid'])>0){
            $post_pid_action = 'feed';
        }
        
        if($posterobj->nochat == 'Y' || $providerid == $post_pid){
            $post_pid = '';
            $post_pid_action = '';
        }
        $avatarimg = "<div class='circular' style='height:30px;width:30px;overflow:hidden;margin-right:10px;position:relative;top:0px'>
                <img class='$post_pid_action' src='$avatarurl2' 
                style='max-width:100%;min-height:100%;cursor:pointer;'
                data-providerid='$post_pid' data-name='$postername'    
                data-roomid='$row2[profileroomid]' data-caller='$selectedroomid'
                data-mode ='S' data-passkey64=''
                />
                </div>";
        //if( InternetTooSlow())
        //{
        //    $avatarimg = '';
        //}
        
        
        $like2button = LikeButton($providerid, $row2['likes'], $shareid, $row2['postid'], $row2['roomid'], $selectedroomid,"float:right","N",$scrollreference);
        $deletebutton = DeleteButton("N", $owner, $row2['owner'], $providerid, $row2['shareid'], $row2['postid'], $roomid, $roomid,"float:right;padding-left:15px;top:5px",$scrollreference );
        
        $postdate = "                
                <div class=smalltext style='color:gray;'>$row2[postdate]</div>
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
                        <span class='pagetitle3' style='color:black;'><b>$postername</b></span><br>
                        $postdate
                </div>
                <br>
               $comment<br>
                <br>
                
                ";

        
    }    
    return $commentshow;
}

function DesktopInput( $roomid, $anonymous_settings, $anonymous_opacity )
{
    global $global_activetextcolor;
    
    return "
        <img class='icon15 hidecomment' title='Collapse Comment Box' src='../img/minus-gray-128.png' style=float:right;right:0;' />


        <span class='pagetitle3'><b>Start New Topic</b></span><br>
        <input class='commentwidth mainfont' id='statustitle' placeholder='Title' name='title' style='background-color:white;' /><br>
        <br>
        <textarea class='commentwidth roominputfocus mainfont' id='statuscomment' placeholder='Comment, links, photo, video' name='comment' style='padding:5px' rows=3></textarea>
        <span class='statusphoto' style='color:black;display:none'>
            <br>
            <b>Share Photo</b><br>
            <input class='commentwidth mainfont' id='statusphoto' type='url' title='Photo Link' value=''>
            <br><br>
        </span>

        <span class='statusfile' style='color:black;display:none'>
            <br>
            <b>Share File</b><br>
            <input class='commentwidth mainfont' id='statusfile' type='url' title='File Link'  value=''>
            <br><br>
        </span>

              <img class='icon25 feed'
                  title='Post Message'
                  src='../img/Arrow-Right-in-Circle_120px.png'
                  style='top:0px'
                  id='post' data-mode='P' data-mobile=''  data-shareid='' data-roomid='$roomid'  data-selectedroomid='$roomid' />

        <br><br>
              <div class='smalltext' style='display:inline-block;height:50px;width:60px;text-align:center;color:gray'>
                  <div class='openstatusphoto photoselect' 
                       id='photoselect_icon' data-target='#statusphoto' data-album='' 
                       data-src='#statusphoto' data-filename='' data-mode='X' data-caller='feed'  style='cursor:pointer;color:$global_activetextcolor'>

                        <!--<img class='icon25' src='../img/brax-photo-round-gold-128.png' style='top:0px;' />-->
                        Share<br>Photo
                  </div>
                  <br>
              </div>
              <div class='smalltext' style='cursor:pointer;display:inline-block;height:50px;width:60px;text-align:center;color:gray'>
                  <div class='openstatusfile fileselect' 
                       id='fileselect_icon' data-target='#statusfile' data-album='' 
                       data-src='#statusfile' data-filename='' data-link=''  data-caller='room' style='cursor:pointer;color:$global_activetextcolor' >

                        <!--<img class='icon25' src='../img/brax-doc-round-gold-128.png' style='top:0px;' />-->
                        Share<br>File
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

function DesktopInputReply( $selectedroomid, $roomid, $shareid, $postid, $anonymous_settings, $anonymous_opacity )
{
    global $global_activetextcolor;
    
    return "
        <span class='noaction makecomment'  data-shareid='$shareid' data-roomid='$roomid' data-reference='$postid'  >
            <input class='mainfont noaction' placeholder='Topic Reply' readonly=readonly name='title' data-shareid='$shareid' data-roomid='$roomid'  
                style='width:100%;min-width:50%;background-color:white;' /><br>
            <br>
        </span>

        <div class=makeaction>
            <img class='hidecomment icon20' src='../img/minus-gray-128.png' style='top:0px;float:right;top;0;right:0;' />

            <br>
            <textarea class='commentwidth replycomment mainfont' id='replycomment'  placeholder='Topic Reply' name='replycomment' style='border-size:1px;padding:5px;' rows=3></textarea>
            <span class='replyphotospan' style='color:#00A0E3;display:none'>
                <br>
                <b>Share Photo</b><br>
                <input class='replyphoto commentwidth mainfont'  id='replyphoto'  type='url' size=70 title='Photo Link' >
                <br><br>
            </span>

            <span class='replyfilespan' style='color:#00A0E3;display:none'>
                <br>
                <b>Share File</b><br>
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
                    Share<br>Photo

                    <!--<img class='icon25'  src='../img/brax-photo-round-gold-128.png' style='top:0px;' />-->
                </div>
                <br>
            </div>
            <div class='smalltext' style='cursor:pointer;display:inline-block;height:50px;width:60px;margin-top:5px;text-align:center;color:gray'>
                <div class='openreplyfile fileselect tapped' 
                    style='cursor:pointer;color:$global_activetextcolor'
                    id='fileselect' 
                    data-target='.replyfile' data-src='.replyfile' data-filename='' data-link='' data-caller='room'  title='My File Library' >
                    Share<br>File

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

function MobileInput( $roomid, $page, $room, $mainwidth, $anonymous_settings, $anonymous_opacity, $readonly )
{
    if($readonly == 'Y'){
        return "";
    }
    
    return "
            <table id='makeaction'  style='display:none;background-color:transparent;width:$mainwidth;border-collapse:collapse;margin:auto'>
            <tr class='gridnoborder pagetitle3' '>
                    <td class='gridnoborder' style='cursor:pointer;background-color:transparent;color:black;padding:10px;text-align:left' >
                        <div class='feed tapped' 
                               data-mode='' style='cursor:pointer;color:black;' data-shareid='' data-roomid='' style='margin-bottom:10px' >
                               <img class='icon25' src='../img/arrow-stem-circle-left-128.png' style='' />
                               &nbsp;
                                   Back
                        </div>
                        <br>
                        <div id='roomstatusheading' class='pagetitle3;font-weight:bold'><b>New Topic</b></div>
                        <input class='commentwidth mainfont' id='roomstatustitle' placeholder='Thread Title' name='title' style='background-color:white;margin-bottom:5px' />
                        <textarea class='commentwidth mainfont' id='roomstatuscomment' placeholder='Comment, links, photo, video.' name='comment...'  rows=4 style='padding:5px;margin:0'></textarea>
                        <span class='statusphoto' style='color:black;display:none'>
                        <br><b>Share Photo</b><br>
                        <input class='commentwidth mainfont' id='roomstatusphoto' type='url' title='Photo Link' value='' >
                        <br><br>
                        </span>
                        <span class='statusfile' style='color:black;display:none'>
                        <br><b>Share File</b><br>
                        <input class='commentwidth mainfont' id='roomstatusfile' type='url' title='File Link'  value=''  >
                        <br><br>
                        </span>
                        





                        <br>
                                <div class='smalltext' style='display:inline-block;height:80px;width:45px;text-align:center;color:gray'>
                                    <div class='openstatusphoto photoselect tapped' 
                                         id='photoselect_icon' data-target='#roomstatusphoto' data-album='' 
                                         data-src='#roomstatusphoto' data-filename='' data-mode='X' data-caller='feed' >
                                    
                                          <img class='buttonicon' src='../img/brax-photo-round-black-128.png' style='cursor:pointer;position:relative;display:inline;height:30px;width:auto;top:0px;' />
                                    </div>
                                    Share<br>Photo
                                    <br>
                                </div>
                                <div class='smalltext' style='cursor:pointer;display:inline-block;height:80px;width:45px;text-align:center;color:gray'>
                                    <div class='openstatusfile fileselect tapped' 
                                         id='fileselect_icon' data-target='#roomstatusfile' data-album='' 
                                         data-src='#roomstatusfile' data-filename='' data-link=''  data-caller='room' >
                                    
                                          <img class='buttonicon' src='../img/brax-doc-round-black-128.png' style='position:relative;display:inline;height:30px;width:auto;top:0px;' />
                                    </div>
                                    Share<br>File
                                    <br>
                                </div>
                                 <div class='smalltext' style='display:inline-block;height:80px;width:45px;text-align:center;color:gray'>
                                    <div class='uploadphoto2 tapped' 
                                         id='photoselect_icon' data-target='#roomstatusphoto' data-album='' 
                                         data-src='#roomstatusphoto' data-filename='' data-mode='X' data-caller='feed' >
                                    
                                          <img class='buttonicon' src='../img/upload-circle-128.png' style='cursor:pointer;position:relative;display:inline;height:30px;width:auto;top:0px;' />
                                    </div>
                                    Upload<br>Photo
                                    <br>
                                </div>
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
                                    <img src='../img/Arrow-Right-in-Circle_120px.png' 
                                        title='Post Message'
                                        class='icon35 feed'
                                        id='roompostcomment' data-mode=''
                                        data-mobile='Y' data-shareid='' data-roomid='$roomid'  data-selectedroomid='$roomid'
                                        style='' 
                                        />
                                    <img class='icon35 feedreply' src='../img/Arrow-Right-in-Circle_120px.png' 
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
function TopButtons($roomid, $anonymousflag, $showmembers, $readonly, $profileflag )
{
    
    if($readonly == 'Y' || $profileflag == 'Y'){
        return "";
    }
    
    $disable = "";
    $disablemembers = "";
    $opacityfiles = "opacity:1";
    $opacitymembers = "opacity:1";

    //$disable = "disable";
    //$opacityfiles = 'opacity:0.2';
    
    if($anonymousflag =='Y'){
        //Disable buttons if anonymous room
        
        //Always Disable Members if All Anonymous
        $disablemembers = "disable";
        $opacitymembers = 'opacity:0.2';
    }
    if($showmembers !='Y'){
        //Disable buttons if anonymous room
        $disablemembers = "disable";
        $opacitymembers = 'opacity:0.2';
    }
    
    $accessbuttons1 =
            "
            <div class='smalltext2 friendlist$disablemembers tapped roombutton' id='friendlist' data-caller='room' data-mode=''  title='Access Shared Files'
                data-roomid='$roomid' style='color:black;$opacitymembers;'>
                <img class='icon25' src='../img/Address-Book_120px.png' 
                    style='margin-bottom:7px;' />
                <br>Who's
                <br>Here<br>   
            </div>
            <div class='smalltext2 roomfiles$disable tapped roombutton' data-caller='room' data-mode=''  title='Access Shared Files'
                data-roomid='$roomid' style='color:black;$opacityfiles'>
                <img class='icon25' src='../img/Folder_120px.png' 
                    style='margin-bottom:7px' />
                <br>Room
                <br>Files<br>   
            </div>
            <!--
            <div class='smalltext2 roomevents$disable tapped roombutton' data-caller='room'  data-roomid='$roomid' title='Room Calendar Events'
                style='color:black;$opacityfiles'>
                <img class='icon25' src='../img/Calendar-4_120px.png' 
                    style='margin-bottom:7px' />
                <br>Events
                <br><br>
            </div>
            -->
            ";

    return $accessbuttons1;
}

function ShareOptions($roomid, $handle, $shareroom, $readonly, $profileflag )
{
        if($readonly == 'Y' || $profileflag == 'Y'){
            return "";
        }

        $opacity = 'opacity:0.2;';
        if($shareroom!='' && intval($roomid)>0  ){
            if($handle!==''){
                $opacity = 'opacity:1;';
            }
        }

        $shareoptions =  " 
                <div class='smalltext2 roomshareoptions tapped roombutton'  title='Share Room and Invite to Room'
                    style='color:black;$opacity'>
                    <img  class='icon25' src='../img/Share_120px.png' 
                        style='margin-bottom:7px' />
                    <br>Invite
                    <br><br>
                </div>
                ";

    return $shareoptions;
}

function EnterpriseButtons($providerid, $owner, $roomid, $readonly, $profileflag )
{
    return "";
    
    if($readonly == 'Y' || $profileflag == 'Y'){
        return "";
    }
    
    $enterprise = "";
        if( $_SESSION['enterprise']=='Y' ){
            if($owner == $providerid){
                $enterprise = "
                <div class='smalltext2 roomcredential tapped roombutton' data-caller='room'  data-roomid='$roomid' title='Form Request'
                    style='color:black;'>
                    <img class='icon30' src='../img/credentials-128.png' 
                        style='margin-bottom:7px' />
                    <br>Manage
                    <br>eForm<br>
                </div>
                    ";
            }
        }
        return $enterprise;
}
function PrivateHeader($providerid, $roomid, $anonymousflag, $private, $groupname, $adminonly, $radiostation, $readonly, $profileflag )
{
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
    if($radiostation=='Q' ){
        $privatetext = "<span style='color:gray'>Quiz Room</span>";
    } else
    if( $groupname !='' && $private!=='Y'){
        $privatetext = "<span style='color:gray'>$groupname Only</span>";
    } else
    if( intval($roomid)==1 || 
        ($private==='N' && 
        intval($roomid!=1) && $anonymousflag!='Y')){

            /*
            $privatetext = "

                <tr class='gridnoborder'>
                    <td class='gridnoborder gridcell smalltext' style='width:100%;padding:5px;background-color:#ffbc00;color:black;text-align:center;border-top-left-radius:10px;border-top-right-radius:10px'>
                        Open Membership
                        <img class='icon15 roomedit tapped' src='../img/delete-128.png'  
                        id='deletefriends' 
                        data-providerid='$providerid' data-roomid='$roomid' data-mode='D'
                        style='float:right;position:relative;top:0px'
                        />
                    </td>
                </tr>
                        ";
             * 
             */
        $privatetext = "<span style='color:gray'>Open Membership $adminonlytext</span>";
    } else 
    if( $private==='N' &&
        intval($roomid!=1) && 
        $anonymousflag=='Y'){

            /*
            $privatetext = "

                <tr class='gridnoborder'>
                    <td class='gridnoborder gridcell smalltext' style='width:100%;padding:5px;background-color:#ffbc00;color:black;text-align:center;border-top-left-radius:10px;border-top-right-radius:10px'>
                        Anonymous Posts Only
                        <img class='icon15 roomedit tapped' src='../img/delete-128.png'  
                            id='deletefriends' 
                            data-providerid='$providerid' data-roomid='$roomid' data-mode='D'
                            style='float:right;position:relative;top:0px'
                        />
                    </td>
                </tr>
                        ";
             * 
             */
        $privatetext = "<span style='color:gray'>Anonymous Posts Only</span>";
        
    } else 
    if( $private==='Y' &&
        intval($roomid!=1) && 
        $anonymousflag=='Y'){

            /*
            $privatetext = "

                <tr class='gridnoborder'>
                    <td class='gridnoborder gridcell smalltext' style='width:100%;padding:5px;background-color:#ffbc00;color:black;text-align:center;border-top-left-radius:10px;border-top-right-radius:10px'>
                        Anonymous Posts Only
                        <img class='icon15 roomedit tapped' src='../img/delete-128.png'  
                            id='deletefriends' 
                            data-providerid='$providerid' data-roomid='$roomid' data-mode='D'
                            style='float:right;position:relative;top:0px'
                        />
                    </td>
                </tr>
                        ";
             * 
             */
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
        $privatetext = "<span style='color:gray'><img class='icon15' src='../img/Lock-2_120px.png' style='top:3px' /> Private Membership</span>";

        }
    return $privatetext; 
}
function LeaveRoomButton($providerid, $owner, $roomid, $readonly, $private, $profileflag)
{
        if($readonly == 'Y' || $profileflag == 'Y' || $private == 'Y'){
            return "";
        }
    
        $button = "
        <div class='smalltext2 friends tapped roombutton' id='deletefriends' data-providerid='$providerid'  data-roomid='$roomid' data-mode='D' title='Delete from Room' data-caller='room'
            style='color:black;'>
            <img class='icon30' src='../img/Close_120px.png' 
                style='margin-bottom:7px' />
            <br>Leave
            <br>Room<br>
        </div>
            ";
        return $button;
}

function GetChildLinks($parentroomhandle, $parentroomid, $handle )
{
    global $rootserver;
    global $global_activetextcolor;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    if($handle == ''){
        return;
    }
    $childlinks = "";
    if($parentroomid!=''){
        $child = "<div class='feed' data-roomid='$parentroomid' style='color:$global_activetextcolor;cursor:pointer;float:left;padding-right:20px;padding-top:10px;padding-bottom:10px'><u>$parentroomhandle</u></div>";
        $childlinks .= $child;
    }
    
    $result = do_mysqli_query("1","
        select roominfo.room, roominfo.roomid, roomhandle.handle from roominfo 
        left join roomhandle on roominfo.roomid = roomhandle.roomid
        where parentroom='$handle' order by roominfo.childsort desc, roominfo.room asc
    ");
    while($row = do_mysqli_fetch("1",$result)){
        $room = $row['room'];
        $roomhandle = $row['handle'];
        $roomid = $row['roomid'];
        $id = substr($roomhandle,1);
        if($roomhandle!=''){
            $child = "<div class='feed' data-roomid='$roomid' style='color:$global_activetextcolor;cursor:pointer;float:left;padding-right:20px;padding-top:10px;padding-bottom:10px'><u>$room</u></div>";
        }
        $childlinks .= $child;

    }
    
    return $childlinks;
    
}
function AvatarButton($profileflag, $avatarurl, $ownerid, $profileroomid )
{
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

function TopBarButtons($roomobj, $providerid, $owner, $handle, $roomid, $anonymousflag, $showmembers, $readonly, $adminonly, $shareroom, $private, $roomOwner_profileflag, $avatarurl, $profileroomid)
{
    $avatarbutton = AvatarButton($roomOwner_profileflag, $avatarurl, $owner, $profileroomid );
    
    $accessbuttons1 =  TopButtons($roomid, $anonymousflag, $showmembers, $readonly, $roomOwner_profileflag );
    $enterprise = EnterpriseButtons($providerid, $owner, $roomid, $readonly, $roomOwner_profileflag );
    $shareoptions = ShareOptions($roomid, $handle, $shareroom, $readonly, $roomOwner_profileflag );
    $leaveroombutton =  LeaveRoomButton($providerid, $owner, $roomid, $readonly, $private, $roomOwner_profileflag);
 
    $topbar = "
        <span class='roomcontent'>
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
            <br>
        </span>
        ";
    
    return $topbar;
}
function BackAction($caller, $readonly)
{
    $backto = "roomselect";
    
    if($readonly == 'Y'){
        $backto = "tilebutton";
    }
    
    if($caller=='find' ){
        $backto = "meetuplist";
    }
    
    if( $caller=='leave'){
        $backto = "leave";
    }
    
    if( $caller=='none'){
        $backto = "tilebutton";
    }
    //Back to Prior Room - Roomid specified
    if( intval($caller)> 0 ){
        $backto = 'feed';
    }

    return $backto;
}

function TopBar( $caller, $owner, $readonly, $profileflag )
{
    global $global_titlebar_color;
    global $icon_braxroom2;
    
    $topbartitle = "Room";
    if($readonly == 'Y'){
        $topbartitle = ucfirst("$_SESSION[sponsorname] Home");
    }
    
    if($profileflag == 'Y'){
        $topbartitle = 'User Profile';
    }
    if($owner == $_SESSION['pid']){
        $topbartitle = 'My Profile and Data';
    }
    
    $backto = BackAction($caller, $readonly);
    
    echo "
        <span class='roomcontent'>
            <div class='gridstdborder' 
                style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                <img class='icon20 $backto' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                    style='' data-providerid='$owner' data-caller='$caller' data-roomid='$caller' />
                &nbsp;
                <span style='opacity:.5'>
                $icon_braxroom2
                </span>
                <span class='pagetitle2a' style='color:white'>$topbartitle</span> 
            </div>
        </span>
        ";
}
function RadioLink($radiostation, $roomOwner_chatid, $roomOwner_chatidquiz )
{
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
                        <div class='divbuttontext setchatsession' data-chatid='$roomOwner_chatidquiz' style='background-color:#3d8da5;color:white'>
                            Go to LIVE QUIZ 
                            <img class='icon15' src='../img/arrowhead-right-white-01-128.png' />
                        </div>  
                      </center><br>";
    }
    return $radiolink;
}
?>
