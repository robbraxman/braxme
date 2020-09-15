<?php
require_once("config.php");
require_once("crypt.inc.php");
require_once("notify.inc.php");
require_once("chat.inc.php");

    function CheckValidHandle($handle, $roomid)
    {
        $dup = false;
        $result = do_mysqli_query("1","
            select * from roomhandle where roomid != $roomid and handle='$handle'
        ");
        while($row = do_mysqli_fetch("1",$result)){
            $dup = true;
        }
        return $dup;
    }

    function DeleteRoom( $roomid )
    {
    
        do_mysqli_query("1","delete from statusroom where roomid = $roomid");
        do_mysqli_query("1","delete from roomhandle where roomid = $roomid");
        do_mysqli_query("1","delete from statuspost where roomid = $roomid");
        do_mysqli_query("1","delete from statusreads where roomid = $roomid");
        do_mysqli_query("1","delete from roominfo where roomid = $roomid");
        do_mysqli_query("1","delete from roommderator where roomid = $roomid");
        return;
    }
    

    
    function DeleteMember( $roomid, $providerid, $friendproviderid )
    {
        //Delete from statusroom if member\ is inactive
        $result = do_mysqli_query("1","
            delete from statusroom 
            where roomid=$roomid and 
            owner=$providerid and
            providerid not in (select providerid from provider where
            statusroom.providerid = provider.providerid and active='Y')
            ");

        $result = do_mysqli_query("1","
            select count(*) as total from statusroom where roomid=$roomid  
            ");
        $row = do_mysqli_fetch("1",$result);
        $membercount = $row['total'];
        
        if($membercount > 1){
        
            //delete user but not owner
            do_mysqli_query("1","
                delete from statusroom 
                where roomid=$roomid and 
                providerid = $friendproviderid and providerid!=owner
                and 
                ( owner = $providerid 
                  or
                  roomid in
                    (select roomid from roommoderator r2 where r2.roomid=$roomid and r2.providerid =$providerid)
                  or
                  providerid = $providerid
                )
                ");
        } else {
            
            //delete user even of owner
            do_mysqli_query("1","
                delete from statusroom where roomid=$roomid and 
                providerid = $friendproviderid 
                and 
                ( owner = $providerid 
                  or
                  roomid in
                    (select roomid from roommoderator r2 where roomid=$roomid and providerid =$providerid)
                )
                ");
        }
        
        $result = do_mysqli_query("1","
            select count(*) as total from statusroom where roomid=$roomid  
            ");
        $row = do_mysqli_fetch("1",$result);
        $membercount = $row['total'];
        
        
        if( $membercount == 0) //no more users so delete room and its contents
        {
            do_mysqli_query("1","
                delete from statusroom where roomid=$roomid and owner=$providerid 
                ");
            do_mysqli_query("1","
                delete from roominfo where roomid=$roomid  
                ");
            do_mysqli_query("1","
                delete from roomhandle where roomid=$roomid  
                ");
            $result = do_mysqli_query("1","
                delete from statuspost where roomid=$roomid and providerid=$providerid 
                ");
            $result = do_mysqli_query("1","
                delete from statusreads where roomid=$roomid and providerid=$providerid 
                ");
            $result = do_mysqli_query("1","
                delete from credentials where roomid=$roomid
                ");
            $result = do_mysqli_query("1","
                delete from credentialrequest where roomid=$roomid 
                ");
            $result = do_mysqli_query("1","
                delete from events where roomid=$roomid 
                ");
            $result = do_mysqli_query("1","
                delete from tasks where roomid=$roomid
                ");
            $result = do_mysqli_query("1","
                delete from tasksaction where roomid=$roomid 
                ");
            $result = do_mysqli_query("1","
                delete from roomwebstyle where roomid=$roomid 
                ");
            $result = do_mysqli_query("1","
                delete from roomfiles where roomid=$roomid 
                ");
            $result = do_mysqli_query("1","
                delete from roomfilefolders where roomid=$roomid 
                ");
            $result = do_mysqli_query("1","
                delete from roominvite where roomid=$roomid 
                ");
            $result = do_mysqli_query("1","
                delete from roommoderator where roomid=$roomid 
                ");
            
        }
        DeletefromGroup($providerid, $friendproviderid, $roomid );
        DeleteFromChat($providerid, $friendproviderid, $roomid );
        
        //CHANGE THIS TO BE ANONYMOUS
        //
        //Delete old posts of this user
        /*
        $result = do_mysqli_query("1","
            select providerid from statusroom where roomid=$roomid and providerid=$friendproviderid
            ");
        if(!$row = do_mysqli_fetch("1",$result)){
        
            $result = do_mysqli_query("1","
                delete from statuspost where roomid=$roomid and providerid=$friendproviderid 
                ");
            $result = do_mysqli_query("1","
                delete from statusreads where roomid=$roomid and providerid=$friendproviderid 
                ");
            
        }
         * 
         */
        return;
    
    }

    function AddMember($providerid2, $friendproviderid, $roomid )
    {
        if($roomid <= 0){
            return false;
        }
        
    
        $result = do_mysqli_query("1","
            select owner from statusroom 
            where statusroom.roomid = $roomid and statusroom.owner = statusroom.providerid
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            $owner = $row['owner'];
            
            $providerid = $owner;
            
            $result = do_mysqli_query("1","
                select * from blocked where blocker = $owner and blockee = $friendproviderid "
                );
            if( $row = do_mysqli_fetch("1",$result)){
                return false;
            }        
            
            $result = do_mysqli_query("1","
                select * from blocked where blocker = $friendproviderid and blockee = $owner "
                );
            if( $row = do_mysqli_fetch("1",$result)){
                return false;
            }        
            
        
            
            //Check If already a member
            $result = do_mysqli_query("1","
                select providerid from statusroom 
                where statusroom.roomid = $roomid and 
                statusroom.providerid = $friendproviderid
                ");
            //if not a member
            if( !$row = do_mysqli_fetch("1",$result)){
            
                do_mysqli_query("1","
                    insert into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                    ( $roomid,$owner, $friendproviderid,'Y',now(),$providerid )
                    ");

                AddtoGroup($providerid, $friendproviderid, $roomid );
                AddtoChat($providerid, $friendproviderid, $roomid );

                AddFormRequest($providerid, $friendproviderid, $roomid );
                
                RoomAutoChat($roomid, $friendproviderid );
                
            }
            AddMemberRoomChain($providerid, $friendproviderid, $roomid );
            AddMemberCommunityLink($providerid, $friendproviderid, $roomid );
            
        }
        
        //SaveHandle($roomid, $handle, $room, $roomdescription, $discover, $tags, $minage );
        return true;
        
        
    }

    function AddMemberRoomChain($providerid, $friendproviderid, $parentroomid )
    {
        //Get Room Handle
        $result = do_mysqli_query("1","
            select handle from roomhandle where roomid = $parentroomid
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            $handle = $row['handle'];

            //Check for Chain
            $result = do_mysqli_query("1","
                select roomid from roominfo where parentroom = '$handle' and roomid in 
                (select roomid from statusroom where owner = providerid and providerid = $providerid and statusroom.roomid = roominfo.roomid )
                ");
            while( $row = do_mysqli_fetch("1",$result)){
                
                $roomid = $row['roomid'];

                $result2 = do_mysqli_query("1","
                    select owner from statusroom 
                    where statusroom.roomid = $roomid and statusroom.owner = statusroom.providerid
                    ");
                if( $row2 = do_mysqli_fetch("1",$result2)){
                    $owner = $row2['owner'];

                    do_mysqli_query("1","
                        insert into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                        ( $roomid,$owner, $friendproviderid,'Y',now(),$providerid )
                        ");

                }
                AddtoGroup($owner, $friendproviderid, $roomid );
                AddtoChat($owner, $friendproviderid, $roomid );
                //Add Second Level Membership
                AddMemberRoomChain2($providerid, $friendproviderid, $roomid );

            }
        }
        return true;
        
        
    }
    
    function AddMemberRoomChain2($providerid, $friendproviderid, $parentroomid )
    {
        //Get Room Handle
        $result = do_mysqli_query("1","
            select handle from roomhandle where roomid = $parentroomid
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            $handle = $row['handle'];

            //Check for Chain
            $result = do_mysqli_query("1","
                select roomid from roominfo where parentroom = '$handle' and roomid in 
                (select roomid from statusroom where owner = providerid and providerid = $providerid and statusroom.roomid = roominfo.roomid )
                ");
            while( $row = do_mysqli_fetch("1",$result)){
                
                $roomid = $row['roomid'];

                $result2 = do_mysqli_query("1","
                    select owner from statusroom 
                    where statusroom.roomid = $roomid and statusroom.owner = statusroom.providerid
                    ");
                if( $row2 = do_mysqli_fetch("1",$result2)){
                    $owner = $row2['owner'];

                    do_mysqli_query("1","
                        insert into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                        ( $roomid,$owner, $friendproviderid,'Y',now(),$providerid )
                        ");

                }
                AddtoChat($owner, $friendproviderid, $roomid );

            }
        }
        return true;
        
        
    }
    
    function AddMemberCommunityLink($providerid, $friendproviderid, $parentroomid )
    {
        //Get Room Handle
        $result = do_mysqli_query("1","
            select handle from roomhandle where roomid = $parentroomid
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            $handle = $row['handle'];

            //Check for Community Link Chain
            $result = do_mysqli_query("1","
                select roomid from roominfo where communitylink = '$handle'
                ");
            while( $row = do_mysqli_fetch("1",$result)){
                
                $roomid = $row['roomid'];

                $result2 = do_mysqli_query("1","
                    select owner from statusroom 
                    where statusroom.roomid = $roomid and statusroom.owner = statusroom.providerid
                    ");
                if( $row2 = do_mysqli_fetch("1",$result2)){
                    $owner = $row2['owner'];

                    do_mysqli_query("1","
                        insert into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                        ( $roomid,$owner, $friendproviderid,'Y',now(),$providerid )
                        ");

                }
                AddtoGroup($owner, $friendproviderid, $roomid );
                AddtoChat($owner, $friendproviderid, $roomid );
                //Add Second Level Membership
                AddMemberRoomChain2($providerid, $friendproviderid, $roomid );

            }
        }
        return true;
        
        
    }    
    
    function RoomAutoChat($roomid, $providerid )
    {
        //Check if AUTOCHATUSER is populated for Sponsor
        $result = do_mysqli_query("1"," 
                select providername, providerid, handle 
                from provider where handle in 
                (select autochatuser from roominfo where roomid=$roomid and (autochatuser!='' and autochatuser is not null)  and external='Y' )
                and active='Y'
                ");
        if(!$row = do_mysqli_fetch("1",$result)){
            return;
        }
        $autochatuserid = $row['providerid'];
        $autochatuserhandle = $row['handle'];
        
        
        $welcome = 'Welcome!';
        $result = do_mysqli_query("1"," 
                select autochatmsg from roominfo where roomid=$roomid and (autochatmsg is not null and autochatmsg!='')
                ");
        if($row = do_mysqli_fetch("1",$result)){
            $welcome = $row['autochatmsg'];
        }
        
        
        $chatid = EstablishChatSession( $autochatuserid, $autochatuserhandle, "", "Welcome!", "", 0, 0, "" );
        if($chatid == false){
            return;
        }
        
        do_mysqli_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastmessage, lastread, lastactive, techsupport, mute, broadcaster)
                values
                ( $chatid, $autochatuserid, 'Y', now(), now(), now(), null, null, null )
            ");
        
        do_mysqli_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastmessage, lastread, lastactive, techsupport, mute, broadcaster)
                values
                ( $chatid, $providerid,     'Y', now(), now(), now(), null, null, null )
            ");

        $message = $welcome;
        $encode = EncryptChat ($message,"$chatid","" );
        $encodeshort = EncryptChat ("You have a message.","$chatid","" );

        $result = do_mysqli_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                values
                ( $chatid, $autochatuserid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
            ");
        $result = do_mysqli_query("1",
            "
            update chatmembers set lastmessage=now(), lastread=now() where providerid= $autochatuserid and chatid=$chatid and status='Y'
            ");
        $result = do_mysqli_query("1",
            "
            update chatmaster set lastmessage=now() where chatid=$chatid 
            ");
        
        //Mark access for new users so notifications are received
        $result = do_mysqli_query("1"," 
                update provider set lastaccess=now() where providerid = $providerid
                ");
        
        ChatNotification($autochatuserid, $chatid, $encodeshort, $_SESSION['responseencoding'], "" );
        //echo "
        //ChatNotification($autochatuserid, $chatid, $encodeshort, $_SESSION[responseencoding], '' );
        //    ";
            
        
    }

    
    function AddtoChat($providerid, $friendproviderid, $roomid )
    {
        if(intval($roomid) <= 0){
            return false;
        }
    
        $result = do_mysqli_query("1","
            select chatspawned.chatid 
            from chatspawned 
            left join chatmaster on chatspawned.chatid = chatmaster.chatid
            where chatspawned.roomid = $roomid
            and chatmaster.lastmessage is not null
            and chatmaster.status='Y'
            ");
        while( $row = do_mysqli_fetch("1",$result)){
            
            do_mysqli_query("1","
                insert into chatmembers 
                ( chatid, providerid, status, lastactive, lastmessage, lastread, techsupport, mute )
                values
                ( $row[chatid], $friendproviderid,'Y', 0, 0, '20000101', 'N','')
                ");
        }
        
        return true;
        
        
    }
    function AddFormRequest($providerid, $friendproviderid, $roomid )
    {
        if(intval($roomid) <= 0){
            return false;
        }
    
        $result = do_mysqli_query("1","
            select formid from roomforms where roomid = $roomid
            ");
        while( $row = do_mysqli_fetch("1",$result)){
            
            do_mysqli_query("1"," 
            update credentialformrequest set status='Y' where providerid = $friendproviderid and formid=$row[formid] and status='N' 
                ");
            
            do_mysqli_query("1","
                insert into credentialformtrigger
                (providerid, formid, created, status ) values 
                ($friendproviderid,  $row[formid], now(),'N')
                ");
            do_mysqli_query("1","
                insert into credentialformrequest
                (providerid, requestor, formid, created, status ) values 
                ($friendproviderid, $providerid, $row[formid], now(),'N')
                ");
            
        }
        
        return true;
        
        
    }
    
    function DeleteFromChat($providerid, $friendproviderid, $roomid )
    {
        if(intval($roomid) <= 0){
            return false;
        }
    
        $result = do_mysqli_query("1","
            select chatspawned.chatid 
            from chatspawned 
            left join chatmaster on chatspawned.chatid = chatmaster.chatid
            where chatspawned.roomid = $roomid
            and chatmaster.lastmessage is not null
            and chatmaster.status='Y'
            ");
        while( $row = do_mysqli_fetch("1",$result)){
            
            do_mysqli_query("1","
                delete from chatmembers 
                where chatid=$row[chatid] and providerid =$friendproviderid
                ");
        }
        
        return true;
        
        
    }
    
    function AddtoGroup($providerid, $friendproviderid, $roomid )
    {
        if(intval($roomid) <= 0){
            return false;
        }
        
        
    
        //LogDebug($providerid,"$providerid $friendproviderid $roomid");
        $result = do_mysqli_query("1","
            select groupid from groups where creator = $providerid and roomid = $roomid
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            //LogDebug($providerid,"$providerid $row[groupid] $friendproviderid $roomid");
            do_mysqli_query("1","
                insert into groupmembers ( groupid, providerid, createdate ) 
                select $row[groupid], $friendproviderid, now()
                ");
        }
        
        return true;
        
        
    }
    function DeletefromGroup($providerid, $friendproviderid, $roomid )
    {
        if(intval($roomid) <= 0){
            return false;
        }
    
        $result = do_mysqli_query("1","
            select groupid from groups where roomid = $roomid
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            
            do_mysqli_query("1","
                delete from groupmembers where groupid = $row[groupid] and providerid = $friendproviderid 
                ");
        }
        
        return true;
        
        
    }
    
    function CopyMembersToRoom($providerid, $sourceroomid, $roomid )
    {
    
        $result = do_mysqli_query("1","
            select owner, statusroom.providerid, roominfo.room from statusroom 
            left join roominfo on statusroom.roomid = roominfo.roomid
            where statusroom.roomid = $sourceroomid 
            ");
        
        while( $row = do_mysqli_fetch("1",$result)){
            $owner = $row['owner'];
            $room =stripslashes($row['room']);
            $roomForSql = tvalidator("PURIFY",stripslashes($room));
            
            do_mysqli_query("1","
                insert ignore into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                ( $roomid,$providerid, $row[providerid],'Y',now(),$row[owner] )
                ");
            AddtoChat($providerid, $row[providerid], $roomid );
            
        }
        
        
        //SaveHandle($roomid, $handle, $room, $roomdescription, $discover, $tags, $minage );
        return true;
        
        
    }


    function MakeModerator( $roomid, $friendproviderid )
    {
    
            $result = do_mysqli_query("1","
                select providerid from roommoderator where providerid=$friendproviderid and roomid=$roomid 
            ");
            if($row = do_mysqli_fetch("1",$result)){
                $result = do_mysqli_query("1","
                    delete from roommoderator where providerid=$friendproviderid and roomid=$roomid
                ");
                
            } else {
        
                $result = do_mysqli_query("1","
                    insert into roommoderator (providerid, roomid) values ( $friendproviderid, $roomid )
                ");
            }
            return;
        
    }
    
    
    function RoomEdit($mode, $roomid, $roomHtml,$roomdesc, $organization, 
            $photourl, $photourl2, $roommode,
            $private, $discover, $category,
            $roomanonymous, $contactexchange,$adminonly, $notifications, $showmembers, $soundalert,
            $roomhandle, $minage, $tags, $sharephotoflag, $rsscategory, $groupid, $rsssource, $rsssourcefailreason,
            $caller, $radiostation, $sponsor, $parent, $childsort, $profileflag, $roomexternal, 
            $roominvitehandle, $webcolorscheme, $webtextcolor, $webpublishprofile, $webflags, $searchengine,
            $analytics, $subscriptiondays, $subscription, $subscriptionusd, $wallpaper, 
            $autochatuser, $autochatmsg, $community, $communitylink, $store, $wizardenterprise, $roomstyle
            )
    {
        global $appname;
        global $providerid;
        global $global_textcolor;
        global $global_background;
        global $global_titlebar_color;
        global $enterpriseapp;
        
        if($_SESSION['store']=='N' && $store == 'Y'){
            $store = 'N';
        }
        $wizardreadonly = '';
        $wizardmessage = '';
        if($wizardenterprise!='' && $roomexternal == 'Y'){
            $wizardreadonly = 'readonly=readonly';
            $wizardmessage = "
                This $enterpriseapp website was set up automatically by a Wizard. If you want
                to modify the hashtag, please delete the current $enterpriseapp domain and run the wizard again.
                ";
        }
        
        
        $autochatmsg = br2nl($autochatmsg);
        /*
        //$autochatmsg = preg_replace('#<br\s   >#i', "", $autochatmsg);        
        */
        
        $displayOnEditOnly = "";
        if($mode == 'N'){
            $displayOnEditOnly = "display:none;";
        }
        $displayForAdminOnly = "";
        if($_SESSION['superadmin']!= 'Y'){
            $displayForAdminOnly = "display:none;";
        }
        $displayForEnterpriseOnly = "";
        if($_SESSION['web']!= 'Y' && $_SESSION['superadmin']!='Y'){
            $displayForEnterpriseOnly = "display:none;";
        }

        if($mode == 'N'){
            $result = do_mysqli_query("1","select sponsor, roomhashtag from sponsor where creator=$providerid ");
            if($row = do_mysqli_fetch("1",$result)){
                $parent = $row['roomhashtag'];
            }
        }
        
        if($webtextcolor == ''){
            $webtextcolor = 'white';
        }
        
        if($private == 'Y' || $private == ''){
        
            $privatetext = "
                        Private <input id='private1' name='private' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Open <input id='private2' name='private' type='radio'  value='N' style='position:relative;top:5px'>
                        ";
            
        } else {
        
            $privatetext = "
                        Private <input id='private1' name='private' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Open <input id='private2' name='private' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        ";
        }        
        if($discover == 'Y'){
        
            $discovertext = "
                        Yes <input id='discover1' name='discover' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='discover2' name='discover' type='radio'  value='N' style='position:relative;top:5px'>
                        ";
            
        } else {
            
            $discovertext = "
                        Yes <input id='discover1' name='discover' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='discover2' name='discover' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        ";
        }
        if($roomanonymous == 'Y')
        {
            $roomanonymoustext = "
                        All Anonymous <input id='roomanonymous1' name='roomanonymous' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        All Open <input id='roomanonymous2' name='roomanonymous' type='radio'  value='N' style='position:relative;top:5px'>
                        <br>
                        Either <input id='roomanonymous3' name='roomanonymous' type='radio'  value='A' style='position:relative;top:5px'>
                        ";
            
        } else
        if($roomanonymous == 'N'){
        
            $roomanonymoustext = "
                        Anonymous Only <input id='roomanonymous1' name='roomanonymous' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Identified Only <input id='roomanonymous2' name='roomanonymous' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        <br>
                        Either <input id='roomanonymous3' name='roomanonymous' type='radio'  value='A' style='position:relative;top:5px'>
                        ";
        } else {
            
            $roomanonymous == 'A';
            $roomanonymoustext = "
                        Anonymous Only <input id='roomanonymous1' name='roomanonymous' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Identified Only <input id='roomanonymous2' name='roomanonymous' type='radio'  value='N' style='position:relative;top:5px'>
                        <br>
                        Either <input id='roomanonymous3' name='roomanonymous' type='radio'  checked=checked value='A' style='position:relative;top:5px'>
                        ";
        }
        /*
        $contactexchangetext = "
                    <input id='contactexchange2' name='contactexchange' type='hidden' checked=checked  value='N' style='position:relative;top:5px'>
                    ";
         * 
         */
        if($adminonly == 'Y' ){
        
            $adminonlytext = "
                        All Members <input id='adminonly1' name='adminonly' type='radio' value='N' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Owner Only <input id='adminonly2' name='adminonly' type='radio' checked=checked  value='Y' style='position:relative;top:5px'>
                        ";
            
        } else {
            
            $adminonlytext = "
                        All Members <input id='adminonly1' name='adminonly' type='radio' value='N' checked=checked style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Owner Only <input id='adminonly2' name='adminonly' type='radio'  value='Y' style='position:relative;top:5px'>
                        ";
        }        

        $storetext = '';
        if( $_SESSION['store']=='Y' ){
            if($store == 'Y'  ){

                $storetext = "
                            <br><br>

                            <br>
                            Online Store<br>
                    
                            Show <input id='roomstore1' name='roomstore' type='radio' value='Y' checked=checked  style='position:relative;top:5px'>
                            &nbsp;&nbsp;
                            Hide <input id='roomstore2' name='roomstore' type='radio'  value='N' style='position:relative;top:5px'>
                            ";

            } else {

                $storetext = "
                            <br><br>

                            <br>
                            Online Store<br>
                    
                            Show <input id='roomstore1' name='roomstore' type='radio' value='Y'  style='position:relative;top:5px'>
                            &nbsp;&nbsp;
                            Hide  <input id='roomstore2' name='roomstore' type='radio'  value='N' checked=checked style='position:relative;top:5px'>
                            ";
            }        
        } else {
            
        }

        
        if($notifications == 'Y' || $notifications == "" )
        {
            $notificationstext = "
                        Yes <input id='notifications1' name='notifications' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='notifications2' name='notifications' type='radio'  value='N' style='position:relative;top:5px'>
                        ";
            
        } else {
            
            $notificationstext = "
                        Yes <input id='notifications1' name='notifications' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='notifications2' name='notifications' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        ";
        }        
        
        if($showmembers == 'Y' ){
        
            $showmemberstext = "
                        Yes <input id='showmembers1' name='showmembers' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='showmembers2' name='showmembers' type='radio'  value='N' style='position:relative;top:5px'>
                        ";
            
        }
        else
        {
            $showmemberstext = "
                        Yes <input id='showmembers1' name='showmembers' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='showmembers2' name='showmembers' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        ";
        }        
        
        if($searchengine == 'Y' ){
        
            $searchenginetext = "
                        Yes <input id='searchengine1' name='searchengine' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='searchengine2' name='searchengine' type='radio'  value='N' style='position:relative;top:5px'>
                        ";
            
        }
        else
        {
            $searchenginetext = "
                        Yes <input id='searchengine1' name='searchengine' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input id='searchengine2' name='searchengine' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        ";
        }        
        
        
        
        if($roomexternal == 'Y' ){
        
            $roomexternaltext = "
                        Yes <input class='showhidden hidehidden2' id='roomexternal1' name='roomexternal' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input class='showhidden2 hidehidden'  id='roomexternal2' name='roomexternal' type='radio'  value='N' style='position:relative;top:5px'>
                        ";
            $showweb = '';            
            $showweb2 = 'display:none';            
        }
        else
        {
            $roomexternaltext = "
                        Yes <input class='showhidden hidehidden2'  id='roomexternal1' name='roomexternal' type='radio' value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No <input class='showhidden2 hidehidden' id='roomexternal2' name='roomexternal' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        ";
            $showweb = 'display:none';            
            $showweb2 = '';            
        }         
        
        if($webpublishprofile == 'Y' ){
        
            $webpublishprofiletext = "
                        Yes <input id='webpublishprofile1' name='webpublishprofile' type='radio' checked=checked value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No - Use Domain Logo <input id='webpublishprofile2' name='webpublishprofile' type='radio'  value='N' style='position:relative;top:5px'>
                        ";
            
        }
        else
        {
            $webpublishprofiletext = "
                        Yes <input id='webpublishprofile1' name='webpublishprofile' type='radio'  value='Y' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        No - Use Domain Logo  <input id='webpublishprofile2' name='webpublishprofile' type='radio'  checked=checked value='N' style='position:relative;top:5px'>
                        ";
        }        
        
        
        
        if($soundalert != '1' ){
        
            $soundalerttext = "
                        Standard <input id='soundalert1' name='soundalert' type='radio' checked=checked value='0' style='position:relative;top:5px'>
                        <br>
                        High/Amber Alert <input id='soundalert2' name='soundalert' type='radio'  value='1' style='position:relative;top:5px'>
                        ";
            
        } else {
        
            $soundalerttext = "
                        Standard   <input id='soundalert1' name='soundalert' type='radio' value='0' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        High Alert <input id='soundalert2' name='soundalert' type='radio' checked=checked  value='1' style='position:relative;top:5px'>
                        ";
        }        

        /*
        if($sharephotoflag != '1' )
        {
            $sharephotoflagtext = "
                        Post Photo <input id='sharephotoflag1' name='sharephotoflag' type='radio' checked=checked value='0' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Header Photo <input id='sharephotoflag2' name='sharephotoflag' type='radio'  value='1' style='position:relative;top:5px'>
                        ";
            
        }
        else
        {
            $sharephotoflagtext = "
                        Post Photo <input id='sharephotoflag1' name='sharephotoflag' type='radio' value='0' style='position:relative;top:5px'>
                        &nbsp;&nbsp;
                        Header Photo <input id='sharephotoflag2' name='sharephotoflag' type='radio' checked=checked  value='1' style='position:relative;top:5px'>
                        ";
        }        
        */
        $selectgroup = "";
        if($groupid!=''){
            $result = do_mysqli_query("1","
                select groups.groupname, groups.groupid 
                from groups where groupid = $groupid
                    ");
            $selectgroupoptions = "<option value=''>No Community Limitation</option>";
            while($row = do_mysqli_fetch("1",$result)){
                $selectgroup .= "<option value='$row[groupid]'>$row[groupname]</option>";
            }
        }
        $result = do_mysqli_query("1","
            select groups.groupname, groups.groupid 
            from groups
            left join groupmembers on groups.groupid = groupmembers.groupid
            where groupmembers.providerid = $providerid
                ");
        $selectgroupoptions = "<option value=''>- No Group Limitation -</option>";
        while($row = do_mysqli_fetch("1",$result)){
            $selectgroupoptions .= "<option value='$row[groupid]'>$row[groupname]</option>";
        }
        
        $selectcommunity = "";
        if($communitylink!=''){
            $result = do_mysqli_query("1","
                select roomhandle.handle from roomhandle where community='Y' and roomhandle='$communitylink'
                    ");
            $selectgroupoptions = "<option value=''>No Community Link</option>";
            while($row = do_mysqli_fetch("1",$result)){
                $selectcommunity .= "<option value='$row[handle]'>$row[handle]</option>";
            }
        }
        $result = do_mysqli_query("1","
                select roomhandle.handle from roomhandle where community='Y' 
                ");
        $selectcommunityoptions = "<option value=''>- No Community Link -</option>";
        while($row = do_mysqli_fetch("1",$result)){
            $selectcommunityoptions .= "<option value='$row[handle]'>$row[handle]</option>";
        }
        
        
        $webstyle = '';
        
        if($category!=''){
        
            $selectcategory = "<option value='$category' selected=selected>$category</option>";
        } else {
        
            $selectcategory = "<option value='Private' selected=selected>Private</option>";
        }
        
        
        if($roomstyle!='forum'){
        
            $selectroomstyle = "<option value='std' selected=selected>Standard</option>";
        } else {
        
            $selectroomstyle = "<option value='forum' selected=selected>Forum</option>";
        }
        
            
        $rsscategorytext = "";
        $rsslinktext = "";
        $sponsortext = "";
        $broadcasttext = "";
        $parenttext = "
                    Parent Room #Hashtag<br>
                    <input id='roomparent' name='roomparent' placeholder='#ParentRoom' type='text' size=20 maxlength=250 value='$parent' style='max-width:400px;width:70%'>
                        <br>
                        <span class='smalltext'>Take members from this Room</span>
                    <br><br>
                    Sort Order - If Child Room<br>
                    <input id='roomchildsort' name='roomchildsort' placeholder='Child Sort #' type='number' size=20 maxlength=250 value='$childsort' style='max-width:400px;width:70%'>
                    <br><br>
                        ";
        if($_SESSION['superadmin'] == 'Y' || $_SESSION['enterprise']=='Y'){
            $rsscategorytext = "
                        <br>
                        RSS Category<br>
                        <input id='rsscategory' name='rsscategory' placeholder='RSS Categories' type='text' size=20 maxlength=250 value='$rsscategory' style='max-width:400px;width:70%'>
                        <br><br>
                        ";
            $rsslinktext = "
                        RSS Feed Link<br>
                        <input id='rsssource' name='rsssource' placeholder='RSS Feed Link' type='text' size=20 maxlength=250 value='$rsssource' style='max-width:400px;width:70%'>
                        <br><span class='smalltext' style='color:firebrick'>$rsssourcefailreason</span><br>
                        <br>
                        ";
            
            $sponsortext = "
                        <br>
                        Enterprise Sponsor Code to Assign to Members<br>
                        <input id='roomsponsor' name='roomsponsor' placeholder='Sponsor' type='text' size=20 maxlength=250 $wizardreadonly value='$sponsor' style='max-width:400px;width:70%'>
                        <br><br>
                            ";
        } else {
            $rsscategorytext = "
                        <input id='rsscategory' name='rsscategory' placeholder='RSS Categories' type='hidden' size=20 maxlength=250 value='$rsscategory' style='max-width:400px;width:70%'>
                        <br><br>
                        RSS Feed Link<br>
                        <input id='rsssource' name='rsssource' placeholder='RSS Feed Link (XML)' type='text' size=20 maxlength=250 value='$rsssource' style='max-width:400px;width:70%'>
                        <br>
                        <span class='smalltext2' style='color:gray'>Advanced feature - this will auto-post content into the room from the RSS Feed</span>
                        <br><span class='smalltext' style='color:firebrick'>$rsssourcefailreason</span><br>
                        ";
            $sponsortext = "
                        <input id='roomsponsor' name='roomsponsor' placeholder='Sponsor' type='hidden' size=20 maxlength=250 value='$sponsor' style='max-width:400px;width:70%'>
                            ";
        }    

        $copymemberstext = "";
        
        if($_SESSION['superadmin'] == 'Y'){
            $broadcasttext = "
                        Broadcast Channel/Quiz<br>
                        <input id='radiostation' name='radiostation' placeholder='Y/Q/' type='text' size=20 maxlength=250 value='$radiostation' style='max-width:400px;width:70%'>
                        <br><br>    
                        Community Holder (Y/N)<br>
                        <input id='community' name='community' placeholder='' type='text' size=20 maxlength=1 value='$community' style='max-width:400px;width:70%'>
                        <br><br>
                            ";
            
            $copymemberstext = "
                        Copy Members From<br>
                        <input id='copymembers' name='copymembers' placeholder='Handle' type='text' size=20 maxlength=250 value='' style='max-width:400px;width:70%'>
                        <br><br>
                            ";
            
        } else {
            $broadcasttext = "
                        <input id='radiostation' name='radiostation' placeholder='Y/N' type='hidden' size=20 maxlength=250 value='$radiostation' style='max-width:400px;width:70%'>
                            ";
            $copymemberstext = "
                        Copy Members From<br>
                        <input id='copymembers' name='copymembers' placeholder='Handle' type='text' size=20 maxlength=250 value='' style='max-width:400px;width:70%'>
                        <br><br>
                            ";
        }    
        $broadcasttext .= "
                    <input id='profileflag' name='profileflag'  type='hidden' size=20 maxlength=1 value='$profileflag' />
                        ";
        
        
        
        
        $roomedit = "
                    <div class='mainfont' style='color:$global_textcolor;width:70%;margin:auto;padding:20px;max-width:500px;text-align:left'>
                        <div class='divbuttontext divbuttontext_unsel roomedit tapped' 
                            id='roomchange' data-room='$roomHtml' data-roomid='$roomid' data-mode='$roommode' data-caller='$caller'
                            style='background-color:$global_titlebar_color;color:white'
                            >
                            Save Room Settings
                        </div>
                        &nbsp;&nbsp;
                        <span class='nonmobile'>
                        <div class='divbuttontext divbuttontext_unsel roomedit tapped' 
                            id='roomchange' data-room='$roomHtml' data-roomid='$roomid' data-mode='DR' data-caller='$caller'
                            style='background-color:$global_titlebar_color;color:white'
                            >
                            Delete Room
                        </div>
                        </span>
                        
                        <br><br>
                        <hr>
                        <div class='pagetitle2a' style='color:$global_textcolor;'>
                            <b>Room Identification</b> 
                        </div>
                        <br>
                        Room Name<br>
                        <input id='newroomname' name='room' placeholder='Room Name' type='text' size=20 maxlength=30 value='$roomHtml' style='max-width:400px;width:70%'>
                        <br><br>
                        Description of Room<br>
                        <input id='roomdesc' name='roomdesc' placeholder='Room Description' type='text' size=20 value='$roomdesc' style='max-width:400px;width:70%'>
                        <br><br>
                        <!--
                        Organization/Company<br>
                        <input id='roomorganization' name='roomorganization' placeholder='Organization Name (Optional)' type='text' size=20 value='$organization' style='max-width:400px;width:70%'>
                        <br><br>
                        -->
                         #HashTag (optional)<br>
                        <input id='roomhandle' name='roomhandle' type='text'  $wizardreadonly
                            size=20 maxlength=80 value='$roomhandle' 
                            placeholder='hashtag'
                            style='background:url(../img/hash.png) no-repeat scroll;background-size:20px 20px;padding-left:20px;width:222px;background-color:ivory'/>
                        <br><div class='smalltext' style='margin-top:5px;color:$global_textcolor'>$wizardmessage</div><br><br>
                        
                        Room Photo<br>
                        <input id='photourl' class='smalltext' name='photourl' placeholder='Select a Photo' type='text' size=20 value='$photourl' readonly=readonly style='background-color:whitesmoke;max-width:400px;width:70%'>
                        <br>
                        <span class='photoselect'
                             id='photoselect' style='cursor:pointer'
                             data-target='#photourl' data-src='' data-filename='' data-mode='X' data-caller='roomsetup' title='My Photo Library' >
                            <img class='icon20' src='../img/brax-photo-round-black-128.png' style='cursor:pointer;top:5px;' />
                             &nbsp;Select from My Photos
                        </span>
                        
                        <br><br><br>
                            
                        <span style='$displayForEnterpriseOnly'>

                            $sponsortext    

                            <br><br><br>
                            
                            <hr>
                            <div class='pagetitle2a' style='color:$global_textcolor;'>
                                <b>Public Website Settings</b>
                            </div>
                            <br>
                            <b>Make Room a Public Website?</b><br>
                                $roomexternaltext
                                <br><br>

                            <span class='showhiddenarea' style='$showweb'>
                                
                                <span class=smalltext>
                                    <b>WEBSITE INFO:</b>
                                    This enables the following external room link:<br><br>
                                    <b>http://hashtag.brax.me</b> 
                                    <br><br>
                                    If enabled, the room settings will be changed to OPEN, NON-DISCOVERABLE, OWNER-POSTING only.
                                    The room will not be visible to others inside the app.
                                    <br><br>
                                    Websites are not accessible by users within the app.
                                </span>
                                <br><br><br>




                                Web Color Scheme<br>
                                <select id='webcolorscheme' name='webcolorsceme'  style='width:250px'>
                                    <option value='$webcolorscheme' selected='selected'>$webcolorscheme</option>
                                    <option value='std'>std</option>
                                    <option value='bluegray'>bluegray</option>
                                    <option value='lavender'>lavender</option>
                                    <option value='crimson'>crimson</option>
                                    <option value='skyblue'>skyblue</option>
                                    <option value='riverblue'>riverblue</option>
                                    <option value='midnightblue'>midnightblue</option>
                                    <option value='downygreen'>downygreen</option>
                                    <option value='rustyred'>rustyred</option>
                                    <option value='tuscany'>tuscany</option>
                                    <option value='michigan'>michigan</option>
                                    <option value='lawn'>lawng</option>
                                    <option value='facebook'>facebook</option>
                                    <option value='newyorkpink'>newyorkpink</option>
                                    <option value='moonlit night'>moonlit night</option>
                                    <option value='grape night'>grape night</option>
                                    <option value='crimson night'>crimson night</option>
                                    <option value='forest night'>forest night</option>
                                    <option value='metal night'>metal night</option>
                                    <option value='starry night'>starry night</option>
                                    <option value='dark alley'>dark alley</option>
                                </select>

                                <br><br>
                                Web Title Text Color<br>
                                <select id='webtextcolor' name='webtextcolor'  style='width:250px'>
                                    <option value='$webtextcolor' selected='selected'>$webtextcolor</option>
                                    <option value='black'>black</option>
                                    <option value='white'>white</option>
                                </select>
                                <br><br>


                                Show User Profile on Web<br>
                                $webpublishprofiletext
                                    
                                $storetext
                                <br><br>


                                Wallpaper Photo (optional)<br>
                                <input id='wallpaper' class='smalltext' name='wallpaper' placeholder='Wallpaper' type='text' size=20 value='$photourl2'  style='background-color:whitesmoke;max-width:400px;width:70%'>
                                <br>
                                <span class='photoselect'
                                     id='photoselect' style='cursor:pointer'
                                     data-target='#wallpaper' data-src='' data-filename='' data-mode='X' data-caller='roomsetup' title='My Photo Library' >
                                    <img class='icon20' src='../img/brax-photo-round-black-128.png' style='cursor:pointer;top:5px;' />
                                     &nbsp;Select from My Photos
                                </span>
                                <br><br><br>
                                New Member Welcome Chat Message<br>
                                <textarea class='dataentry' id='autochatmsg' name='roomdesc' placeholder='Welcome Message' 
                                 rows=10 style='width:90%'>$autochatmsg</textarea>
                                <br><br>

                                Welcome Chat User @username<br>
                                <input class='dataentry' id='autochatuser' name='autochatuser' placeholder='@username of Member to Auto Chat' type='text' size=20 maxlength=30 value='$autochatuser' style='width:250px'>

                                <br><br>


                                Advanced Customization Flags<br>
                                <input id='webflags' class='smalltext' name='webflags' placeholder='Flags' type='text' size=20 value='$webflags'  style='background-color:whitesmoke;max-width:400px;width:70%'>
                                <br>
                                <br>
                                <b>Allow Search Engine Discovery</b><br>
                                    $searchenginetext
                                    <br><br>

                                Google Analytics<br>
                                <textarea id='analytics' class='smalltext' name='analytics' placeholder='Analytics'   style='background-color:whitesmoke;max-width:400px;width:70%'>$analytics</textarea>

                                <br><br><br>
                            </span>
                            
                            
                        </span>
                        <span class='showhiddenarea2' style='$showweb2'>
                            
                            <hr>
                            <div class='pagetitle2a' style='color:$global_textcolor;'>
                                <b>Membership Settings</b> <span class='smalltext' style='color:gray'>(Optional)</span>
                            </div>

                            <br>
                            <b>Open or Private Membership?</b><br>
                            $privatetext
                            <br><br><br>

                            <b>Show Members List within Room?</b><br>
                            $showmemberstext
                            <br><br><br>


                            <hr>
                            <div class='pagetitle2a' style='color:$global_textcolor;'>
                                <b>Room Preferences</b> <span class='smalltext' style='color:gray'>(Optional)</span>
                            </div>
                            <br>

                            <b>Who can Post?</b><br>
                            $adminonlytext
                            <br><br><br>
                            <b>Anonymity of Posts</b><br>
                            $roomanonymoustext
                            <br><br>
                            <br>
                            <b>Allow Notifications?</b><br>
                            $notificationstext
                            <br><br><br>
                            <b>Alert Sound</b><br>
                            $soundalerttext
                            <br><br><br>
                            <hr>
                            <div class='pagetitle2a' style='color:$global_textcolor;'>
                                <b>Room Discovery by Users</b> 
                                <br><br>
                            </div>
                            <b>Will be Listed and Discoverable to All App Users?</b><br>
                            $discovertext
                            <br>
                            <span class='smalltext2' style='color:gray'>(Searchable and promotable to other $appname users and subject to age-appropriate content moderation)</span>
                            <br><br>


                            Category<br>
                            <select id='roomcategory' name='roomcategory'  style='width:250px'>
                                $selectcategory
                                <option value='Art'>Art</option>
                                <option value='Broadcasters'>Broadcasters</option>
                                <option value='Business'>Business</option>
                                <option value='Crafts'>Crafts</option>
                                <option value='Cryptocurrency'>Cryptocurrency</option>
                                <option value='Cybersecurity'>Cybersecurity</option>
                                <option value='Do It Yourself'>Do It Yourself</option>
                                <option value='Education'>Education</option>
                                <option value='Entertainment'>Entertainment</option>
                                <option value='Fashion'>Fashion</option>
                                <option value='Food'>Food</option>
                                <option value='Fun'>Fun</option>
                                <option value='Hobbies'>Hobbies</option>
                                <option value='Humor'>Humor</option>
                                <option value='Internet'>Internet</option>
                                <option value='Lifestyle'>Lifestyle</option>
                                <option value='Music'>Music</option>
                                <option value='Opinion'>Opinion</option>
                                <option value='Paranormal'>Paranormal</option>
                                <option value='Periscope'>Periscope App</option>
                                <option value='Philosophy'>Philosphy</option>
                                <option value='Politics'>Politics</option>
                                <option value='Private'>Private</option>
                                <option value='Radio'>Radio</option>
                                <option value='Random'>Random</option>
                                <option value='Religion'>Religion</option>
                                <option value='Schools'>Schools/Colleges</option>
                                <option value='Science'>Science</option>
                                <option value='Singles'>Singles</option>
                                <option value='Sports'>Sports</option>
                                <option value='Stock Market'>Stock Market</option>
                                <option value='Style'>Style</option>
                                <option value='Teach'>Teach</option>
                                <option value='Tech'>Tech</option>
                                <option value='Travel'>Travel</option>
                                <option value='Other'>Other</option>
                                <option value='Other Blogs'>Other Blogs</option>
                            </select>
                            <br><br><br><br><br><br>

                            <hr>
                            <div class='pagetitle2a' style='color:$global_textcolor;'>
                                <b>Advanced Features</b> 
                                <br><br>
                            </div>

                            <br><br>
                            $parenttext


                            Member Group Limitation<br>
                            <select id='roomgroupid' name='roomgroupid'  style='width:250px'>
                                $selectgroup
                                $selectgroupoptions
                            </select>
                            <br>
                            <span class='smalltext' style='color:$global_textcolor'>Limit discovery and membership to this group.</span>
                            <br><br>

                            <span style='$displayForEnterpriseOnly'>

                            Subscription Token Price<br>
                            <input id='subscription' class='smalltext' name='subscription' placeholder='Token Price' type='text' size=20 value='$subscription'  style='background-color:whitesmoke;max-width:400px;width:70%'>
                                <br>
                                <span class='smalltext2'>Use a negative number to accept Test Subscriptions</span>
                            <br><br>
                            Subscription $ Price (Sandbox Test Only)<br>
                            <input id='subscriptionusd' class='smalltext' name='subscriptionusd' placeholder='$ USD Price' type='text' size=20 value='$subscriptionusd'  style='background-color:whitesmoke;max-width:400px;width:70%'>
                            <br><br>
                            Subscription Days (0 = Unlimited)<br>
                            <input id='subscriptiondays' class='smalltext' name='subscriptiondays' placeholder='Subscription Days' type='text' size=20 value='$subscriptiondays'  style='background-color:whitesmoke;max-width:400px;width:70%'>
                            <br><br>



                                
                            $rsslinktext

                            $copymemberstext
                                
                            Room Style<br>
                            <select id='roomstyle' name='roomstyle'  style='width:250px'>
                                $selectroomstyle
                                <option value='std'>Standard</option>
                                <option value='forum'>Forum</option>
                            </select>
                            <br><br>


                            </span>
                            
                            <hr>
                            <br><br>


                            <span class=smalltext>
                                <b>Tip:</b>
                                Discoverable Rooms are open to all and are subject to age-appropriate 
                                content moderation and other content restrictions as specified
                                under the Terms of Use. Content or materials of any kind (text, graphics,
                                images, photographs, sounds, etc.) that in our reasonable judgment may be 
                                found objectionable or
                                inappropriate, for example, materials that may be considered 
                                obscene, pornographic, hate oriented, or defamatory will be
                                removed from open access (removed from Discover Rooms).
                                <br><br>
                                You can promote non-discoverable rooms outside of the app using Group Invite Links as
                                well as through private invites.
                                <br><br>
                                Rooms shall not be established for unlawful purposes and will be subject to reporting 
                                to law enforcement and immediate shut down.

                                <br><br>
                                Although room content is encrypted, please note that the description
                                of the room as defined here is visible internally to us.
                            </span>
                            <br><br><br>

                        </span>


                        <span style='$displayOnEditOnly'>



                            <span style='$displayForAdminOnly'>
                                <br><br>
                                <hr>
                                <div class='pagetitle2a' style='color:$global_textcolor;'>
                                    <b>Admin Only</b> 
                                    <br><br>
                                </div>
                            <br>
                                Join Room #hashtag from Website <br>
                                <input id='roominvitehandle' class='smalltext' name='roominvitehandle' placeholder='hashtag' type='text' size=20 value='$roominvitehandle' 
                                    style='background:url(../img/hash.png) no-repeat scroll;background-size:20px 20px;padding-left:20px;width:222px;background-color:ivory'/>
                                <br><br>
                                Global Community Link<br>
                                <select id='communitylink' name='communitylink'  style='width:250px'>
                                    $selectcommunity
                                    $selectcommunityoptions
                                </select>
                                <br>
                                <span class='smalltext' style='color:$global_textcolor'>Link this room's membership to a global community with a group chat</span>
                                <br><br>

                                $rsscategorytext    



                                $broadcasttext
                            </span>
                            
                        </span>

                    </div>
            ";        
        
        if( $mode == 'N' || $mode == 'E'){
        

            $modedesc = "Room Properties";
            if( $mode == 'N'){
                $modedesc = "Create a New Room";
            }
        }
        
        $roomtip = '';
        if( $mode == 'N' && $_SESSION['roomuser'] == 'N' || $_SESSION['roomuser']=='M'){
        
            $backgroundcolor = '#3d8da5';
            $roomtip = "
                        <div style='width:80%;padding:0;margin:0;max-width:500px'>
                            <div class='tipbubble pagetitle2a gridstdborder' style='color:white;background-color:$backgroundcolor'>
                            CREATE YOUR FIRST ROOM
                            <br><span class='pagetitle3' style='color:white'>Rooms can be private or have open membership</span>
                            </div>
                        </div>
                    ";
        }
        
        $roomtext =  "
                        <br>
                        <center>
                        $roomtip
                        <div class='pagetitle2' style='color:$global_textcolor'>$roomHtml</div>
                        $roomedit

                        <br>
                        ID: 808113-$roomid
           ";
        return $roomtext;
    }
    function GetNewID( $parmkey, $parmcode )
    {
            
        
            $result = do_mysqli_query("1","
                select val1 from parms where parmkey='$parmkey' and parmcode='$parmcode' 
                ");
            if( $row = do_mysqli_fetch("1",$result)){
                $val1 =intval($row['val1']);
            } else {
                
                $maxval = 100;
                if($parmkey =='ROOM') {
                    $result = do_mysqli_query("1","
                        select max(roomid)+1 as maxval from roominfo
                        ");
                    if( $row = do_mysqli_fetch("1",$result)){
                    
                        $maxval =intval($row['maxval']);
                    }
                }
                
                $result = do_mysqli_query("1","
                    insert into parms (parmkey, parmcode, val1, val2 ) values 
                    ('$parmkey','$parmcode', $maxval, 0 )
                ");
                $val1 = $maxroomid;
     
            }
            $result = do_mysqli_query("1","
                update parms set val1=val1+1 where parmkey='$parmkey' and parmcode='$parmcode'
            ");
            $val1++;
            
            return $val1;
        
    }
    function SaveHandle($roomid, $private, $handle, $name, $roomdescription, $discover, 
                        $tags, $category, $organization, $minage, $photourl, $photourl2, $roomanonymous, 
                        $roomexternal, $contactexchange, $adminonly, $notifications, $showmembers,
                        $soundalert, $sharephotoflag, $rsscategory, $groupid, $rsssource, $radiostation, 
                        $sponsor, $parent, $childsort, $copymembers, $profileflag, $roominvitehandle,
                        $webcolorscheme, $webtextcolor, $webpublishprofile, $webflags, $searchengine, 
                        $analytics, $subscriptiondays, $subscription, $subscriptionusd, $wallpaper,
                        $autochatuser, $autochatmsg, $community, $communitylink, $store, $roomstyle )
    {
        
        
        /* Notes 
         *  roominfo.external = Website
         *  roomhandle.public = discoverable
         *  roominfo.private = open vs. private
         *  roominfo.adminonly = owner posting
         */
        global $iconsource_braxwarning_common;
        
        //If website - default other settings
        if($roomexternal == 'Y'){
            $adminonly = 'Y';
            $private = 'N';
            $discover = 'N';
            $showmembers = 'N';
            if($autochatuser ==''){
                //$autochatuser = $_SESSION['handle'];
            }
            
        } else {
            $autochatuser = '';
            
        }
        if($community == 'Y'){
            $adminonly = 'Y';
            $discover = 'N';
            $showmembers = 'N';
            $private = 'N';
            $parent = '';
            $roomexternal = 'N';
            
        }
        if($webtextcolor == ''){
            $webtextcolor = 'white';
        }
        
        
        
        //echo "anon = $roomanonymous";
        $error = "";
        if($groupid == ''){
            $groupid = 'null';
        }
        
        if($discover ==''){
        
            $discover = 'N';
        }
        if($roomexternal!='Y'){
            $roomexternal = 'N';
        }
        
        $handle = str_replace(" ","",$handle);
        $handle = str_replace("'","",$handle);
        $handle = str_replace('"',"",$handle);
        $handle = stripslashes($handle);
        if( $handle!='' && $handle[0]!='#'){
        
            $handle = "#".$handle;
        }
        
        if( CheckValidHandle($handle, $roomid)){
            $error = "<img class='icon20' src='$iconsource_braxwarning_common'' /> Room hashtag $handle is already taken.<br>Please enter another";
            $handle = "";
        }
        
        
        
        $roomdesc = htmlentities($roomdescription, ENT_QUOTES);
        $tagsclean = tvalidator("PURIFY",$tags);
        $sponsor = tvalidator("PURIFY",$sponsor);
        $parent = tvalidator("PURIFY",$parent);
        $nameclean = tvalidator("PURIFY",stripslashes($name));
        $photourl = tvalidator("PURIFY",$photourl);
        
        //$welcome = htmlentities($welcome, ENT_QUOTES);
        //$welcome = html_entity_decode($welcome,ENT_QUOTES);
        //$autochatmsg = strip_tags($autochatmsg,"<br>");
        $autochatmsg = str_replace("\\n","<br />", $autochatmsg);
        
        //$autochatmsg = str_replace("\n","",$autochatmsg);
        //$autochatmsg = str_replace("\r","",$autochatmsg);
        //$autochatmsg = htmlentities($autochatmsg, ENT_QUOTES);
        $community = tvalidator("PURIFY",$community);
        $communitylink = tvalidator("PURIFY",$communitylink);
        
        
        $photourl2 = $photourl;
        //$photourl2 = tvalidator("PURIFY",$photourl2);
        $organizationclean = tvalidator("PURIFY",stripslashes($organization));
        $age = intval($minage);
        $childsort = intval($childsort);
        
        $result = do_mysqli_query("1","
            select rank from roomhandle where roomid = $roomid 
        ");
        $row = do_mysqli_fetch("1",$result);
        $rank = intval($row['rank']);
        
        $result = do_mysqli_query("1","
            delete from roomhandle where roomid = $roomid 
        ");
        $result = do_mysqli_query("1","
            insert ignore into roominfo (roomid ) values ($roomid)
        ");
        
        //$analytics64 = base64_encode(html_entity_decode($analytics));
        $analytics64 = base64_encode($analytics);
        //$paypal64 = base64_encode($paypal);
        $subscriptionvalue = (float) $subscription;
        $subscriptionusdvalue = (float) $subscriptionusd;
        $subscriptiondaysvalue = (int) $subscriptiondays;
        if($roomstyle==''){
            $roomstyle = 'std';
        }
        
        $result = do_mysqli_query("1","
            update roominfo
            set room = '$nameclean', roomdesc='$roomdesc', photourl='$photourl', photourl2='$photourl2',
            anonymousflag='$roomanonymous', external='$roomexternal', organization='$organizationclean',
            private='$private',contactexchange='N',adminonly='$adminonly',
            notifications='$notifications',showmembers='$showmembers', soundalert='$soundalert',
            sharephotoflag='$sharephotoflag',rsscategory='$rsscategory', groupid= $groupid,
            rsssource='$rsssource', rsssourceid='', radiostation='$radiostation', sponsor='$sponsor', 
            parentroom='$parent', childsort=$childsort, profileflag='$profileflag', 
            roominvitehandle='$roominvitehandle', webcolorscheme='$webcolorscheme', webtextcolor ='$webtextcolor',
            webpublishprofile='$webpublishprofile', webflags='$webflags', searchengine='$searchengine', 
            analytics='$analytics64', subscriptiondays=$subscriptiondaysvalue, subscription=$subscriptionvalue,subscriptionusd=$subscriptionusdvalue, 
            wallpaper='$wallpaper', autochatuser='$autochatuser', autochatmsg='$autochatmsg', 
            communitylink = '$communitylink', store = '$store', roomstyle='$roomstyle'
           
            where roomid = $roomid
        ");
        
        
        if( $handle != '' && $handle!='#')
        {
            $result = do_mysqli_query("1","
                insert into roomhandle 
                (roomid, handle, name, public, roomdesc, tags, category, community ) 
                values 
                ($roomid, '$handle', '$nameclean', '$discover', '$roomdesc', '$tagsclean', '$category','$community' ) 
            ");
        }
        if($rsssource!=''){
            CreateRssSource($roomid, $rsssource);
        } else {
            $result = do_mysqli_query("1","
                update roominfo set rsstimestamp = 0 where roomid = $roomid
            ");
            
        }

        //Don't allow reversal of this setting. If anonymous once, past posts will remain anonymous
        if($roomanonymous == 'Y'){
            do_mysqli_query("1","update statusposts set anonymous = 'Y' where roomid = $roomid ");
        }
        
        if($parent!=='' || $copymembers!==''){
            $result = do_mysqli_query("1","select roomid, (select owner from statusroom where statusroom.roomid = $roomid and statusroom.owner = statusroom.providerid) as providerid from roomhandle where handle='$copymembers' ");
            if($row = do_mysqli_fetch("1",$result)){
                $sourceroomid = $row['roomid'];
                CopyMembersToRoom($row[providerid], $sourceroomid, $roomid);
            }
            
            
        }
        
        return "$error";
    }
    
    function ConstructSelect($providerid, $roomid)
    {
        global $installfolder;
        global $global_textcolor;
        global $global_background;
        global $iconsource_braxmedal_common;
        
        
        
        
        $lock =  "<img class='icon15' src='../img/Lock-2_120px.png' style='top:3px;opacity:.3' />";
        $discover =  "<img class='icon15' src='../img/Cloud-Computer_120px.png' style='top:3px;opacity:.3' />";
        
        if(intval($roomid) > 0){
            return "";
        }
        //$room1 = htmlentities($room,ENT_QUOTES);
        //$room2 = stripslashes($room);
        
        
        $select = "
            <div class='pagetitle2'  style='color:$global_textcolor;text-align:center;margin:auto;vertical-align:top'>
                  ";
        
            $result = do_mysqli_query("1","
                select distinct roominfo.room, statusroom.roomid, roominfo.photourl,
                roominfo.private, roominfo.external, roomhandle.public, roominfo.rsscategory
                from statusroom 
                left join roominfo on roominfo.roomid = statusroom.roomid
                left join roomhandle on roomhandle.roomid = statusroom.roomid
                where (
                    statusroom.owner=$providerid 
                   )
                and roominfo.room!=''
                and roomhandle.community = 'Y'
                and statusroom.providerid = statusroom.owner


                order by roominfo.room asc
            ");
    //                statusroom.owner=$providerid or
    //                statusroom.roomid in (select roomid from roommoderator where providerid = $providerid )


            $i=0;
            while($row = do_mysqli_fetch("1",$result)){

                if($i==0){
                    $select .= "<div class='pagetitle2a' style='color:$global_textcolor;margin:auto'>Communities</div>";
                }

                $selected = "";

                $room1 = htmlentities(stripslashes($row['room']),ENT_QUOTES);
                $room2 = stripslashes($row['room']);
                if($row['photourl']==''){
                    $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
                }
                $photourl = "
                        <div class='circular' style='float:left;text-align:left;padding:0px;vertical-align:top;;margin-right:10px;overflow:hidden'>
                            <img src='$row[photourl]' style='height:100%;width:auto' />
                        </div>
                        ";

                $private = ".";
                $public = "";
                $rss = "";
                if($row['private']=='Y'){
                    $private = $lock;
                }
                if($row['public']=='Y'){
                    $public = "";//$discover;
                }
                if($row['rsscategory']!=''){
                    $rss = "$discover Feed";
                }

                $select .=      "<div data-room='$room1' data-roomid='$row[roomid]' data-mode='' 
                                class='roomedit pagetitle3 smalltext' 
                               style='width:200px;min-width:20%;
                               vertical-align:top;
                               margin-left:5px;margin-right:5px;margin-top:0px;margin-bottom:5px;
                               font-weight:500;cursor:pointer;text-align:left;
                               background-color:whitesmoke;color:black;display:inline-block;height:80px;
                               padding-left:10px;padding-right:10px;border-radius:5px;
                               padding-bottom:5px;padding-top:10px;overflow:hidden;'>

                                <div class='mainfont' style='width:100%;margin:0;padding:0'>
                                    $room2 
                                </div>
                                    $photourl 
                                <div class='smalltext2' style='float:left'>
                                <br>
                                $private $public $rss
                                </div>
                               </div>";
                $i++;
            }

            $select .= "<br><br><br>";
                
        
        if($_SESSION['enterprise']=='Y'){
            

            $result = do_mysqli_query("1","
                select distinct roominfo.room, statusroom.roomid, roominfo.photourl,
                roominfo.private, roominfo.external, roomhandle.public, roominfo.rsscategory
                from statusroom 
                left join roominfo on roominfo.roomid = statusroom.roomid
                left join roomhandle on roomhandle.roomid = statusroom.roomid
                where (
                    statusroom.owner=$providerid 
                   )
                and roominfo.room!=''
                and roominfo.profileflag!='Y'
                and roominfo.external = 'Y'
                and statusroom.providerid = statusroom.owner


                order by roominfo.room asc
            ");
    //                statusroom.owner=$providerid or
    //                statusroom.roomid in (select roomid from roommoderator where providerid = $providerid )


            $i=0;
            while($row = do_mysqli_fetch("1",$result)){

                if($i==0){
                    $select .= "<div class='pagetitle2a' style='color:$global_textcolor;margin:auto'>Websites</div>";
                }

                $selected = "";

                $room1 = htmlentities(stripslashes($row['room']),ENT_QUOTES);
                $room2 = stripslashes($row['room']);
                if($row['photourl']==''){
                    $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
                }
                $photourl = "
                        <div class='circular' style='float:left;text-align:left;padding:0px;vertical-align:top;;margin-right:10px;overflow:hidden'>
                            <img src='$row[photourl]' style='height:100%;width:auto' />
                        </div>
                        ";

                $private = ".";
                $public = "";
                $rss = "";
                if($row['private']=='Y'){
                    $private = $lock;
                }
                if($row['public']=='Y'){
                    $public = "";//$discover;
                }
                if($row['rsscategory']!=''){
                    $rss = "$discover Feed";
                }

                $select .=      "<div data-room='$room1' data-roomid='$row[roomid]' data-mode='' 
                                class='roomedit pagetitle3 smalltext' 
                               style='width:200px;min-width:20%;
                               vertical-align:top;
                               margin-left:5px;margin-right:5px;margin-top:0px;margin-bottom:5px;
                               font-weight:500;cursor:pointer;text-align:left;
                               background-color:whitesmoke;color:black;display:inline-block;height:80px;
                               padding-left:10px;padding-right:10px;border-radius:5px;
                               padding-bottom:5px;padding-top:10px;overflow:hidden;'>

                                <div class='mainfont' style='width:100%;margin:0;padding:0'>
                                    $room2 
                                </div>
                                    $photourl 
                                <div class='smalltext2' style='float:left'>
                                <br>
                                $private $public $rss
                                </div>
                               </div>";
                $i++;
            }

            $select .= "<br><br><br>";
        }
        
        $result = do_mysqli_query("1","
            select distinct roominfo.room, statusroom.roomid, roominfo.photourl,
            roominfo.private, roominfo.external, roomhandle.public, roominfo.rsscategory
            from statusroom 
            left join roominfo on roominfo.roomid = statusroom.roomid
            left join roomhandle on roomhandle.roomid = statusroom.roomid
            where (
                statusroom.owner=$providerid 
               )
            and roominfo.room!=''
            and roominfo.profileflag!='Y'
            and roominfo.external = 'N'
            and (roomhandle.community !='Y' or roomhandle.community is null) 
            and statusroom.providerid = statusroom.owner
            
            
            order by roominfo.room asc
        ");
//                statusroom.owner=$providerid or
//                statusroom.roomid in (select roomid from roommoderator where providerid = $providerid )
    
        $i2 = 0;
        while($row = do_mysqli_fetch("1",$result)){
        

            if($i2==0){
                $select .= "<div class='pagetitle2a' style='color:$global_textcolor;margin:auto'>Rooms</div>";
            }
            $selected = "";

            $room1 = htmlentities(stripslashes($row['room']),ENT_QUOTES);
            $room2 = stripslashes($row['room']);
            if($row['photourl']==''){
                $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            }
            $photourl = "
                    <div class='circular' style='float:left;text-align:left;padding:0px;vertical-align:top;;margin-right:10px;overflow:hidden'>
                        <img src='$row[photourl]' style='height:100%;width:auto' />
                    </div>
                    ";

            $private = ".";
            $public = "";
            $rss = "";
            if($row['private']=='Y'){
                $private = $lock;
            }
            if($row['public']=='Y'){
                $public = "";//$discover;
            }
            if($row['rsscategory']!=''){
                $rss = "$discover Feed";
            }

            $select .=      "<div data-room='$room1' data-roomid='$row[roomid]' data-mode='' 
                            class='roomedit pagetitle3 smalltext' 
                           style='width:200px;min-width:20%;
                           vertical-align:top;
                           margin-left:5px;margin-right:5px;margin-top:0px;margin-bottom:5px;
                           font-weight:500;cursor:pointer;text-align:left;
                           background-color:whitesmoke;color:black;display:inline-block;height:80px;
                           padding-left:10px;padding-right:10px;border-radius:5px;
                           padding-bottom:5px;padding-top:10px;overflow:hidden;'>
                           
                            <div class='mainfont' style='width:100%;margin:0;padding:0'>
                                $room2 
                            </div>
                                $photourl 
                            <div class='smalltext2' style='float:left'>
                            <br>
                            $private $public $rss
                            </div>
                           </div>";
            $i++;
            $i2++;
        }        
        
        
        
        $select .=
        "
            </div>
            <br>
            <br>
            <br>#: $i
        ";
        if( $i == 0) {
            return "";
        }
        return $select;
    
    }
    function CreateRssSource($roomid, $rss)
    {
        $sourceid = "";
        //Check to see if source already exists
        $result = do_mysqli_query("news","
                select id, url, status from rss_sources where url = '$rss' 
            ");
        if($row = do_mysqli_fetch("news",$result)){
            if( $row['status']=='Active'){
                $sourceid = $row['id'];
            }
            
        } else {
            
            $result = do_mysqli_query("news","
                insert into rss_sources 
                (type, name, site_url, url, last_crawled, last_updated, crawl_interval,
                link_type, status, article_status, favicon, keywords, featured,
                altimg_url, diocese )
                values
                ('','Room Feed','$rss', '$rss', 0, 0, 0, 'Yes','Active', 'Published','','', 
                    null, null, null )
            ");
            $result = do_mysqli_query("news","
                select id, url from rss_sources where url = '$rss' 
                ");
            if($row = do_mysqli_fetch("news",$result)){
                $sourceid = $row['id'];
            }
            
        }
        
            
        $result = do_mysqli_query("1","
            update roominfo set rsssourceid = '$sourceid', rsstimestamp = 0 where roomid = $roomid and rsssourceid != '$sourceid'
            ");
            
        return $sourceid;
    }
    
    function MemberList($providerid, $roomid, $filter, $caller)
    {
        global $rootserver;
        global $iconsource_braxmedal_common;
        global $global_textcolor;
        
        $result = do_mysqli_query("1",
            "
                select provider.replyemail, providername as name, provider.providerid,
                avatarurl, roominfo.room, statusroom.roomid, provider.handle, 
                roommoderator.providerid as moderator, statusroom.owner
                from statusroom
                left join roominfo on statusroom.roomid = roominfo.roomid
                left join provider on statusroom.providerid = provider.providerid
                left join roommoderator on roommoderator.providerid = statusroom.providerid
                    and roommoderator.roomid = statusroom.roomid
                where 
                provider.active='Y'
                and statusroom.roomid=$roomid
                and (provider.providername like '%$filter%' or provider.handle like '%$filter%')
                order by providername limit 500
             ");

        while($row = do_mysqli_fetch("1",$result)){
        
            $avatar = $row['avatarurl'];
            if($avatar == "$rootserver/img/faceless.png"){
                $avatar = "$rootserver/img/newbie2.jpg";
            }
            if($row['handle']!=''){
                $row['replyemail']=$row['handle'];
            }
            $row['name']=substr($row['name'],0,20);
            $row['replyemail']=substr($row['replyemail'],0,20);
            $room2 = htmlentities($row['room'],ENT_QUOTES);
            echo "
                            <div class='roomlistbox rounded gridstdborder' 
                                style='display:inline-block;padding-top:2px;;
                                background-color:white;color:black;margin-bottom:10px;overflow:hidden;border-color:gray;border-width:1px'>
                                
                                <div style='background-color:white;color:black;height:65%;overflow:hidden;width:100%;' >
                                    <img class='avatar1' src='$avatar' style='width:auto;max-width:100%;min-height:100%;;overflow:hidden' />
                                        <br>
                                </div>
                                <div class='smalltext2' style='color:black;word-wrap:break-word;width:95%;margin-top:5px;margin-bottom:2px;padding-left:5px;padding-right:5px;overflow:hidden'>
                                    $row[name]
                                    <br>
                                    $row[replyemail]
                                    <br>
                                </div>
                                    <img class='icon20 friends' src='../img/delete-circle-128.png' style='' 
                                        id='deletefriends' 
                                        data-providerid='$row[providerid]'  data-roomid='$roomid' data-mode='D' data-caller='$caller' />
                    ";
            if($row['moderator']!='' || $row['providerid']==$providerid){
                echo "
                                    &nbsp;&nbsp; <img class='icon20 friends' src='$iconsource_braxmedal_common' style='' 
                                        id='' 
                                        data-providerid='$row[providerid]' data-room='$room2' data-roomid='$roomid' data-mode='MOD' />
                            </div>
                   ";
            } else
            if($row['owner']==$providerid){
                echo " 
                                    &nbsp;&nbsp;  <img class='icon20 friends' src='../img/medal-02-128.png' style='opacity:.3' 
                                        id='' 
                                        data-providerid='$row[providerid]' data-room='$room2' data-roomid='$roomid' data-mode='MOD' />
                            </div>
                   ";
                
            } else {
                echo " 
                            </div>
                   ";
                
            }


        }
        
        echo "<br><br><span class='smalltext2' style='color:$global_textcolor'><img class='icon15' src='$iconsource_braxmedal_common' style='top:3px;cursor:none;' /> = Moderator</span>";

        
    }        
    function GetRoomData($roomid, $providerid)
    {
        
    $stdanalytics = "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');04-1', 'auto');ga('send', 'pageview');</script>";

            
        $roomdata = array();
        
        $roomdata['IsOwner'] = false;
        $roomdata['room'] = '';
        $roomdata['roomForSql'] = '';
        $roomdata['roomHtml'] = '';

        $roomdata['roomhandle'] = '';
        $roomdata['roomdesc'] = '';
        $roomdata['tags'] = '';
        $roomdata['discover'] = '';
        $roomdata['minage'] = '';;
        $roomdata['photourl'] = '';
        $roomdata['photourl2'] = '';
        $roomdata['category'] = '';
        $roomdata['organization'] = '';
        $roomdata['roomanonymous'] = '';
        $roomdata['private'] = '';
        $roomdata['contactexchange'] = '';
        $roomdata['adminonly'] = '';
        $roomdata['notifications'] = '';
        $roomdata['showmembers'] = 'Y';
        $roomdata['soundalert'] = '';
        $roomdata['sharephotoflag'] = '';
        $roomdata['rsscategory'] = '';
        $roomdata['rsssource'] = '';
        $roomdata['rsssourceid'] = '';
        $roomdata['private'] = 'Y';
        $roomdata['groupid'] = '';
        $roomdata['failreason'] ='';
        $roomdata['radiostation'] = '';
        $roomdata['parent'] = '';
        $roomdata['childsort'] = 0;
        $roomdata['sponsor'] ='';
        $roomdata['profileflag'] ='';
        $roomdata['roomexternal'] ='';
        $roomdata['roominvitehandle']='';
        $roomdata['webcolorscheme']='';
        $roomdata['webtextcolor']='';
        $roomdata['webpublishprofile']='Y';
        $roomdata['webflags']='';
        $roomdata['searchengine']='';
        $roomdata['analytics']='';
        $roomdata['subscriptiondays']='';
        $roomdata['subscription']='';
        $roomdata['subscriptionusd']='';
        $roomdata['wallpaper']='';
        $roomdata['autochatuser']=$_SESSION['handle'];
        $roomdata['autochatmsg']='';
        $roomdata['community']='';
        $roomdata['communitylink']='';
        $roomdata['store']='';
        $roomdata['wizardenterprise']='';
        $roomdata['roomstyle']='';
        
        if(intval($roomid)==0){
            return (object) $roomdata;
        }
        
        $result = do_mysqli_query("1","
            select roomhandle.handle, roominfo.room, roominfo.roomdesc, roomhandle.tags, roomhandle.public, 
            roomhandle.community, roominfo.communitylink, roomhandle.minage, roominfo.photourl, roominfo.photourl2, roomhandle.category, roominfo.organization,
            roominfo.anonymousflag, roominfo.private, roominfo.contactexchange, roominfo.adminonly,
            roominfo.notifications, roominfo.soundalert, roominfo.sharephotoflag, 
            roominfo.rsscategory, roominfo.rsssource, roominfo.rsssourceid, roominfo.groupid,
            roominfo.showmembers, roominfo.radiostation, statusroom.owner,
            roominfo.sponsor, roominfo.parentroom, roominfo.childsort, roominfo.profileflag,
            roominfo.external, roominfo.roominvitehandle, roominfo.webcolorscheme, roominfo.webtextcolor,
            roominfo.webpublishprofile, roominfo.webflags, roominfo.searchengine, roominfo.analytics, 
            roominfo.subscriptiondays, roominfo.subscription, roominfo.subscriptionusd, roominfo.wallpaper,
            roominfo.autochatuser, roominfo.autochatmsg, roominfo.store, roominfo.wizardenterprise,
            roominfo.roomstyle
            from roominfo 
            left join statusroom on statusroom.roomid = roominfo.roomid and statusroom.owner = statusroom.providerid
            left join roomhandle on roomhandle.roomid = roominfo.roomid
            where roominfo.roomid=$roomid 
            ");

        if( $row = do_mysqli_fetch("1",$result)){

            $roomdata['IsOwner'] = false;
            if($row['owner']==$providerid ){
                $roomdata['IsOwner'] = true;
            }
            
            $roomdata['room'] = stripslashes($row['room']);
            $roomdata['roomForSql'] = tvalidator("PURIFY",stripslashes($row['room']));
            $roomdata['roomHtml'] = htmlentities(stripslashes($row['room']),ENT_QUOTES);
            
            $roomdata['roomhandle'] = $row['handle'];
            if($roomdata['roomhandle'][0]=='#'){
                $roomdata['roomhandle'] = substr($roomdata['roomhandle'],1);
            }
            $roomdata['roomdesc'] = $row['roomdesc'];
            $roomdata['tags'] = $row['tags'];
            $roomdata['discover'] = $row['public'];
            $roomdata['minage'] = $row['minage'];
            $roomdata['photourl'] = $row['photourl'];
            $roomdata['photourl2'] = $row['photourl2'];
            $roomdata['category'] = $row['category'];
            $roomdata['organization'] = $row['organization'];
            $roomdata['roomanonymous'] = $row['anonymousflag'];
            $roomdata['private'] = $row['private'];
            $roomdata['contactexchange'] = $row['contactexchange'];
            $roomdata['adminonly'] = $row['adminonly'];
            $roomdata['notifications'] = $row['notifications'];
            $roomdata['showmembers'] = $row['showmembers'];
            $roomdata['soundalert'] = $row['soundalert'];
            $roomdata['sharephotoflag'] = $row['sharephotoflag'];
            $roomdata['rsscategory'] = $row['rsscategory'];
            $roomdata['rsssource'] = $row['rsssource'];
            $roomdata['rsssourceid'] = $row['rsssourceid'];
            $roomdata['groupid'] = $row['groupid'];
            $roomdata['radiostation'] = $row['radiostation'];
            $roomdata['sponsor'] = $row['sponsor'];
            $roomdata['parent'] = $row['parentroom'];
            $roomdata['childsort'] = intval($row['childsort']);
            $roomdata['profileflag'] = $row['profileflag'];
            $roomdata['roomexternal'] = $row['external'];
            $roomdata['roominvitehandle'] = $row['roominvitehandle'];
            if($roomdata['roominvitehandle']==''){
                $roomdata['roominvitehandle']=$roomdata['roomhandle'];
            }
            $roomdata['webcolorscheme'] = $row['webcolorscheme'];
            $roomdata['webtextcolor'] = $row['webtextcolor'];
            if($row['webtextcolor']==''){
                $roomdata['webtextcolor']='white';
            }
            $roomdata['webpublishprofile'] = $row['webpublishprofile'];
            $roomdata['webflags'] = $row['webflags'];
            
            $roomdata['searchengine'] = $row['searchengine'];
            $roomdata['analytics'] = base64_decode($row['analytics']);
            
            if($roomdata['roomexternal']=='Y' && $roomdata['analytics']==''){
                $roomdata['analytics'] = $stdanalytics;
                        
            }
            $roomdata['subscription'] = $row['subscription'];
            $roomdata['subscriptionusd'] = $row['subscriptionusd'];
            $roomdata['subscriptiondays'] = $row['subscriptiondays'];
            $roomdata['wallpaper'] = $row['wallpaper'];
            $roomdata['autochatuser'] = $row['autochatuser'];
            if($roomdata['autochatuser']=='' && $roomdata['roomexternal']=='Y'){
                $roomdata['autochatuser']=$_SESSION['handle'];
            }
            $roomdata['autochatmsg'] = $row['autochatmsg'];
            $roomdata['community'] = $row['community'];
            $roomdata['communitylink'] = $row['communitylink'];
            $roomdata['store'] = $row['store'];
            $roomdata['wizardenterprise'] = $row['wizardenterprise'];
            $roomdata['roomstyle'] = $row['roomstyle'];
            
            
                
            if($roomdata['private'] == '' && $roomdata['roomhandle']!=''){
            
                $roomdata['private'] = 'N';
            }
            if($roomdata['roomhandle']==''){
            
                $roomdata['private'] = 'Y';
            }
            
            if( $row['rsssourceid']!=''){
                
                $result = do_mysqli_query("news","
                    select failreason from rss_sources where id = $row[rsssourceid] 
                ");
                if($row = do_mysqli_fetch("news",$result)){
                    if($row['failreason']!=''){
                        $roomdata['failreason'] = "Feed Error: ".$row['failreason'];
                    }
                }
                
            }
        }
        return (object) $roomdata;
    }
    function NewProfileRoom($providerid)
    {
        $roomid = GetNewID( "ROOM", "ID" );
            
            do_mysqli_query("1","
                insert into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                ( $roomid,$providerid, $providerid, '', now(), $providerid )
                ");
            
            $private = 'Y';
            $handle = "";
            $room = "About Me";
            $roomdescription = "";
            $discover = "N";
            $tags = "";
            $category = "Private";
            $organization = "";
            $minage = "0";
            $photourl = "";
            $roomanonymous = "A";
            $roomexternal = "N";
            $contactexchange = "N";
            $adminonly = "N";
            $notifications = "N";
            $showmembers = "N";
            $soundalert = "";
            $sharephotoflag = "";
            $rsscategory = "";
            $groupid = 0;
            $rsssource = "";
            $radiostation = "";
            $sponsor = "";
            $parent = "";
            $childsort = 0;
            $copymembers = "";
            $profileflag = "Y";
            $photourl2 = "";
            $roominvitehandle = "";
            $webcolorscheme = "";
            $webtextcolor = "";
            $webpublishprofile = "";
            $webflags = "";
            $searchengine = "";
            $paypal = "";
            $subscription = 0;
            $subscriptiondays = 0;
            $subscriptionusd = 0;
            $analytics = "";
            $wallpaper = "";
            $autochatuser = "";
            $autochatmsg = "";
            $community = "";
            $communitylink = "";
            $store = "N";
            $roomstyle = 'std';
            
            
            $error = SaveHandle($roomid, $private, $handle, $room, $roomdescription, 
                    $discover, $tags, $category, $organization, $minage,$photourl, $photourl2,
                    $roomanonymous, $roomexternal, $contactexchange, $adminonly, 
                    $notifications, $showmembers, $soundalert, $sharephotoflag, 
                    $rsscategory, $groupid, $rsssource, $radiostation, $sponsor, 
                    $parent, $childsort, $copymembers, $profileflag, $roominvitehandle, 
                    $webcolorscheme, $webtextcolor, $webpublishprofile, $webflags, $searchengine, 
                    $analytics, $subscriptiondays, $subscription, $subscriptionusd, $wallpaper, $autochatuser, $autochatmsg, 
                    $community, $communitylink, $store, $roomstyle  );
            
            do_mysqli_query("1","update provider set profileroomid = $roomid where providerid=$providerid ");
            
            return $roomid;
    }
    function NewProfileRoomMember($roomid, $owner, $memberid)
    {
            do_mysqli_query("1","
                insert ignore into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                ( $roomid,$owner, $memberid, '', now(), $memberid )
                ");
    }
    function FindProfileRoom($providerid, $viewerid)
    {
        $result = do_mysqli_query("1","
            select roomid from roominfo where profileflag ='Y' and profileflag is not null 
            and roomid in (select roomid from statusroom 
            where providerid = $providerid and providerid = owner) ");
        
        if($row = do_mysqli_fetch("1",$result)){
            
            $vieweraction = "feed";
            $roomid = $row['roomid'];
            
            if($providerid != $viewerid){
                NewProfileRoomMember($roomid, $providerid, $viewerid);
            }

        }  else {
            
            if($providerid == $viewerid ){

                $roomid = NewProfileRoom($providerid);
                $vieweraction = "userview";
            } else {
                $roomid = "";
                $vieweraction = "";
            }
            
        }
        if($_SESSION['superadmin']!='Y'){
            //$vieweraction = 'userview';
        }
        $array['roomid']=$roomid;
        $array['action']=$vieweraction;
        return (object) $array;
    }
    
?>