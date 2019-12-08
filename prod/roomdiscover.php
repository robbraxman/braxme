<?php
session_start();
require("validsession.inc.php");
require_once("config.php");
require_once("internationalization.php");
require_once("roomselect.inc.php");


    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = @mysql_safe_string($_POST['providerid']);
    $providerid = $_SESSION['pid'];
    $find = @mysql_safe_string($_POST['find']);

    if($providerid == ''){
        exit();
    }
    
    $caller = '';
    if(isset($_POST['caller'])){
        $caller = @mysql_safe_string($_POST['caller']);
    }
    
    
    $mode = '';
    if(isset($_POST['mode'])){
        $mode = @mysql_safe_string($_POST['mode']);
    }
    
    $room = '';
    if(isset($_POST['room'])){
        $room = @mysql_safe_string($_POST['room']);
    }
    
    $roomid = '';
    if(isset($_POST['roomid'])){
        $roomid = @mysql_safe_string($_POST['roomid']);
    }
    
    $postid = '';
    if(isset($_POST['postid'])){
        $postid = mysql_safe_string($_POST['postid']);
    }
    
    $search = '';
    if(isset($_POST['search'])){
        $search = mysql_safe_string($_POST['search']);
    }
    
    $category = '';
    if(isset($_POST['category'])){
        $category = mysql_safe_string($_POST['category']);
    }
    

    $friendproviderid = '';
    if(isset($_POST['friendproviderid'])){
        $friendproviderid = mysql_safe_string($_POST['friendproviderid']);
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
                <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                    <img class='icon20 feed'
                        id='feed' data-roomid='$roomid' data-caller='room'
                        Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                        style='' />
                    &nbsp;
                <span style='opacity:.5'>
                $icon_braxroom2
                </span>
                    <span class='pagetitle2a' style='color:white'>$menu_discoverrooms</span> 
                </div>
                <div class=appbody style='background-color:transparent;color:$global_textcolor;vertical-align:top'>
            

            ";
    } else {
        echo "
            <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                <img class='icon20 feed'
                    id='feed' data-roomid='0' data-caller='room'
                    Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                    style='' />
                &nbsp;
                <span style='opacity:.5'>
                $icon_braxroom2
                </span>
                <span class='pagetitle2a' style='color:white'>$menu_discoverrooms</span> 
            </div>
            
            <div class=appbody style='background-color:transparent;color:$global_textcolor;vertical-align:top'>
            ";
        
    }

    echo "
            <div class='pagetitle3' style='color:$global_textcolor;display:inline;white-space:nowrap;margin-top:20px;margin-left:10px'>
                <img class='icon30 showhidden' src='$iconsource_braxfind_common'  title='Find Room' />
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
                    <img class='icon30 showhidden' src='$iconsource_braxjoin_common' title='Join Room by Hashtag' />
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
    
            /*
    echo "
            <div class='pagetitle3' style='display:inline;white-space:nowrap;margin-top:20px;margin-left:20px'>
                Join
                <input class='inputline dataentry mainfont' id='roomhandle' placeholder='Hashtag' name='roomhandle' type='text' size=20 value=''              
                    style='max-width:120px;background:url(../img/hash.png) no-repeat scroll;background-size:15px 15px;background-color:transparent;padding-left:20px;margin-bottom:10px'/>
                <div class='mainfont roomjoin' style='white-space:nowrap;display:inline;cursor:pointer;color:black'>
                    <img class='icon20 roomjoin' id='roomjoin' data-mode='J' src='../img/Arrow-Right-in-Circle_120px.png' 
                    style='top:3px' >
                </div>
            </div>
            &nbsp;&nbsp;&nbsp;
            <div class='pagetitle3' style='display:inline;white-space:nowrap;margin-top:20px;margin-left:20px'>
                Find
                <input class='inputline dataentry mainfont' id='findroom' placeholder='Keyword' name='findroom' type='text' size=20 value='$find'              
                    style='max-width:120px;padding-left:10px;;margin-bottom:10px'/>
                <div class='mainfont roomdiscover' style='white-space:nowrap;display:inline;cursor:pointer;color:black' data-mode='F'>
                    <img class='icon20'   src='../img/Arrow-Right-in-Circle_120px.png' 
                    style='top:3px' >
                </div>
            </div>

            <br>
            ";
             * 
             */

    /*
    echo "
         <br><br>
                <div class='pagetitle2' style='white-space:nowrap;margin-top:20px;margin-left:20px'>
                    Join
                    <input class='inputline dataentry mainfont' id='roomhandle' placeholder='Hashtag' name='roomhandle' type='text' size=20 value=''              
                        style='max-width:120px;background:url(../img/hash.png) no-repeat scroll;background-size:15px 15px;background-color:transparent;padding-left:20px;'/>
                        &nbsp;
                    <div class='mainfont roomjoin' style='white-space:nowrap;display:inline;cursor:pointer;color:black'>
                        <img class='icon20 roomjoin' id='roomjoin' data-mode='J' src='../img/Arrow-Right-in-Circle_120px.png' 
                        style='top:3px' >
                    </div>
                </div>
                <div class='pagetitle2' style='white-space:nowrap;margin-top:20px;margin-left:20px'>
                    Find
                    <input class='inputline dataentry mainfont' id='findroom' placeholder='Keyword' name='findroom' type='text' size=20 value='$find'              
                        style='max-width:120px;padding-left:10px;'/>
                        &nbsp;
                    <div class='mainfont roomdiscover' style='white-space:nowrap;display:inline;cursor:pointer;color:black' data-mode='F' data-caller='select' data-roomod='0'>
                        <img class='icon20'   src='../img/Arrow-Right-in-Circle_120px.png' 
                        style='top:3px' >
                    </div>
                </div>
                <br>
         
         <div class='' style='text-align:center;background-color:transparent;margin:20px'>
         <br>
         ";
    */
    
    /*****************************/
    $result = do_mysqli_query("1","
        select roomdiscovery, sponsor from provider where providerid = $providerid 
        ");
    $roomdiscovery = '';
    if($row = do_mysqli_fetch("1",$result)){
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
    
    if($roomdiscovery == 'Y'){
    
        
        
        
        $agequery = '';
        if(intval($_SESSION['age'])<=18 ){
            $agequery = " and category not in ('Adult') ";
        }

        $result = do_mysqli_query("1","
                select distinct category from roomhandle 
                where public = 'Y' and category not in ('Private')
                $agequery
                order by category
                ");
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
        while($row = do_mysqli_fetch("1",$result)){



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
    
        $result = do_mysqli_query("1","
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, 
            roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=$providerid and 
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
            ");
        
    } else {
        
        $result = do_mysqli_query("1","
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, 
            roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=$providerid and 
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
            and roominfo.groupid in (select groupid from groupmembers where providerid =$providerid)
            and (
              roominfo.roomdesc like '%$find%' or
              roominfo.room like '%$find%' or
              roomhandle.handle like '%$find%'
            )
            and roominfo.private!='Y'
            
            order by roomhandle.handle asc limit 100
            ");
        
    }
    
    
    $lastcategory = "";
    $i1 = 0;
    while($row = do_mysqli_fetch("1",$result)){
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
        
        /*
        echo "
              <div class='roomjoin tapped2' data-roomid='$row[roomid]' 
                data-handle='$row[handle]' data-mode='J' 
                style='display:inline;cursor:pointer;border:0px solid lightgray;
                background-color:transparent;
                width:320px;max-width:70%;
                padding-left:10px;padding-right:10px;
                padding-top:10px;
                margin-bottom:10px;vertical-align:top'>
                    <div class=pagetitle3 
                    style='display:inline-block;color:$global_textcolor;
                    vertical-align:top;text-align:center'>
                        <b>$row[name]</b>
                    </div>
                <div class='smalltext2' style='color:$global_textcolor;max-width:300px;text-align:center;margin:auto'>
                    $roomdesc
                </div>
                <div class=mainfont style='color:$global_textcolor'>$row[handle]</div>
                ";
        if($active !="" || $joined !=""){
            echo "  <div class='mainfont' style='color:$global_textcolor'>$active $joined</div>";
        }
        if($anonymousstatus!=""){
            echo "  <span class='smalltext2' style='color:$global_textcolor'>$anonymousstatus</span>";
        }
        echo "
                <br>
              </div>
             ";
         * 
         */
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }
        
        echo "
              <div class='roomjoin tapped2 gridstdborder $shadow rounded' data-roomid='$row[roomid]' 
                data-room='$row[name]' data-mode='J' data-handle='$row[handle]'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:white;
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
    
        $result = do_mysqli_query("1","
            
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=$providerid and statusroom.roomid = roomhandle.roomid ) as existing,
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
            ");
        
    } else {
        
        $result = do_mysqli_query("1","
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, roomhandle.name, roomhandle.category,
            (select 'Y' from statusroom where providerid=$providerid and statusroom.roomid = roomhandle.roomid ) as existing,
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
            ");
        
    }
    //        and roomhandle.minage <= $_SESSION[age] and roomhandle.public = 'Y' 
        

    
    $lastcategory = "";
    $i1 = 0;
    while($row = do_mysqli_fetch("1",$result)){
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
        
        /*
        echo "
              <div class='rounded roomjoin tapped2 gridstdborder shadow' data-roomid='$row[roomid]' 
                data-handle='$row[handle]' data-mode='J' 
                style='display:inline-block;cursor:pointer;
                background-color:white;
                width:320px;max-width:70%;
                padding-left:10px;padding-right:10px;
                padding-top:10px;margin:5px;
                vertical-align:top'>
                $photourl
                  <div class=pagetitle3 
                    style='display:inline-block;color:black;
                    vertical-align:top;text-align:center'>
                        <b>$row[name] $membercount</b>
                    </div>
                <div class='smalltext2' style='max-width:70%;text-align:center;margin:auto'>
                    $roomdesc
                </div>
                <div class=mainfont style='color:black'>$row[handle]</div>";
        if($active !="" || $joined !=""){
            echo "  <span class='mainfont' style='color:black'>$active $joined<br></span>";
        }
        if($anonymousstatus!=""){
            echo "  <span class='smalltext2' style='color:steelblue'>$anonymousstatus</span>";
        }
        echo "
                <br>
              </div>
             ";
         * 
         */
        echo "
              <div class='roomjoin tapped2 gridstdborder $shadow rounded' data-roomid='$row[roomid]' 
                data-mode='J' data-handle='$row[handle]'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:white;
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
