<?php
session_start();
require_once("validsession.inc.php");
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("room.inc.php");
require_once("internationalization.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("ID",$_SESSION['pid']);
    $shareid = @tvalidator("PURIFY",$_POST['shareid']);
    $scrollreference = @tvalidator("PURIFY",$_POST['scrollreference']);
    $selectedroomid = @tvalidator("PURIFY",$_POST['selectedroomid']);
    $readonly = @tvalidator("PURIFY",$_POST['readonly']);
    
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $limit =  @tvalidator("PURIFY",$_POST['limit']);
    $limitsql = "limit 200";
    if($limit!=''){
        $limitsql = "limit $limit";
    }
    

    $braxmedal = "<img class='icon15' src='$iconsource_braxmedal_common' placeholder='Trusted $appname Resource' style='top:0px;bottom:0px;height:15px' />";
    
    
    if($mode == 'X')
    {
        $result2 = pdo_query("1",
        "
            select distinct statuspost.anonymous, provider.providerid, provider.providername, provider.alias, statusreads.xaccode, 
            statusreads.actiontime as actiontime2, provider.name2, provider.active, provider.medal
            DATE_FORMAT(date_add(statusreads.actiontime, INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), '%m/%d/%y %a %h:%i %p') as actiontime,
            (select 'Y' from publicrooms where publicrooms.roomid = statusreads.roomid ) as public,
            provider.handle,
            roominfo.anonymousflag, blocked1.blockee
            from
            statusreads
            left join provider on provider.providerid = statusreads.providerid
            left join roominfo on roominfo.roomid = statusreads.roomid
            left join statuspost on statuspost.owner = statusreads.providerid and statusreads.shareid = statuspost.shareid 
            left join blocked blocked1 on blocked1.blockee = statuspost.providerid and blocked1.blocker = ?
            where statusreads.shareid = ? and statusreads.xaccode in ('L','C','D','B','P')
            order by actiontime2 desc limit 100
        ",array($providerid,$shareid));
        $activity = "";
        while( $row2 = pdo_fetch($result))
        {
            
            $postername = $row2['providername'];
            if($row2['public']=='Y')
            {
                $postername = $row2['alias'];
                if($postername == '')
                    $postername = $row2['providername'];
            }
            if( $row2['handle']!='' && $row2['name2']!='')
            {
                $postername = $row2['name2'];
            }
            if($row2['anonymous']=='Y')
                $postername = 'Anonymous';
            if($row2['anonymousflag']=='Y')
                $postername = 'Someone';
            
            if( $row2['xaccode']=='C')
                $action = 'commented';
            if( $row2['xaccode']=='D')
                $action = 'deleted post';
            if( $row2['xaccode']=='L')
                $action = 'liked this';
            if( $row2['xaccode']=='B')
                $action = 'bumped it up';
            if( $row2['xaccode']=='P')
                $action = 'posted';
            $activity .=
                "$postername $action on $row2[actiontime]<br>";
            
            
        }
        $activity.="<br>";
        echo $activity;
        
        exit();
    }
    
    $sizing = RoomSizing();

    pdo_query("1","
        delete from statusreads where shareid=? and xaccode='R'
        ",array($shareid));
    
    
    $result2 = pdo_query("1",
        "
            select count(*) as count
            from statuspost
            where parent!='Y' and shareid=?
        ",array($shareid));
    $row2 = pdo_fetch($result);
    $iCount = $row2['count'];
            

        
    $result2 = pdo_query("1",

        "
            select anonymous, encoding, postid, providername, 
            comment, link, photo, album,
            video, videotitle, 
            avatarurl, alias, providerid, name2,
            postdate, postdate2, active, medal,
            shareid, roomid, likes, owner,
            public, handle, private, anonymousflag, blockee, blocker,
            profileroomid, moderator
            
            from 
            (
            

                select statuspost.anonymous, statuspost.encoding, postid, providername, 
                statuspost.comment, statuspost.link, statuspost.photo, statuspost.album,
                statuspost.video, statuspost.videotitle, 
                avatarurl, provider.alias, statuspost.providerid, provider.name2,
                DATE_FORMAT(date_add(statuspost.postdate, INTERVAL $_SESSION[timezoneoffset] HOUR), '%m/%d/%y %h:%i%p') as postdate, 
                statuspost.postdate as postdate2, provider.active, provider.medal,                  
                statuspost.shareid, statuspost.roomid, statuspost.likes, statuspost.owner, 
                (select 'Y' from publicrooms where statuspost.roomid = publicrooms.roomid 
                 ) as public,
                 provider.handle,
                roominfo.private,
                roominfo.anonymousflag, blocked1.blockee, blocked2.blocker,
                provider.profileroomid,
                (select providerid from roommoderator where roommoderator.roomid = statuspost.roomid
                 and roommoderator.providerid = ? ) as moderator

                from statuspost
                left join provider on statuspost.providerid = provider.providerid
                left join roominfo on statuspost.roomid = roominfo.roomid
                left join blocked blocked1 on blocked1.blockee = statuspost.providerid and blocked1.blocker = ?
                left join blocked blocked2 on blocked2.blocker = statuspost.providerid and blocked2.blockee = ?
                where parent!='Y' and shareid=?

                 order by  statuspost.postdate  desc $limitsql
            ) as s2
            order by postdate2 asc
        ",array($providerid,$providerid,$providerid,$shareid));
            
    $i = 0;
    $page = 0;
    //echo "<br>";
    while($row2 = pdo_fetch($result))
    {
        $comment = FormatComment("", $row2['postid'],$row2['owner'], $row2['roomid'], $row2['encoding'], 
                $row2['comment'], '',$row2['photo'], $row2['album'], $row2['video'], $row2['link'], "","N",  
                $sizing->mainwidth, $sizing->statuswidth2, $row2['videotitle'], 0, $page, $readonly, 
                $row2['blockee'], $row2['blocker']  );

        $posterobj = RoomPosterInfo( $row2['roomid'], $row2['owner'], $row2['avatarurl'], $row2['public'], 
                $row2['private'], $row2['anonymous'], $row2['anonymousflag'], $row2['providername'], $row2['name2'], 
                $row2['alias'], $row2['handle'], $row2['blockee'], $row2['blocker'], $row2['medal']  );
        $avatarurl2 = HttpsWrapper($posterobj->avatar);
        $postername = $posterobj->name;
        
        $like2button = LikeButton($providerid, $row2['likes'], $shareid, $row2['postid'], $row2['roomid'], $selectedroomid,"float:right", "N","$scrollreference" );
        $deletebutton = DeleteButton("N", $providerid, $row2['owner'], $row2['moderator'], $row2['providerid'], $row2['shareid'], $row2['postid'], $row2['roomid'], $selectedroomid,"float:right;padding-left:15px;top:5px","$scrollreference" );

        $postdate = InternationalizeDate($row2['postdate']);
        $postdate =
            "                
                <div class=smalltext style='color:gray;display:inline'>$postdate</div>
                <br>
            ";
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
            $post_pid_action = "";
        }
        $usermedal = "";
        if($posterobj->medal == '1'){
            $usermedal = $braxmedal;
        }
        echo "
                $deletebutton
                $like2button
                <div style='display:inline-block'>
                    <div class='circular' style='height:30px;width:30px;overflow:hidden;margin-right:10px'>
                        <img class='avatar1 $post_pid_action' src='$avatarurl2' 
                        style='max-width:100%;min-height:100%;cursor:pointer'
                        data-providerid='$post_pid' data-name='$postername'    
                        data-roomid='$row2[profileroomid]' data-caller='$selectedroomid'
                        data-profile='Y'
                        data-mode ='S' data-passkey64=''
                        />
                    </div>
                </div>
                <div style='display:inline-block;vertical-align:top;padding-left:5px'>
                        <span class='pagetitle3' style='color:black;'>$postername $usermedal</span><br>
                        $postdate
                </div>
                <div id='$row2[postid]' style='display:inline' ></div>
                <br>
               $comment<br><br>
                ";

        
        $i++;
    }
    
?>
