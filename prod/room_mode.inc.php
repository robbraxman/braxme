<?php
    
/*
    $help = "<img class='helpinfo' src='../img/help-gray-128.png' 
            style='height:20px;width:auto;position:relative;top:3px;padding-left:10px;padding-right:10px;cursor:pointer' 
            data-help='This shows all the rooms (highlights). <br><br>".
            "You need to go to specific Room to make a new post. This is needed because each Room has its own membership rules.<br><br>".
            "You can be a member of a room or organize your own room. Rooms can be private or public. ".
            "Public rooms are visible on the Internet. Private rooms can only be seen by members.' ".
            "/>";
 * 
 */
    $help = "";

    if( $mode == 'EDIT'){
    
        RoomPostEdit( 
        $providerid, $postid, $comment );
        $mode = "";
        $parent = "Y";
    }
    if( $mode == 'FLAG'){
    
        FlagBlockPost( $providerid, $shareid, $postid, $roomid );
        //exit();
    }
    if( $mode == 'PIN'){
    
        PinPost( $providerid, $shareid, $postid, $roomid );
        //exit();
    }
    if( $mode == 'LOCK'){
    
        LockPost( $providerid, $shareid, $postid, $roomid );
        //exit();
    }
    if( $mode == 'UNPIN'){
    
        UnPinPost( $providerid, $shareid, $postid, $roomid );
        //exit();
    }
    if( $mode == 'F')
    {
        FlagReadPost( $shareid );
        
    }
    if( $mode == 'C'){  //Comment Count
    
    
            $result2 = pdo_query("1",
                "
                    select count(*) as commenttotal from
                    statuspost where parent!='Y' and shareid=?
                ",array($shareid));
            $row2 = pdo_fetch($result2);
            $commentitems = $row2['commenttotal'];
            echo "$commentitems Comments";
            exit();
    }
    
    if( $mode == 'L'){  //Like
    
        RoomPostLike( $providerid, $shareid, $postid, $roomid);
        
        if($parent == 'N') {
            exit();
        }
        //$roomid = $selectedroomid;
        
        
        
    }
    if( $mode == 'P'){
    
        if( $roomid =='' || $roomid == 'All '){
            exit();
        }
        RoomPost( $mode, $providerid, $shareid, $roomid, $title, $comment, $video, $photo, $link, $anonymous,0);
    }
    if( $mode == 'R'){
    
        RoomPost( $mode, $providerid, $shareid, $roomid, $title, $comment, $video, $photo, $link, $anonymous,0);
        if($parent == 'N'){
            exit();
        }
        $roomid = $selectedroomid;
        
    }
    if( $mode == 'B'){
    
        FlagBumpPost( $providerid, $shareid, $postid, $roomid );
    }
    if( $mode == 'D'){
    
        RoomPostDelete( $providerid, $shareid, $postid, $roomid );
        
        if($parent == 'N')
            exit();
        $roomid = $selectedroomid;
        
    }
    
        
?>
