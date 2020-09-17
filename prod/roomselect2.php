<?php
require("room.inc.php");
require_once("crypt-pdo.inc.php");
require_once("lib_autolink.php");



    $providerid = tvalidator("ID",$_POST['providerid']);

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $page = intval(@tvalidator("PURIFY",$_POST['page']));
    $find = stripslashes(htmlentities(@tvalidator("PURIFY",$_POST['find'],ENT_QUOTES)));
    
    $maxperpage = 100;
    $limitstart = $page * $maxperpage;
    $limitend = $maxperpage;
    $previouspage = intval($page) - 1;
    $nextpage = intval($page) + 1;
    $post_pid = '';
    $previous = " 
                    <img class='roomselect tapped icon25'
                      data-mode='' data-page='$previouspage'
                    src='$iconsource_braxarrowup_common' style='padding-left:10px' />
                    ";
    if($page == 0){
        $previous = "";
    }
    $next = "
                    <img class='roomselect tapped icon25'
                    data-mode='' data-page='$nextpage'                    
                    src='$iconsource_braxarrowdown_common' style='padding-left:10px' />
                ";
    
    
    
    SaveLastFunction($providerid,"R", 0);

    
    
    $result = pdo_query("1",
        "
            select roominfo.room, statuspost.parent, roominfo.subscription,
            provider.avatarurl, roominfo.profileflag,
            statuspost.anonymous, statuspost.encoding, statuspost.postid,
            statuspost.pin, statuspost.locked,
            providername, provider.name2, statuspost.comment, statuspost.link, 
            provider.active,
            statuspost.photo, statuspost.album, statuspost.video,  statuspost.videotitle, statuspost.roomid,
            avatarurl, alias, statuspost.providerid, statuspost.articleid,
            DATE_FORMAT(date_add(statuspost.postdate,INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), 
                '%m/%d %h:%i%p') as postdate, 
            (select actiontime from statusreads st2 
                where st2.shareid = statuspost.shareid and
                st2.roomid = statuspost.roomid and
                st2.xaccode  not in ('R','D') 
             order by actiontime desc limit 1 ) as lastpostdate,
            statuspost.shareid, statuspost.roomid, statuspost.likes, 
            statuspost.owner,
            (   select distinct providername from provider
                where providerid = statuspost.owner
            ) as ownername,
            (   select distinct concat(providername,' ',handle) from provider
                where providerid = statusroom.owner
            ) as roomownername,
            (select 'Y' from publicrooms where statuspost.roomid = publicrooms.roomid) as public,
            (select handle from roomhandle where roomhandle.roomid = statuspost.roomid )
                as handle,
            roominfo.anonymousflag, blocked1.blockee, blocked2.blocker,
            provider.profileroomid,
            (select providerid from roommoderator where roommoderator.roomid = statuspost.roomid
             and roommoderator.providerid = statusroom.providerid ) as moderator,
             statuspost.commentcount, statuspost.title
            from statuspost
            left join statusroom on statuspost.roomid = statusroom.roomid
            left join provider on statuspost.providerid = provider.providerid
            left join roominfo on statuspost.roomid = roominfo.roomid
            left join blocked blocked1 on blocked1.blockee = statuspost.providerid and blocked1.blocker = statusroom.providerid
            left join blocked blocked2 on blocked2.blocker = statuspost.providerid and blocked2.blockee = statusroom.providerid
            where 
            statusroom.providerid = ? 
            and            
            (
                roominfo.profileflag!='Y' 
                and
                statusroom.owner not in (select blockee from blocked where blocker = statusroom.providerid and blockee = statusroom.owner)
                or
                roominfo.profileflag='Y' and
                statusroom.owner not in (select blockee from blocked where blocker = statusroom.providerid and blockee = statusroom.owner)
                and
                
                (
                    statusroom.owner  in 
                    (select targetproviderid from contacts where contacts.providerid = statusroom.providerid )
                    or
                    statusroom.owner in 
                    (select providerid from groupmembers where groupid in 
                        (select groupid from groupmembers where providerid = statusroom.providerid)
                    )
                )
            )
            and
            (
                datediff( now(), roominfo.lastactive)  < 2 
            )
            and datediff(now(), statuspost.postdate) < 2
            and statuspost.articleid = 0
            and (roominfo.adminroom !='Y' or roominfo.adminroom is null)
            and roominfo.external!='Y'

            order by lastpostdate desc, shareid asc, postid asc limit $limitstart, $limitend
            


    ",array($providerid));
    $readonly = 'N';
    $page = 0;
    $sizing = RoomSizing();
    $post_pid_action='feed';
    
    $lastroomid = '';
    $lastpostid = '';
    $arrow = "<img class='icon15' src='../img/arrowhead-right-white-01-128.png' style='position:relative;top:3px' />";
    $arrowblack = "<img class='icon15' src='../img/Arrow-Right-in-Circle_120px.png' style='position:relative;top:3px' />";

    
    $postcount = 0;
    while($row = pdo_fetch($result)){
        $postid = $row['postid'];
        $postdate = InternationalizeDate($row['postdate']);
        if($postcount == 0){
            $activeroomcontent = true;

            if($roomdiscovery == 'Y'){
            echo "
                <div class='mainfont roomselect' data-mode='TRENDING' style='float:right;cursor:pointer;margin-right:20px;color:$global_activetextcolor;'>$menu_trending</div>
                ";
            }
            
            echo "
                <div class='mainfont roomselect' data-mode='MYROOMS' style='float:right;cursor:pointer;margin-right:20px;color:$global_activetextcolor;'>$menu_rooms</div>
                ";
            
            echo "
                <br><br>
                $previous
                <div class='pagetitle2' style='text-align:center;margin:auto;color:$global_textcolor'>$menu_whatsnew
                    <span class='pagetitle3' style='color:$global_textcolor'>
                        <input class='roomfeedflag' type='checkbox' $feedforcechecked style='position:relative;top:5px;' /> $menu_hide 
                    </span>
                </div>
                <br>
                <table class='roomcontent gridnoborder' style='background-color:transparent;width:$sizing->mainwidth;padding:0;margin-left:auto;margin-right:auto;margin-top:0;'>
                    ";
            
        }
        
        $postcount++;
        $cleanPostid = str_replace(".","",$row['postid']);
        $comment = FormatComment("3", $row['postid'], $row['owner'], $row['roomid'], 
            $row['encoding'], $row['comment'], $row['title'], $row['photo'], $row['album'],
            $row['video'], $row['link'],"width:$sizing->statuswidth2","Y", 
            $sizing->mainwidth, $sizing->statuswidth2, $row['videotitle'], 
            $row['articleid'], $page, $readonly, $row['blockee'], $row['blocker'] );
        
        $reply = "";
        if($row['parent']!='Y'){
            $reply = "<div style='text-align:left;padding-left:10px;margin-top:5px'><img class='icon15' src='../img/Arrow-Right_120px.png' /></div>";
        }
        $roomtitle = '';
        $roomfooter = "";
        
        if( $lastroomid!=$row['roomid'] ){
            
            if($row['profileflag']==''){
                $roomtitle = "<div class='feed' data-roomid='$row[roomid]' style='cursor:pointer;opacity:0.5;background-color:$global_menu_color;color:white;padding:3px;text-align:center'><b>$row[room] $arrow</b></div>";
            } else {
                $roomtitle = "<div class='feed' data-roomid='$row[roomid]'  style='cursor:pointer;opacity:0.5;background-color:$global_menu_color;color:white;padding:3px;text-align:center'><b>Profile of $row[roomownername] $arrow</b></div>";
            }
            if($lastroomid!=''){
                
                $roomfooter = "
                <tr class='gridnoborder' style='opacity:0.4'>
                    <td class='feed commentline mainfont gridnoborder' 
                        data-roomid='$lastroomid'
                        data-reference='$lastpostid'
                        style='text-align:center;cursor:pointer;padding:5px;opacity:1;
                        background-color:$global_menu_color;color:white;overflow:none;word-wrap:break-word'>          
                        $menu_gotoroom $arrow
                     </td>
                </tr>    
                <tr class='gridnoborder'>
                    <td class='commentline mainfont  gridnoborder' 
                        style='padding:$sizing->padding;width:$sizing->statuswidth;
                        background-color:transparent;overflow:none;word-wrap:break-word'>          
                        <br>
                     </td>
                </tr>     
                ";
            }
        }

        //What about Blocked? Check Later
        
        $avatarurl = HttpsWrapper($row['avatarurl']);
        $postername = $row['providername'];
        if($row['avatarurl'] == '' || $row['anonymous']=='Y'){
            $avatarurl = "$rootserver/img/egg-blue.png";
        }
        if($row['anonymous']=='Y'){
            $avatarurl = "$rootserver/img/egg-blue.png";
            $postername = "Anonymous";
            $post_pid = '';
            $row['profileroomid']='';
        }
        
        
        /*
            $avatarurl = "$rootserver/img/egg-blue.png";
        if($avatarurl == "$rootserver/img/faceless.png"){
            $avatarurl = "$rootserver/img/egg-blue.png";
        }
        if($row['blockee']!='' || $row['blocker']!=''){
            $avatarurl = "$rootserver/img/egg-blue.png";
            $postername = "Unknown";
        }
         * 
         */
        $avatarimg = "<div class='circular' style='height:30px;width:30px;overflow:hidden;margin-right:10px;position:relative;top:0px'>
                <img class='$post_pid_action' src='$avatarurl' 
                style='max-width:100%;min-height:100%;cursor:pointer;'
                data-providerid='$post_pid' data-name='$postername'    
                data-roomid='$row[profileroomid]' data-caller=''
                data-profile='Y'
                data-mode ='S' data-passkey64=''
                />
                </div>";
        
        
        //$avatarimg = "<img class='circular avatar1' src='$avatarurl' style='' />";

        
            
        $likebutton = LikeButton("$providerid", $row['likes'], $row['shareid'], 
                $row['postid'], $row['roomid'], $row['roomid'],"","Y",$cleanPostid );
          
         
        //$deletebutton = DeleteButton("Y","$memberinfo->owner",  "$row[providerid]",$row['moderator'], $providerid, $row['shareid'], 
        //        $row['postid'], $row['roomid'], $roomid,"top:0px",$cleanPostid );

        $style = "";
        if($row['parent'] =='Y'){
            //$style= 'margin-left:30px';
        }
        $commentshow = "
                <br>
                <div style='display:inline-block;$style'>
                        $avatarimg
                </div>
                <div id='$cleanPostid' class='roomothertext smalltext' data-reply='$postername' style='color:gray;display:inline-block;vertical-align:top;'>
                        <span class='pagetitle3' style='color:black;'><b>$postername</b></span><br>
                        $postdate
                </div>
                <br>
                ";
        if((float)$row['subscription']> 0){
            $reply = "";
            $commentshow = "";
            $likebutton = "";
            $comment = "<div style='text-align:left;padding-left:10px;margin-top:5px;'>Premium content not displayed</div>";
        }
        
        if($lastroomid!=$row['roomid']){
            echo "
                $roomfooter
                <tr class='gridnoborder'>
                    <td class='commentline mainfont  gridstdborder' 
                        style='text-align:left;padding:$sizing->padding;width:$sizing->statuswidth;
                        background-color:$global_backgroundreverse;overflow:none;word-wrap:break-word'>     
                        $roomtitle
                        $reply
                        $comment
                        <br>
                        $commentshow
                        <div style='float:right'>$likebutton</div>
                     </td>
                </tr>     
             ";
        } else 
        if((float)($row['subscription'])==0){
        
            echo "
                <tr class='gridnoborder'>
                    <td class='commentline mainfont  gridstdborder' 
                        data-roomid=$row[roomid]
                        style='text-align:left;padding:$sizing->padding;width:$sizing->statuswidth;
                        background-color:$global_backgroundreverse;overflow:none;word-wrap:break-word'>     
                        $comment
                        <br>
                        $commentshow
                        <div style='float:right'>$likebutton</div>
                     </td>
                </tr>     
             ";
            
        }
        $lastroomid = $row['roomid'];
        $lastpostid = $cleanPostid;
        //echo $avatarimg."$comment<br>";
        //echo $likebutton."<br>";

        
    }
    if($postcount > 0){
    
        echo "</table>$next";
    }
?>