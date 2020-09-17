<?php

    if(intval($page)<0 || $page == ''){
        $page = 0;
    }
    $maxperpage = 1000;
    
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
    
    if($shareid!=''){
        $topbar = '';
        $profile = '';
        $topbarbuttons = '';
    }
    
    echo "$holdingbucket";
    
    echo $topbar;
    
    
    echo $profile;
        
    echo "
        <div style='background-color:transparent;max-width:$_SESSION[innerwidth]px'>";
    
    echo $topbarbuttons;
    
    echo "
        <table class='roomcontent gridnoborder' style='background-color:transparent;width:$sizing->mainwidth;padding:0;margin-left:auto;margin-right:auto;margin-top:0;'>
            ";
    echo $ownerbuttons;
    
    echo $roomtitle;
    
        
    echo "
        <div style='background-color:transparent;max-width:$_SESSION[innerwidth]px'>";
    
    
    echo "
        <table class='roomcontent gridnoborder' style='background-color:transparent;$sizing->mainwidth:80%;padding:0;margin-left:auto;margin-right:auto;margin-top:0;'>
            ";
    
    echo $childlinks;    
    

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
            provider.active,
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
                st2.xaccode ='X' and st2.providerid = ?
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
             and roommoderator.providerid = ? ) as moderator,
            statuspost.commentcount, statuspost.title
            from statuspost
            left join provider on statuspost.providerid = provider.providerid
            left join roominfo on statuspost.roomid = roominfo.roomid
            left join blocked blocked1 on blocked1.blockee = statuspost.providerid and blocked1.blocker = ?
            left join blocked blocked2 on blocked2.blocker = statuspost.providerid and blocked2.blockee = ?

            where statuspost.parent = 'Y' and
            statuspost.roomid  = ?
            and statuspost.title like ?
            and statuspost.shareid like ?
            and '$roominfo->subscriptionpending'!='Y'
            order by  pin desc, lastpostdate  desc  limit $limitstart, $limitend 
    ",array($providerid,$providerid,$providerid,$providerid,$roomid."%".$find."%",$sharid."%"));
    
    
    $postcount = 0;
    while($row = pdo_fetch($result)){
        
        $postcount++;
        $cleanPostid = str_replace(".","",$row['postid']);
        $postdate = InternationalizeDate($row['postdate']);
        
        //$lastpostdate = date_format($row['lastpostdate'],"m/d/Y");
        //$postdate = InternationalizeDate($lastpostdate);
        $lastpostdate = date_create($row['lastpostdate']);
        $postdate = InternationalizeDate(date_format($lastpostdate,"m/d/Y"));
        
        if($shareid == ''){
            $comment = $row['title'];
            if($comment==''){
                $comment = "Untitled";
            }
        } else {
            $comment = FormatComment( "", $row['postid'], $row['owner'], $row['roomid'], 
                $row['encoding'], $row['comment'], $row['title'], $row['photo'], $row['album'],
                $row['video'], $row['link'],"width:$sizing->statuswidth2","Y", 
                $sizing->mainwidth, $sizing->statuswidth2, $row['videotitle'], 
                $row['articleid'], $page, $readonly, $row['blockee'], $row['blocker'] );
            
        }
        
        
        $posterobj = RoomPosterInfo($row['roomid'], $row['owner'], $row['avatarurl'], $memberinfo->adminroom, $memberinfo->private,
                $row['anonymous'], $row['anonymousflag'], $row['providername'], $row['name2'], 
                $row['alias'], $row['handle'], $row['blockee'], $row['blocker'] );
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
            /*
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
             * 
             */
        }
        
        if($shareid == ''){
        
            echo DisplayForumRoomPost( 

                $readonly, intval($row['locked']),
                $providerid, $row['owner'], $row['roomid'], $row['profileroomid'], $row['shareid'], $row['postid'], 
                $memberinfo, $postdate, $row['handle'],
                $row['active'], $posterobj, $sizing, $avatarurl, $comment,
                $anonymous_settings, $anonymous_opacity,
                $deletebutton, $likebutton, $row['commentcount'] 

                );
        } else {
            
            echo DisplayStdRoomPost( 

                $readonly, intval($row['locked']),
                $providerid, $row['owner'], $row['roomid'], $row['profileroomid'], $row['shareid'], $row['postid'], 
                $memberinfo, $postdate, $row['handle'],
                $row['active'], $posterobj, $sizing, $avatarurl, $comment,
                $anonymous_settings, $anonymous_opacity,
                $deletebutton, $likebutton, $row['commentcount'] 
                );
            
        }

        
        
    }
    
    $timeend = microtime();   
    $timeelapsed = $timeend - $timestart;
    echo "</table></div><br><br><br>";
    if($postcount >= $limitend -1) {
        //       $sizing->mainwidth $_SESSION[sizing] $_SESSION[innerwidth] $sizing->padding
        echo "<center><span class=roomcontent>$next</span></center>
             <br>";
    }
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
    
    

?>
