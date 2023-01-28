<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("internationalization.php");
require_once("roomselect.inc.php");


    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("ID",$_POST['providerid']);
    $providerid = $_SESSION['pid'];
    $find = @tvalidator("PURIFY",$_POST['find']);

    if($providerid == ''){
        exit();
    }
    
    $caller = '';
    if(isset($_POST['caller'])){
        $caller = @tvalidator("PURIFY",$_POST['caller']);
    }
    
    
    $mode = '';
    if(isset($_POST['mode'])){
        $mode = @tvalidator("PURIFY",$_POST['mode']);
    }
    
    $room = '';
    if(isset($_POST['room'])){
        $room = @tvalidator("PURIFY",$_POST['room']);
    }
    
    $roomid = '';
    if(isset($_POST['roomid'])){
        $roomid = @tvalidator("ID",$_POST['roomid']);
    }
    
    $postid = '';
    if(isset($_POST['postid'])){
        $postid = tvalidator("PURIFY",$_POST['postid']);
    }
    
    $search = '';
    if(isset($_POST['search'])){
        $search = tvalidator("PURIFY",$_POST['search']);
    }
    
    $category = '';
    if(isset($_POST['category'])){
        $category = tvalidator("PURIFY",$_POST['category']);
    }
    

    $friendproviderid = '';
    if(isset($_POST['friendproviderid'])){
        $friendproviderid = tvalidator("PURIFY",$_POST['friendproviderid']);
    }
    if( $roomid == '') {
        $roomid = 'All';
    }
    
    $call = "feed";
    if($mode == 'P'){
    
        $call = "feedphoto";
    }
    
    $dot = "<img class='unreadicon tapped' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' style='padding-left:0px;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    //echo "<div class='mainfont friends' style='float:right;padding:20px;color:firebrick;cursor:pointer'>Manage Rooms</div> ";
    
    if( $caller == 'select'){
        echo "
                <br>
                <div class='feed gridnoborder' style='cursor:pointer;background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;
                        id='feed' data-roomid='$roomid' data-caller='room';>
                    <img class='icon20'
                        Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                        style='' />
                    &nbsp;
                <span style='opacity:.5'>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                </span>
                    <span class='pagetitle2a' style='color:white'>$menu_discoverrooms</span> 
                </div>
                <div class=appbody style='background-color:transparent;color:$global_textcolor;vertical-align:top'>
            

            ";
    } else {
        echo "
            <br>
            <div class='feed gridnoborder' style='cursor:pointer;background-color:transparent;color:$global_textcolor;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;
                id='feed' data-roomid='0' data-caller='room' >
                <img class='icon20 feed'
                    Title='Back to Home' src='$iconsource_braxarrowleft_common' 
                    style='' />
                &nbsp;
                <span style='opacity:.5'>
                </span>
                <span class='pagetitle2a' style='color:$global_textcolor'>$menu_discoverrooms</span> 
            </div>
            
            <div class=appbody style='background-color:transparent;color:$global_textcolor;vertical-align:top'>
            <br>
            ";
        
    }

    echo "
            <br>
            <div class='feed pagetitle3' style='color:$global_textcolor;display:inline;white-space:nowrap;margin-top:20px;margin-left:10px;'>
                <img class='icon25 showhidden' src='$iconsource_braxfind_common'  title='Find Room' />
                <span class='showhiddenarea' style='display:none'>
                    <input class='inputline dataentry mainfont' id='findroom' placeholder='$menu_find $menu_room' name='findroom' type='text' size=20 value='$find'              
                        style='max-width:120px;padding-left:10px;;margin-bottom:10px;color:$global_textcolor'/>
                    <div class='mainfont roomdiscover' style='white-space:nowrap;display:inline;cursor:pointer;color:black' data-mode='F'>
                        <img class='icon25'   src='$iconsource_braxarrowright_common' 
                        style='top:3px' >
                    </div>
                <span>    
            </div>
            &nbsp;
            <div class='pagetitle3' style='color:$global_textcolor;display:inline;white-space:nowrap;margin-top:20px;margin-left:10px'>
                <span class='showhidden' style='cursor:pointer' title='Join a room using a hashtag'>
                    <img class='icon25 showhidden' src='$iconsource_braxjoin_common' title='Join Room by Hashtag' />
                </span>
                <span class='showhiddenarea' style='display:none'>
                    <input class='inputline dataentry mainfont' id='roomhandle' placeholder='Hashtag' name='roomhandle' type='text' size=20 value=''              
                        style='max-width:120px;margin-bottom:10px;color:$global_textcolor'/>
                    <div class='mainfont roomjoin' style='white-space:nowrap;display:inline;cursor:pointer;color:black'>
                        <img class='icon25 roomjoin' id='roomjoin' data-mode='J' src='$iconsource_braxarrowright_common' 
                        style='top:3px' >
                    </div>
                <span>    
            </div>

            <br>
            ";
    
    
    /*****************************/
    $result = pdo_query("1","
        select roomdiscovery, sponsor from provider where providerid = ? 
        ",array($providerid));
    $roomdiscovery = '';
    if($row = pdo_fetch($result)){
        $roomdiscovery = $row['roomdiscovery'];
        $sponsor = $row['sponsor'];
        if($sponsor == ''){
            $roomdiscovery = 'Y';
        } else {
            //$roomdiscovery = 'N';
            
        }
    }
    echo JoinCommunity($providerid, $roomdiscovery,"<br><br><br>","<br>");

    echo "
         <div class='' style='text-align:center;background-color:transparent;margin:20px'>
         <br>
         ";
    
    global $icon_darkmode;
    $shadow = "shadow gridstdborder";
    $background = $global_background;
    if($icon_darkmode){
        $shadow = "gridnoborder";
        $background = $global_background.";filter:brightness(120%);";
    }

    
    if($roomdiscovery == 'Y'){
    
        
        
        
        $agequery = '';
        if(intval($_SESSION['age'])<=18 ){
            $agequery = " and category not in ('Adult') ";
        }

        $result = pdo_query("1","
                select distinct category from roomhandle 
                where public = 'Y' and category not in ('Private')
                $agequery
                order by category
                ",null);
            echo "
                  <div class='roomdiscover tapped2' data-category=''
                    style='display:inline;cursor:pointer;border:0px solid lightgray;
                    background-color:transparent;
                    width:150px;height:12px;padding:5px;vertical-align:top'>
                        <div class=pagetitle3 
                        style='display:inline-block;color:$global_activetextcolor;
                            vertical-align:top;padding-bottom:15px'>
                            All
                        </div>
                  </div>
                  ";
        while($row = pdo_fetch($result)){



            echo "
                  <div class='roomdiscover tapped2' data-category='$row[category]'
                    style='display:inline;cursor:pointer;border:0px solid lightgray;
                    background-color:transparent;
                    width:150px;height:12px;padding:5px;vertical-align:top'>
                        <div class=smalltext 
                        style='display:inline-block;color:$global_activetextcolor;
                            vertical-align:top;padding-bottom:15px'>
                            $row[category]
                        </div>
                  </div>
                  ";

        }
        echo "
             </div>";
    }
    
    echo "
         <div class='' style='text-align:center;background-color:transparent'>
         <br><br>
         ";

    
    /***********************************************
     * 
     * 
     * 
     * 
     *      Active This Week
     * 
     * 
     * 
     * 
     */
    
    
        
    //if($roomdiscovery == 'Y' || $sponsor == ''){
    if($roomdiscovery == 'Y'){
    
        $result = pdo_query("1","
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, 
            roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=? and 
             statusroom.roomid = roomhandle.roomid ) as existing,
             datediff( now(), roominfo.lastactive) as active,
            roominfo.anonymousflag, roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roomhandle.roomid ) as postcount
            from roomhandle 
            left join roominfo on roominfo.roomid = roomhandle.roomid
            where roomhandle.public = 'Y' and 
            ( roomhandle.tags like '%$search%'  or roomhandle.name like '%$search%' or 
              roomhandle.handle like '%$search%' or roomhandle.roomdesc like '%$search%' 
            ) 
            and roomhandle.category like '%$category%'  and roomhandle.category not in ('Private')
            and datediff( now(), roominfo.lastactive ) < 8 
            and (
              roominfo.roomdesc like '%$find%' or
              roominfo.room like '%$find%' or
              roomhandle.handle like '%$find%'
            )
            and roominfo.private!='Y'
            order by roomhandle.handle asc limit 100
            ",array($providerid));
        
    } else {
        
        $result = pdo_query("1","
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, 
            roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=? and 
             statusroom.roomid = roomhandle.roomid ) as existing,
             datediff( now(), roominfo.lastactive) as active,
            roominfo.anonymousflag, roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roomhandle.roomid ) as postcount
            from roomhandle 
            left join roominfo on roominfo.roomid = roomhandle.roomid
            where roomhandle.public = 'Y' and 
            ( roomhandle.tags like '%$search%'  or roomhandle.name like '%$search%' or 
              roomhandle.handle like '%$search%' or roomhandle.roomdesc like '%$search%' 
            ) 
            and roomhandle.category like '%$category%'  and roomhandle.category not in ('Private')
            and datediff( now(), roominfo.lastactive ) < 8 
            and roominfo.groupid in (select groupid from groupmembers where providerid =? )
            and (
              roominfo.roomdesc like '%$find%' or
              roominfo.room like '%$find%' or
              roomhandle.handle like '%$find%'
            )
            and roominfo.private!='Y'
            
            order by roomhandle.handle asc limit 100
            ",array($providerid,$providerid));
        
    }
    
    
    $lastcategory = "";
    $i1 = 0;
    while($row = pdo_fetch($result)){
        if( $row['existing']=='Y'){
            continue;
        }
        if($i1 == 0){
            echo "<br><div class='pagetitle'  
                    style='max-width:300px;margin:auto;color:$global_textcolor;'>
                        $menu_activetoday
                    </div><br>";
            
        }
        $joined = '';
        if($row['existing']=='Y'){
            $joined = "<img src='../img/check-yellow-128.png' style='height:15px' /><br>";
        }
        $active = "";
        if($row['active']!='' && intval($row['active'])<8){
            $active = "<img src='../img/Yes_120px.png' style='height:15px;opacity:.3' />";
        }
        $anonymousstatus = '';
        if($row['anonymousflag']=='Y'){
            $anonymousstatus = "Anonymous Posts Only<br><br>";
        }
        if($row['photourl']==''){
            $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        }
        
        $photourl = "
                <div style='width:100%;text-align:center;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
        
        $roomdesc = limit_words($row['roomdesc'],12);
        
        
        echo "
              <div class='roomjoin tapped2 gridstdborder $shadow rounded' data-roomid='$row[roomid]' 
                data-room='$row[name]' data-mode='J' data-handle='$row[handle]'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:white;color:black;
                min-width:15%;padding-left:10px;padding:10px;margin:5px'>
                    <b>$row[name]</b>
                    $photourl
                    <div class=smalltext 
                    style='display:inline-block;text-align:center;color:black;
                    '>
                        $active $dot 
                        <span class='smalltext2'>($row[postcount])</span>
                    <div class='smalltext' style='max-width:90%;width:200px;word-break:break-word'>$roomdesc</div>
                    </div>
              </div>
             ";
        
        $i1++;
    }

        
    /***********************************************
     * 
     * 
     * 
     * 
     *      By Category
     * 
     * 
     * 
     * 
     */
    
    if($roomdiscovery == 'Y'){
    
        $result = pdo_query("1","
            
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=? and statusroom.roomid = roomhandle.roomid ) as existing,
             datediff( now(), roominfo.lastactive) as active,
            roominfo.anonymousflag, roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roomhandle.roomid ) as membercount
            from roomhandle 
            left join roominfo on roominfo.roomid = roomhandle.roomid
            where roomhandle.public = 'Y' and roominfo.private!='Y' and
            ( roomhandle.tags like '%$search%'  or roomhandle.name like '%$search%' or roomhandle.handle like '%$search%' or roomhandle.roomdesc like '%$search%' ) 
            and roomhandle.category like '%$category%'  and roomhandle.category not in ('Privatex')
            and (
              roominfo.roomdesc like '%$find%' or
              roominfo.room like '%$find%' or
              roomhandle.handle like '%$find%'
            )

            order by roomhandle.category, roomhandle.rank desc, roomhandle.handle asc 
            ",array($providerid));
        
    } else {
        
        $result = pdo_query("1","
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=? and statusroom.roomid = roomhandle.roomid ) as existing,
             datediff( now(), roominfo.lastactive) as active,
            roominfo.anonymousflag, roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roomhandle.roomid ) as membercount
            from roomhandle 
            left join roominfo on roominfo.roomid = roomhandle.roomid
            left join statusroom on roomhandle.roomid = statusroom.roomid and statusroom.owner = statusroom.providerid 
            where roomhandle.public = 'Y' and roominfo.private!='Y' and 
            ( roomhandle.tags like '%$search%'  or roomhandle.name like '%$search%' or roomhandle.handle like '%$search%' or roomhandle.roomdesc like '%$search%' ) 
            and roomhandle.category like '%$category%'  and roomhandle.category not in ('Privatex')
            and roominfo.groupid in (select groupid from groupmembers where providerid =$providerid)
            and (
              roominfo.roomdesc like '%$find%' or
              roominfo.room like '%$find%' or
              roomhandle.handle like '%$find%'
            )

            order by roomhandle.category, roomhandle.rank desc, roomhandle.handle asc 
            ",array($providerid));
        
    }
    //        and roomhandle.minage <= $_SESSION[age] and roomhandle.public = 'Y' 
        

    
    $lastcategory = "";
    $i1 = 0;
    while($row = pdo_fetch($result)){
        if($row['existing']=='Y' && $_SESSION['superadmin']!='Y'){
            //continue;
        }
        if($row['membercount']==0){
            continue;
        }
        if($i1 == 0){
            
        }
        if($lastcategory != $row['category'] ){
            echo "<br><div class='pagetitle'  
                    style='max-width:300px;margin:auto;color:$global_textcolor;'>
                        $row[category]
                    </div><br>";
        }
        $joined = '';
        if($row['existing']=='Y'){
            $joined = "<img src='../img/check-yellow-128.png' style='height:15px' />";
        }
        $active = "";
        if($row['active']!='' && intval($row['active'])<8){
            $active = "<img src='../img/Yes_120px.png' style='height:15px;opacity:.5' />";
        }
        $anonymousstatus = '';
        $postcount = "(".$row['membercount'].")";
                
        if($row['anonymousflag']=='Y'){
            $anonymousstatus = "Anonymous Posts Only<br><br>";
        }
        
        if($row['photourl']==''){
            $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        }
        $photourl = "
                <div style='width:100%;text-align:center;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";

        $roomdesc = limit_words($row['roomdesc'],20);
        
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }
        
        echo "
              <div class='roomjoin tapped2 gridstdborder $shadow rounded' data-roomid='$row[roomid]' 
                data-mode='J' data-handle='$row[handle]'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:white;color:black;
                min-width:15%;padding-left:10px;padding:10px;margin:5px'>
                    <b>$row[name]</b>
                    $photourl
                    <div class=smalltext 
                    style='display:inline-block;text-align:center;color:black;
                    '>
                        $active $dot 
                        <span class='smalltext2'>$postcount</span>
                    <div class='smalltext' style='max-width:90%;width:200px;word-break:break-word'>$roomdesc</div>
                    </div>
              </div>
             ";
        $lastcategory = $row['category'];
        $i1++;
    }

    
echo "
    </div></div>
    ";    
    
    
?>
