<?php
session_start();
require("validsession.inc.php");
require_once("nohost.php");
require_once("config-pdo.php");
require_once("room.inc.php");
require_once("roommanage.inc.php");
require_once("internationalization.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("PURIFY",$_POST['providerid']);
    
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = stripslashes(@tvalidator("PURIFY",$_POST['roomid']));
    $friendproviderid = @tvalidator("PURIFY",$_POST['friendproviderid']);
    $handle = strtolower(stripslashes(@tvalidator("PURIFY",$_POST['handle'])));
    $newroom = @tvalidator("PURIFY",stripslashes($_POST['newroom']));
    $filter = @tvalidator("PURIFY",stripslashes($_POST['filter']));
    
    $room = @tvalidator("PURIFY",stripslashes($_POST['room']));
    $roomForSql = @tvalidator("PURIFY",stripslashes($room));
    $roomHtml = htmlentities(stripslashes($room),ENT_QUOTES);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $minage = intval(@tvalidator("PURIFY",$_POST['minage']));
    $tags = stripslashes(@tvalidator("PURIFY",$_POST['tags']));
    $category = stripslashes(@tvalidator("PURIFY",$_POST['category']));
    $organization = stripslashes(@tvalidator("PURIFY",$_POST['organization']));
    $discover = stripslashes(@tvalidator("PURIFY",$_POST['discover']));
    //$roomdescription = stripslashes(@tvalidator("PURIFY",$_POST['roomdescription']));
    $roomdescription = @tvalidator("PURIFY",$_POST['roomdescription']);
    //$roomdesc = url_decode($roomdescription);
    $photourl = stripslashes(@tvalidator("PURIFY",$_POST['photourl']));
    $photourl2 = stripslashes(@tvalidator("PURIFY",$_POST['photourl2']));
    $roomanonymous = stripslashes(@tvalidator("PURIFY",$_POST['roomanonymous']));
    $roomexternal = stripslashes(@tvalidator("PURIFY",$_POST['roomexternal']));
    $private = stripslashes(@tvalidator("PURIFY",$_POST['private']));
    $contactexchange = stripslashes(@tvalidator("PURIFY",$_POST['contactexchange']));
    $adminonly = stripslashes(@tvalidator("PURIFY",$_POST['adminonly']));
    $notifications = stripslashes(@tvalidator("PURIFY",$_POST['notifications']));
    $soundalert = stripslashes(@tvalidator("PURIFY",$_POST['soundalert']));
    $sharephotoflag = stripslashes(@tvalidator("PURIFY",$_POST['sharephotoflag']));
    $rsscategory = stripslashes(@tvalidator("PURIFY",$_POST['rsscategory']));
    $rsssource = stripslashes(@tvalidator("PURIFY",$_POST['rsssource']));
    $showmembers = stripslashes(@tvalidator("PURIFY",$_POST['showmembers']));
    $groupid = stripslashes(@tvalidator("PURIFY",$_POST['groupid']));
    $sponsor = stripslashes(@tvalidator("PURIFY",$_POST['sponsor']));
    $parent = stripslashes(@tvalidator("PURIFY",$_POST['parent']));
    $childsort = stripslashes(@tvalidator("PURIFY",$_POST['childsort']));
    $copymembers = stripslashes(@tvalidator("PURIFY",$_POST['copymembers']));
    $profileflag = stripslashes(@tvalidator("PURIFY",$_POST['profileflag']));
    $roominvitehandle = stripslashes(@tvalidator("PURIFY",$_POST['roominvitehandle']));
    $webcolorscheme = stripslashes(@tvalidator("PURIFY",$_POST['webcolorscheme']));
    $webtextcolor = stripslashes(@tvalidator("PURIFY",$_POST['webtextcolor']));
    $webpublishprofile = stripslashes(@tvalidator("PURIFY",$_POST['webpublishprofile']));
    $webflags = stripslashes(@tvalidator("PURIFY",$_POST['webflags']));
    $wallpaper = stripslashes(@tvalidator("PURIFY",$_POST['wallpaper']));
    
    $analytics = @htmlentities(stripslashes($_POST['analytics']));
    $searchengine = stripslashes(@tvalidator("PURIFY",$_POST['searchengine']));
    $subscription = stripslashes(@tvalidator("PURIFY",$_POST['subscription']));
    $subscriptionusd = stripslashes(@tvalidator("PURIFY",$_POST['subscriptionusd']));
    $subscriptiondays = stripslashes(@tvalidator("PURIFY",$_POST['subscriptiondays']));
    
    $autochatuser = stripslashes(@tvalidator("PURIFY",$_POST['autochatuser']));
    $autochatmsg = @tvalidator("PURIFY",$_POST['autochatmsg']);
    $community = @tvalidator("PURIFY",$_POST['community']);
    $communitylink = @tvalidator("PURIFY",$_POST['communitylink']);
    $store = @tvalidator("PURIFY",stripslashes($_POST['store']));
    $roomstyle = @tvalidator("PURIFY",stripslashes($_POST['roomstyle']));
        

    $radiostation = strtoupper(stripslashes(@tvalidator("PURIFY",$_POST['radiostation'])));
    
    if($handle!=''){
    
        $handle = preg_replace("/[^A-Za-z0-9]/", "", strtolower($handle));
    }
    
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $help = "<img class='helpinfo' src='../img/help-gray-128.png' 
            style='height:20px;width:auto;position:relative;top:3px;padding-left:10px;padding-right:10px;cursor:pointer' 
            data-help='#RoomHashTag<br><br>".
            "Unique alphanumeric name starting with #. No spaces. Case insensitive.<br><br>If a #RoomHashTag is supplied, users can use it to join the room on their own. If not provided, only individual invitation is allowed.' />";
    $braxsocial = "<img class='icon20' src='$iconsource_braxarrowleft_common' style='padding-top:0;padding-left:10px;padding-right:2px;padding-bottom:0px;' />";
    $braxrooms = "<img class='icon20' src='$iconsource_braxarrowright_common' style='padding-top:0;padding-left:10px;padding-right:2px;padding-bottom:0px;' />";
   //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    /***********************************************
     * 
     * 
     * 
     * 
     *   MODES
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    if( $mode == 'DR'){
        DeleteRoom($roomid);
        echo "<div class='tipbubble friends mainbutton pagetitle3' style='margin:20px;cursor:pointer;background-color:$global_menu_color;color:white'>Room and all members and posts have been deleted. Click to continue.</div>";
        exit();
    }
    
    if( $mode == 'D'){
        DeleteMember($roomid, $providerid, $friendproviderid);
        if($caller == 'room'){
            echo "  
                    <br><br>
                    &nbsp;&nbsp;<span class='tipbubble pagetitle3' style='background-color:$global_menu_color;color:white'>&nbsp;Removed from Room</span> 
                    <br>

                        &nbsp;&nbsp;
                        <div class='mainfont feed tapped'  style='color:black'
                            id='feed' data-roomid='All' data-room='$roomHtml' data-mode=''>
                                $braxsocial
                                $menu_roomselect&nbsp;&nbsp;
                        </div>
                        <br><br>

               ";
            exit();
            
        } else
        if($providerid != $friendproviderid ){
            
            //$roomid = "";
            $friendproviderid = "";
            $mode = "";
            
        } else {
        
            echo "  
                    <br><br>
                    &nbsp;&nbsp;<span class='tipbubble pagetitle3' style='background-color:$global_menu_color;color:white'>&nbsp;Removed from Room</span> 
                    <br>

                        &nbsp;&nbsp;
                        <div class='mainfont feed tapped'  style='color:black'
                            id='feed' data-roomid='All' data-room='$roomHtml' data-mode=''>
                                $braxsocial
                                $menu_roomselect&nbsp;&nbsp;
                        </div>
                        <br><br>

               ";
            exit();
        }
        
    }
    if( $mode == 'M'){ //Adding Member
        if(!AddMember($providerid, $friendproviderid, $roomid )){
            $room = "";
            $roomid = "";
        }
        $mode = "";
    }
    if( $mode == 'MOD'){
        MakeModerator($roomid, $friendproviderid);
        $mode = '';
    }
    if( $mode == 'A'){
    
        $result = pdo_query("1","
            select providername, replyemail from provider where providerid=$providerid
            ");
        if( $row = pdo_fetch($result)){
        
            $ownername =$row['providername'];
            $owneremail =$row['replyemail'];
        }
        
        
        
        $room = $newroom;
        if( $room!=''){
        
            $roomForSql = tvalidator("PURIFY",stripslashes($newroom));
            $roomHtml = htmlentities(stripslashes($newroom),ENT_QUOTES);

            $roomid = 0;
            //Find if the Room already exists
            $result = pdo_query("1","
                select roomid from statusroom where room='$roomForSql' and owner=$providerid
                ");
            if( $row = pdo_fetch($result)){
            
                $roomid = intval($row['roomid']);
            }
        }            
        if($roomid == 0){
        
            $roomid = GetNewID( "ROOM", "ID" );
        }
        /* Let's Error out if Room exists
        else
        {
            $result = pdo_query("1","
                select room from statusroom where roomid=$roomid and owner=$providerid
                ");

            if( $row = pdo_fetch($result))
            {
                $room = $row[room];
                $roomForSql = tvalidator("PURIFY",$room);
                $roomHtml = htmlentities(stripslashes($room),ENT_QUOTES);
            }
        }
         * *
         */
        
        
        if( $room!='' && $roomid > 0){
        
            
            pdo_query("1","
                insert into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                ( $roomid,$providerid, $providerid, '', now(), $providerid )
                ");
            if( $friendproviderid!=''){
            
                $result = pdo_query("1","
                    select providername, replyemail from provider where providerid=$friendproviderid
                    ");
                if( $row = pdo_fetch($result)){
                
                    $friendemail =$row['replyemail'];
                }

                pdo_query("1","
                    insert into statusroom ( roomid, owner, providerid, status, createdate, creatorid ) values
                    ( $roomid,$providerid, $friendproviderid, '',now(),$providerid )
                    ");
            }
            
            
            $error = SaveHandle($roomid, $private, $handle, $room, $roomdescription, 
                    $discover, $tags, $category, $organization, $minage,$photourl, $photourl2,
                    $roomanonymous, $roomexternal, $contactexchange, $adminonly, 
                    $notifications, $showmembers, $soundalert, $sharephotoflag, 
                    $rsscategory, $groupid, $rsssource, $radiostation, $sponsor, 
                    $parent, $childsort, $copymembers, $profileflag, $roominvitehandle, 
                    $webcolorscheme, $webtextcolor, $webpublishprofile, $webflags, $searchengine, 
                    $analytics, $subscriptiondays, $subscription, $subscriptionusd, $wallpaper, $autochatuser, $autochatmsg, $community, $communitylink, $store, $roomstyle );
            $mode = 'F';
            if( $error!=''){
            }
        } else {
            $room = '';
            $roomid = '';
            $mode = '';
        }
        if( $_SESSION['roomuser']=='N' ){
            $_SESSION['roomuser']='1';
        }
    }
    if( $mode == 'R'){

 
        if( $newroom !== '' && intval($roomid)>0){
        
            
            $room = stripslashes($newroom);
            $roomForSql = tvalidator("PURIFY",stripslashes($newroom));
            $roomHtml = htmlentities(stripslashes($newroom),ENT_QUOTES);
            

            /*
            $result = pdo_query("1","
                update statusroom set room='$roomForSql' where roomid = $roomid 
            ");
             * 
             */
            //$result = pdo_query("1","
            //    update statuspost set room='$roomForSql' where roomid = $roomid 
            //");
            
            
            $error = SaveHandle($roomid, $private, $handle, $room, $roomdescription, 
                    $discover, $tags, $category, $organization, $minage,$photourl, $photourl2,
                    $roomanonymous, $roomexternal, $contactexchange, $adminonly, 
                    $notifications, $showmembers, $soundalert, $sharephotoflag, 
                    $rsscategory, $groupid, $rsssource, $radiostation, $sponsor, 
                    $parent, $childsort, $copymembers, $profileflag, $roominvitehandle, 
                    $webcolorscheme, $webtextcolor, $webpublishprofile, $webflags, $searchengine, 
                    $analytics, $subscriptiondays, $subscription, $subscriptionusd, $wallpaper, $autochatuser, $autochatmsg, $community, $communitylink, $store, $roomstyle );
            if( $error!=''){
            
            }
            $mode = 'F';
        }
        
        
    }

    /***********************************************
     * 
     * 
     * 
     * 
     *     Create a New Room
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    
    
    if( $mode == '' && intval($roomid)==0){
    
        $result = pdo_query("1","
            select providername, replyemail from provider where providerid=$providerid
            ");
        if( $row = pdo_fetch($result)){
        
            $ownername =$row['providername'];
            $owneremail =$row['replyemail'];
        }

        //Find if the Room already exists
        $result = pdo_query("1","
            select roomid from statusroom where (room='$roomForSql' and room!='') and owner=$providerid
            ");
        
        $roomid = 0;
        if( $row = pdo_fetch($result)){
        
            $roomid = intval($row['roomid']);
        }
        if($roomid == 0){
        
            if( $room!=''){
            
                $result = pdo_query("1","
                    select max(roomid)+1 as roomid from roominfo
                    ");
                if( $row = pdo_fetch($result)){
                
                    $roomid =intval($row['roomid']);
                }
                pdo_query("1","
                    insert into statusroom ( roomid, room, owner, providerid, status, createdate, creatorid ) values
                    ( $roomid, '$roomForSql',$providerid, $providerid,'', now(), $providerid )
                    ");
            }
        }
        
    }
    
    /***********************************************
     * 
     * 
     * 
     * 
     *   Get Room Data 
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    
    $roomdata = GetRoomData($roomid, $providerid);
    /***********************************************
     * 
     * 
     * 
     * 
     *   Main Room Setup Header 
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    $action = "feed";
    if(intval($_SESSION['profileroomid'])==0){
        $action = "userview";
    }
    echo "
                <span class='roomcontent'>
                    <div class='$action mainbutton gridnoborder' 
                        data-providerid='$providerid' data-roomid='$_SESSION[profileroomid]' data-caller='none'
                        style='cursor:pointer;background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        <img class='icon20' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                        />
                        &nbsp;
                        <span style='opacity:.5'>
                        $icon_braxroom2
                        </span>
                        <span class='pagetitle2a' style='color:white'>$menu_managerooms</span> 
                    </div> 
                </span>
                <div style='background-color:$global_background'>
        ";
    
    
    if( $mode == 'F' ){
        echo "  
                    
                    &nbsp;&nbsp;
                    <div class='mainfont feed tapped'  style='display:inline;cursor:pointer;color:$global_textcolor'
                        id='feed' data-roomid='$roomid'  data-mode=''>
                            $braxsocial
                            $menu_room&nbsp;&nbsp;
                    </div>
                    <!--
                    &nbsp;&nbsp;
                    &nbsp;&nbsp;
                    <div class='mainfont friends tapped'  style='display:inline;cursor:pointer;color:$global_textcolor'
                        id='feed' data-roomid=''  data-mode=''>
                            $menu_managerooms
                            $braxrooms
                    </div>
                    -->
                    <br><br>
                    <div class='pagetitle3 tipbubble bubblesize' style='margin:20px;color:white;background-color:$global_menu_color;margin:auto;text-align:center'>Room Settings Updated</div>
                    <br><br><br>
                    <center>
                        <div class='feed pagetitle3' data-mode='' data-roomid='$roomid' style='cursor:pointer;margin:auto;color:$global_activetextcolor'>View Room</div> 
                            <br>
                        &nbsp;&nbsp;
                        <div class='friends pagetitle3' data-mode='E' data-roomid='$roomid' style='cursor:pointer;margin:auto;color:$global_activetextcolor'>Edit</div> 
                        &nbsp;&nbsp;
                        <div class='friends pagetitle3' data-mode='' data-roomid='$roomid' style='cursor:pointer;margin:auto;color:$global_activetextcolor'><img class='icon15' src='$iconsource_braxarrowleft_common'> Back</div>
                            
                    </center>
                    <br><br>
           ";
        echo "<center><div class='pagetitle2' style='color:$global_textcolor'>$error</div></center>";
        echo "</div>";
        exit();
        
    }
   
    
    
    if(($mode == 'E' || $mode == 'R') && $caller=='friendlist' ){
    
        echo "  
                    
                    &nbsp;&nbsp;
                    <div class='mainfont feed tapped'  style='color:$global_textcolor'
                        id='feed' data-roomid='$roomid'  data-mode=''>
                            $braxsocial
                            $menu_back&nbsp;&nbsp;
                    </div>

           ";
    } else
    if($mode == 'E'  ){
    
        echo "  
                    
                    &nbsp;&nbsp;
                    <div class='mainfont friends tapped'  style='color:$global_textcolor'
                        id='feed' data-roomid='' data-room='' data-mode=''>
                            $braxsocial
                            $menu_back&nbsp;&nbsp;
                    </div>

           ";
    } else
    if(intval($roomid) == 0 && $mode!='N' && $caller=='room'){
    
        echo "  
                    
                    &nbsp;&nbsp;
                    <div class='mainfont feed showtop tapped'  style='color:$global_textcolor'
                        id='feed' data-roomid='All'>
                            $braxsocial
                            $menu_rooms&nbsp;&nbsp;
                    </div>

           ";
    } else
    if(intval($roomid) > 0  && $caller=='friendlist'){
    
        echo "  
                    
                    &nbsp;&nbsp;
                    <div class='mainfont feed showtop tapped' 
                        id='feed' data-roomid='$roomid' data-mode='' style='color:$global_textcolor'>
                            $braxsocial
                            $menu_rooms&nbsp;&nbsp;
                    </div>

           ";
    } else
    if(intval($roomid) == 0 && $mode!='N'){
    
        echo "  
                    &nbsp;&nbsp;
                    
                    <div class='mainfont feed showtop tapped'  style='color:$global_textcolor'
                        id='feed' data-roomid='All'>
                            $braxsocial
                            $menu_roomselect
                            
                    </div>

           ";
    } else
    if(intval($roomid) > 0 || $mode == 'N'){
    
        echo "  
                    &nbsp;&nbsp;
                    
                    <div class='mainfont friends showtop tapped' 
                        id='feed' data-roomid='' style='color:$global_textcolor'>
                            $braxsocial
                            $menu_roomselect
                            
                    </div>

           ";
    }
    
     /***********************************************
     * 
     * 
     * 
     * 
     *   Room Edit Mode
     * 
     * 
     * 
     * 
     * 
     ************************************************/

    
        if($mode == 'E'){
            $roommode = 'R';
        } else {
            $roommode = 'A';
        }
     /***********************************************
     * 
     * 
     * 
     * 
     *   Add/Edit Room Window
     * 
     * 
     * 
     * 
     * 
     ************************************************/
        
        
        $select = ConstructSelect($providerid, $roomid);
        
        // No prior rooms so turn automatically to CREATE NEW mode
        //if( ($_SESSION['roomuser'] == 'N' ||  //No Prior Room Ownership
        //     $_SESSION['roomuser']=='M')){    //No Prior Room Membership
        //    $mode = 'N';
        //}
        if($select == '' && $mode!=''){
            //$mode = 'N';
        }
        if( $mode == 'N' || $mode == 'E'){
        
        
            $roomedit = 
                    RoomEdit($mode, $roomid, $roomdata->roomHtml,$roomdata->roomdesc, $roomdata->organization, 
                            $roomdata->photourl, $roomdata->photourl2, $roommode,
                            $roomdata->private, $roomdata->discover, $roomdata->category,
                            $roomdata->roomanonymous, $roomdata->contactexchange,$roomdata->adminonly, 
                            $roomdata->notifications, $roomdata->showmembers, $roomdata->soundalert,
                            $roomdata->roomhandle, $roomdata->minage, $roomdata->tags, 
                            $roomdata->sharephotoflag, $roomdata->rsscategory, $roomdata->groupid,
                            $roomdata->rsssource, $roomdata->failreason,
                            $caller, $roomdata->radiostation, $roomdata->sponsor, $roomdata->parent, $roomdata->childsort, 
                            $roomdata->profileflag, $roomdata->roomexternal,
                            $roomdata->roominvitehandle, $roomdata->webcolorscheme,  $roomdata->webtextcolor,
                            $roomdata->webpublishprofile, $roomdata->webflags,
                            $roomdata->searchengine, $roomdata->analytics, $roomdata->subscriptiondays, 
                            $roomdata->subscription,$roomdata->subscriptionusd, $roomdata->wallpaper, 
                            $roomdata->autochatuser, $roomdata->autochatmsg, $roomdata->community, $roomdata->communitylink, 
                            $roomdata->store, $roomdata->wizardenterprise, $roomdata->roomstyle
                    );

            echo $roomedit;
            echo "</div>";
            exit();
        }
        
        /*
         * 
         * 
         *    MAIN WINDOW with ROOM LIST
         * 
         * 
         * 
         */
        
        
    
    if( $_SESSION['roomuser']=='N'){
        /*
            echo "
                        <br><br>
                        <center>
                        <div style='padding:20px'>
                            <div class='tipbubble pagetitle2a gridstdborder' style='color:black;background-color:gold;cursor:pointer'>
                                Try Posting a Comment to your Room
                                <img class=feed  data-roomid='$roomid' src='../img/arrowhead-right-128.png' style='height:12px;position:relative;top:0px' />
                            </div>
                        </div>
                        </center>
                ";
         * 
         */
    }
 
    
    

    
    if(intval($roomid) == 0){
    
        echo "
                <div style='width:90%;background-color:$global_background;text-align:center;margin:auto;color:$global_textcolor'>
                    <input id='createroom' name='room' type='hidden' value='' style=';color:$global_textcolor'>
                    <br>

                    <center>
                    <span class=pagetitle2  style='color:$global_textcolor'>
                        $menu_managerooms
                    </span>
                    <br>    
                    <div class=formobile></div>
                    <br>
                    <div class='roomedit tapped'
                         id='roomedit' data-room='' data-roomid='' data-mode='N'
                         style='color:$global_activetextcolor;cursor:pointer;display:inline'>
                         $menu_manageroomscreate
                    </div>
                    <br><br>
                    $select
                    </center>

                    <center>
                    <br>
                    </center>
                </div>
                ";
    }

    if(intval($roomid)==0){
    
        echo "</div>";
        exit();
    }
    $room1 = htmlentities($room,ENT_QUOTES);

    $showroomtip="";
    if( $_SESSION['roomuser']=='1' && intval($roomid)>1){
    
        $showroomtip = '1';
    }
    
    
    $roomshare = "";
    $room = stripslashes($roomdata->room);
    echo "
                        <br>
                        <div class='pagetitle3' style='text-align:center;margin:auto' style='color:black'> 
                            <span class='pagetitle2' style='color:$global_textcolor'>
                            <b>$room</b>
                            </span>
                                <br>
        ";
    if($roomdata->IsOwner){
        echo "
                            <br>
                            <span class='feed pagetitle3' data-mode='' data-roomid='$roomid' data-caller='$caller' style='cursor:pointer'>
                                <span style='color:$global_activetextcolor'>$menu_manageroomsview</span>
                                    <br>
                            </span>
                            <br>
                            <span class='friends pagetitle3' data-mode='E' data-roomid='$roomid' data-room='$room1' data-caller='$caller' style='cursor:pointer'>
                                <span style='color:$global_activetextcolor'>$menu_manageroomsedit</span>
                                    <br>
                            </span>
        ";

        echo  "
                            <br>
                            <span class='roomedit pagetitle3' data-mode='DR' data-roomid='$roomid' data-room='$room1' data-caller='$caller' style='cursor:pointer'>
                                <span style='color:$global_activetextcolor'>Delete Room and Members</span>
                                    <br>
                            </span>
        ";
                            
    
    
    }
    echo "
                            $roomshare
                        </div>
                        <div style='text-align:center'>
                        ";

    /*
    if( $private!='Y'){
        exit();
    }
     * 
     */
    
    echo "
                        <hr>
                        <br>
                        <div style='margin:auto;min-width:300px;text-align:center'>
                            <span class='pagetitle2a' style='color:$global_textcolor'>
                                <b>Add Members - Search Contact List</b>
                            </span>
                        </div>
                        <table style='margin:auto;text-align:left;'  style='color:$global_textcolor'>
                        <tr style='min-width:70%'>
                        <td>
                            <input type='text' class='inputline showhidden friendsearchfilter' placeholder='Contact Search' size=20 style='width:150px;min-width:50%;color:$global_textcolor' />
                            <img class='showhiddenarea friendsearch icon20' src='$iconsource_braxarrowright_common' data-roomid='$roomid' data-caller='$caller' style='display:none' />
                                <br>
                        </td>
                        <td>
                        </td>
                        
                        </tr>
                        </table>
                        
                        <br>
                        <div class='friendsource' style='text-align:center'></div>
            ";
    echo "<br><hr><div class='pagetitle2' style='color:$global_textcolor;padding:20px'>Room Members</div>";
    echo "<input class='inputline showhidden2' id='roommanagefilter' type='text' value='$filter' size=30 placeholder='Search Members' style=';color:$global_textcolor' /> ";
    echo "<img class='showhiddenarea2 friends icon25'  src='$iconsource_braxrefresh_common' style='display:none' data-roomid='$roomid' /><br> ";
    echo "<br>";
    
    $memberlist = MemberList($providerid, $roomid,$filter, $caller);
    
    
    echo "      </div>  
                <br><br><br><span class='smalltext2' style='color:$global_textcolor'>Roomid: 410020$roomid</span>";
    
    echo "</div>";
    



?>