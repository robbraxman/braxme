<?php
    
function RoomListFooter()
{
    global $global_bottombar_color;
    global $global_activetextcolor_reverse;
    global $menu_managerooms;
    global $menu_rooms;
    global $menu_discoverrooms;
    
    if($_SESSION['roomcreator']!='Y' ){
        return;
    }
    
        echo "
        <div class='mainfont' 
            style='background-color:$global_bottombar_color;margin:auto;cursor:pointer;color:white;text-align:center'>
            <br><br>
            <div class='friends' style='color:$global_activetextcolor_reverse'><b>$menu_managerooms</b></div>
            <br><br>
            <br><br><br>
            <br><br><br>
            <br><br><br>
            <br><br><br>
        </div>
        ";
    
}
function NoRooms()
{
    global $global_textcolor;
    global $global_activetextcolor;
    global $menu_rooms;
    global $menu_discoverrooms;
    
        echo "
                <div class='pagetitle3' 
                    style='padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                    <div class='circular3' style=';overflow:hidden;margin:auto'>
                        <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                    </div>
                    <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                        Published content can be found in $menu_rooms.<br><br>
                        Tap on 
                        <div class='roomselect' data-mode='TRENDING' style='cursor:pointer;color:$global_activetextcolor'>Trending</div> 
                            to see what $menu_rooms are popular.<br> 
                        Or 
                        <div class='roomdiscover' data-mode='TRENDING' style='cursor:pointer;color:$global_activetextcolor'>
                            <img class='icon20' src='../img/Globe_120px.png' /> $menu_discoverrooms.
                        </div>
                    </div>
                    <br>
                </div>
                <br><br><br>
        </div>
        ";
}
function limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,0,$word_limit));
}    

function UnreadRooms($providerid)
{
    global $lock;
    global $global_textcolor;
    global $installfolder;
    global $global_background;
    
        $heading =  
             "<br><br><br>
              <div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>
                Rooms - Unread
              </div>";
        //echo "<img class='icon15' src='../img/Speach-Bubble_120px.png' style='top:3px;opacity:.3' title='Legend: active today' /> <span class='smalltext2'>Active Today  </span><br><br>";
    
    
        $photourl = "
                <div class='circular icon50 gridnoborder' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='../img/familyreunion.jpg' style='height:100%;width:auto;max-width:100%' />
                </div><span class='smalltext'><br></span>
                ";
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }
        
        /*
        $discoverbutton = 
            "
            <div class='roomdiscover rounded roomselectbutton tapped2 $shadow' data-roomid='0' 
              data-room='' data-selectedroom='Y' 
              style='display:inline-block;cursor:pointer;
              text-align:left;
              background-color:white;
              min-height:80px;
              min-width:12%;
              padding:10px;margin:5px'>
                  <div class=smalltext 
                  style='float:left;display:inline-block;color:black;
                  '>
                      <b>Discover Rooms</b> 
                  </div><br>
                    $photourl   
                    
            </div>
             ";
    */
        $discoverbutton = "";
    
    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, roominfo.room, provider.providername as ownername, statusroom.owner, 
            roominfo.lastactive as lastaccess,
            roomhandle.handle,
            roomhandle.public,
            roomhandle.category,
            roominfo.organization,
            roominfo.adminroom,
            roominfo.photourl,
            roominfo.private,
            datediff( now(), 
            roominfo.lastactive) as active,
            statusreads.xaccode,
            statusroom.pin
            from statusroom
            left join statusreads on 
                statusroom.roomid = statusreads.roomid and
                statusreads.providerid = statusroom.providerid and statusreads.xaccode = 'R'
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on statusroom.owner = provider.providerid
            where statusroom.providerid= $providerid
            and ( datediff( now(), roominfo.lastactive)  < 2  )
            and roominfo.room!=''
            and (roominfo.rsscategory is null or roominfo.rsscategory ='')
            and (roominfo.radiostation='' or roominfo.radiostation='Q')
            and roominfo.profileflag !='Y'
            and statusreads.xaccode = 'R'
            and roominfo.external !='Y'
            and (roominfo.adminroom !='Y' or roominfo.adminroom is null)
            order by statusroom.pin desc, statusreads.xaccode desc, roominfo.room asc         
            ");
    //            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid and statuspost.providerid > 0  ) as postcount

    $count = 0;
              
    while($row = do_mysqli_fetch("1",$result)){
        
        if($count == 0){
            echo $heading;
            echo $discoverbutton;
            
        }
        if($row['handle']!='') {
            $row['ownername']=$row['handle'];
        }
        $public = '';
        /*
        if( $row['publicadmin']=='Y') {
            $public = "<span style='color:firebrick'> (Public Room)</span>";
        }
         * 
         */
        $activecolor = 'gray';
        if( $row['lastaccess']!=''){
            $activecolor = 'steelblue';
        }

        $active = "";
        if($row['pin'] == '1' ){
            $active = "<div style='float:left;opacity:.3'><img class='icon15' src='../img/pin-line-128.png' style='top:3px' /></div>";
        }
        if($row['xaccode'] == 'R' ){
            $active = "<div style='float:left;opacity:.3'><img class='icon15' src='../img/Speach-Bubble_120px.png' style='top:3px' title='Active today' /></div>";
        }
        
        $dot = "";
        if("$row[owner]"=="$providerid"){
            //$dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
        }
        if($row['private']=='Y'){
            $dot .= "<div style='float:left'>$lock</div>";
        }
        if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
            $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        } else {
            $row['photourl'] = HttpsWrapper($row['photourl']);
        }

        $photourl = "
                <div class='circular icon50 gridnoborder' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                </div><span class='smalltext2'>&nbsp;</span><br>
                ";
        
        echo "
            <div class='feed rounded roomselectbutton tapped2 $shadow' data-roomid='$row[roomid]' 
              data-room='$row[room]' data-selectedroom='Y' 
              style='display:inline-block;cursor:pointer;
              text-align:left;
              background-color:$background;
              min-height:80px;
              min-width:12%;
              padding:10px;margin:5px'>
                  <div class=smalltext 
                  style='float:left;display:inline-block;color:$global_textcolor;
                  '>
                      <b>$row[room]</b> 
                  </div><br>
                    $photourl  $dot 
            </div>
             ";
            
        
        
        
        $count++;
    }
    if($count == 0){
        echo $discoverbutton;
    }

    if($count>0){
        echo "";
    }    
}

function ActiveRooms($providerid)
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_background;
    global $menu_activetoday;
    
        //echo "<img class='icon15' src='../img/Speach-Bubble_120px.png' style='top:3px;opacity:.3' title='Legend: active today' /> <span class='smalltext2'>Active Today  </span><br><br>";
    
    
        $photourl = "
                <div class='circular2 icon50' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='../img/familyreunion.jpg' style='height:100%;width:auto;max-width:100%' />
                </div><span class='smalltext'><br></span>
                ";
        /*
        echo "
            <div class='roomdiscover rounded roomselectbutton tapped2 shadow' data-roomid='0' 
              data-room='' data-selectedroom='Y' 
              style='display:inline-block;cursor:pointer;border:1px solid lightgray;
              text-align:left;
              background-color:white;
              min-height:120px;
              min-width:12%;
              padding:10px;margin:10px'>
                  <div class=mainfont 
                  style='float:left;display:inline-block;color:black;
                  '>
                      <b>Discover Rooms</b> 
                  </div><br>
                    $photourl   
                    
            </div>
             ";
         * 
         */
    
    
    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, roominfo.room, provider.providername as ownername, statusroom.owner, 
            roominfo.lastactive as lastaccess,
            roomhandle.handle,
            roomhandle.public,
            roomhandle.category,
            roominfo.organization,
            roominfo.adminroom,
            roominfo.photourl,
            roominfo.private,
            datediff( now(), 
            roominfo.lastactive) as active,
            statusreads.xaccode,
            statusroom.pin
            from statusroom
            left join statusreads on 
                statusroom.roomid = statusreads.roomid and
                statusreads.providerid = statusroom.providerid and statusreads.xaccode = 'R'
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on statusroom.owner = provider.providerid
            where statusroom.providerid= $providerid
            and ( datediff( now(), roominfo.lastactive)  < 2 or statusroom.pin > 0 )
            and roominfo.room!=''
            and (roominfo.rsscategory is null or roominfo.rsscategory ='')
            and (roominfo.radiostation='' or roominfo.radiostation='Q')
            and roominfo.profileflag !='Y'
            and roominfo.external!='Y'
            and 
            (
                ( statusroom.owner = statusroom.providerid  )
                   or
                ( statusroom.owner != statusroom.providerid and roominfo.external = 'N' )
            )
            and (roominfo.adminroom !='Y' or roominfo.adminroom is null)
            order by statusroom.pin desc, roominfo.room asc         
            ");

    $count = 0;
    //        (select count(*) from statuspost where statuspost.roomid = roominfo.roomid and statuspost.providerid > 0 ) as postcount
              
    while($row = do_mysqli_fetch("1",$result)){
        
        if($count == 0){
            echo "<br>
                  <div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>
                    $menu_activetoday
                  </div>";
            
        }
        if($row['handle']!='') {
            $row['ownername']=$row['handle'];
        }
        $public = '';
        /*
        if( $row['publicadmin']=='Y') {
            $public = "<span style='color:firebrick'> (Public Room)</span>";
        }
         * 
         */
        $activecolor = 'gray';
        if( $row['lastaccess']!=''){
            $activecolor = 'steelblue';
        }

        $active = "";
        if($row['pin'] == '1' ){
            $active = "<div style='float:left;opacity:.3'><img class='icon15' src='../img/pin-line-128.png' style='top:3px' /></div>";
        }
        if($row['xaccode'] == 'R' ){
            $active = "<div style='float:left;opacity:.3'><img class='icon15' src='../img/Speach-Bubble_120px.png' style='top:3px' title='Active today' /></div>";
        }
        
        $dot = "";
        if("$row[owner]"=="$providerid"){
            //$dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
        }
        if($row['private']=='Y'){
            $dot .= "<div style='float:left'>$lock</div>";
        }
        if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
            $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        } else {
            $row['photourl'] = HttpsWrapper($row['photourl']);
        }

        $photourl = "
                <div class='circular icon50 gridnoborder' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                </div><span class='smalltext2'>&nbsp; </span><br>
                ";
        
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }
        echo "
            <div class='feed rounded roomselectbutton tapped2 $shadow' data-roomid='$row[roomid]' 
              data-room='$row[room]' data-selectedroom='Y' 
              style='display:inline-block;cursor:pointer;
              text-align:left;
              background-color:$background;
              min-height:80px;
              min-width:12%;
              padding:10px;margin:5px'>
                  <div class=smalltext 
                  style='float:left;display:inline-block;color:$global_textcolor;
                  '>
                      <b>$row[room]</b> 
                  </div><br>
                    $photourl  $dot 
            </div>
             ";
            
        
        
        
        $count++;
    }

    if($count>0){
        echo "<br><br><br>";
    }    
}

function FeedRooms($providerid )
{
    global $global_activetextcolor;
    /*************************************************************
     * 
     * 
     * 
     * 
     *  FEEDS
     * 
     * 
     * 
     */
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_background;

    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, roominfo.room, provider.providername as ownername, statusroom.owner, 
            roominfo.lastactive as lastaccess,
            roomhandle.handle,
            roomhandle.public,
            roomhandle.category,
            roominfo.organization,
            roominfo.adminroom,
            roominfo.photourl,
            roominfo.private,
            datediff( now(), 
            roominfo.lastactive) as active,
            statusreads.xaccode
            from statusroom
            left join statusreads on 
                statusroom.roomid = statusreads.roomid and
                statusreads.providerid = statusroom.providerid and statusreads.xaccode = 'R'
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on statusroom.owner = provider.providerid
            where statusroom.providerid= $providerid
            and roominfo.room!='' and roominfo.private='N'
            and roominfo.rsscategory is not null and roominfo.rsscategory !=''
            order by statusreads.xaccode desc, roominfo.room asc         
            ");

    $count = 0;
    //        and datediff( now(), roominfo.lastactive)  < 2
    //        (select count(*) from statuspost where statuspost.roomid = roominfo.roomid and statuspost.providerid = 0) as postcount
              
    while($row = do_mysqli_fetch("1",$result)){
        
        if($count == 0){
            echo "<br>
                  <div class='pagetitle2'  style='max-width:300px;margin:auto;color:black;color:$global_textcolor;'>
                    Feeds
                  </div>";
            echo "<br>";
            
        }
        if($row['handle']!='') {
            $row['ownername']=$row['handle'];
        }
        $public = '';
        /*
        if( $row['publicadmin']=='Y') {
            $public = "<span style='color:firebrick'> (Public Room)</span>";
        }
         * 
         */
        $activecolor = 'gray';
        if( $row['lastaccess']!=''){
            $activecolor = 'steelblue';
        }

        $active = "";
        if($row['xaccode'] == 'R' ){
            $active = "<div style='float:left'><img class='icon15' src='../img/Speach-Bubble_120px.png' style='top:3px;opacity:.3' title='Active today' /></div>";
        }
        
        $dot = "";
        if("$row[owner]"=="$providerid"){
            //$dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
        }
        if($row['private']=='Y'){
            $dot .= "<div style='float:left'>$lock</div>";
        }
        if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
            $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        } else {
            $row['photourl'] = HttpsWrapper($row['photourl']);
        }

        $photourl = "
                <div class='circular icon50 gridnoborder' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                </div>
                ";
        /*
        echo "
              <div class='$call tapped2 gridstdborder' data-roomid='$row[roomid]' 
                data-room='$row[room]' data-selectedroom='Y' 
                style='display:inline-block;cursor:pointer;
                text-align:center;
                background-color:whitesmoke;
                margin-bottom:5px;
                width:250px;min-width:25%;padding-left:10px;padding-right:10px;padding-bottom:10px;padding-top:10px'>
                    $photourl
                    <div class=mainfont
                    style='display:inline-block;color:black;
                    '>
                        $row[room] $active $dot 
                    </div>
              </div>
             ";
        */
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }
        
            if( true){
                //test
                echo "
                    <div class='feed pagetitle2a' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='height:40px;max-width:500px;margin:auto;position:relative;display:block;cursor:pointer;
                      text-align:left;
                      background-color:$global_background;color:$global_textcolor;
                      min-width:15%;
                      overflow:hidden'>
                              <div style='padding:10px'>
                              $row[room] 
                              </div>
                    </div>
                     ";
            } else {
        
        
                echo "
                    <div class='feed rounded roomselectbutton tapped2 $shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$background;
                      min-height:80px;
                      min-width:12%;
                      padding:10px;margin:5px'>
                          <div class=smalltext 
                          style='float:left;display:inline-block;color:$global_textcolor;
                          '>
                              <b>$row[room]</b> 
                          </div><br>
                            $photourl  $dot 
                    </div>
                     ";
            }
        
        
        
        $count++;
    }

    if($count>0){
        echo "<br><br>";
    }
    
    
}
function AlphaRooms($providerid, $find )
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;

    if($_SESSION['superadmin']=='Y'){
        return "";
    }

    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, 
            roominfo.room, provider.providername as ownername, statusroom.owner,
            statusroom.lastaccess,
            ( select 'Y' from publicrooms where 
                statusroom.roomid = publicrooms.roomid 
            ) as publicadmin,
            roomhandle.handle,
            roomhandle.public,            
            roominfo.private,
            roominfo.photourl,
            ( select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end
            ) as category,
            roominfo.organization,
            ( select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end
            ) as org,
            datediff( now(), 
            roominfo.lastactive) as active

            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on provider.providerid = statusroom.owner
            where 
            statusroom.owner!=$providerid and
            statusroom.providerid=$providerid and
            statusroom.roomid!=1 and
            roominfo.room!=''
            and 
            ( roominfo.room like '%$find%' or 
              roomhandle.handle like '%$find%' or
              roominfo.roomdesc like '%$find%'
            )
            and roominfo.profileflag !='Y'
            and ( radiostation!='Y' or statusroom.owner = $providerid)

            order by roominfo.room asc
            ");


        //    (select count(*) from statuspost where statuspost.roomid = roominfo.roomid and statuspost.providerid > 0 ) as postcount


        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = do_mysqli_fetch("1",$result)){
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>ROOMS - MEMBER</div>";
                echo "<img class='icon15 rounded' src='../img/Arrow-Circle_120px.png' style='padding:3px;background-color:white;top:3px;opacity:.3' /> <span class='smalltext2'>Active this Week</span>";
                echo "<div class='smalltext' style='padding-bottom:20px;'></div><br>";

            }
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            if( $row['publicadmin']=='Y') {
                $public = "<span style='color:firebrick'> (Public Room)</span>";
                $activecolor = 'transparent';
            }
            if( $row['lastaccess']!=''){
                $activecolor = 'black';
            }
            if( $row['roomid']==$lastroomid)
                continue;

            if( $row['org']=='Y' ){
                $row['category']="$row[organization]";
            }
            if( $row['org']!='Y' && $row['category']=='' ){
                $row['category']='Private';
            }

            if(rtrim($row['organization'])==''){
                $row['organization']=$row['category'];
            }
            $dot = "";


            /*
            if("$row[owner]"=="$providerid"){
                $dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
            }
             * 
             */
            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
            //$row['room']=htmlentities($row['room']);
            $row['room']=rtrim(ltrim($row['room']));


            if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
                $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            } else {
                $row['photourl'] = HttpsWrapper($row['photourl']);
            }
            $photourl = "
                    <div class='circular2 icon50' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                        <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                    </div><span class='smalltext2'>&nbsp; </span><br>
                    ";
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "";
        }

            echo "
                <div class='feed rounded roomselectbutton tapped2 $shadow' data-roomid='$row[roomid]' 
                  data-room='$row[room]' data-selectedroom='Y' 
                  style='display:inline-block;cursor:pointer;
                  text-align:left;
                  background-color:white;
                  min-height:120px;
                  min-width:12%;
                  padding:10px;margin:5px'>
                      <div class=smalltext 
                      style='float:left;display:inline-block;color:black;
                      '>
                          <b>$row[room]</b> 
                      </div><br>
                        $photourl  $dot $active 
                </div>
                 ";

        }
        if($count == 0 && $find==''){
            echo "
                <div class='pagetitle3 tipbubble gridnoborder' style='background-color:$global_titlebar_alt_color;color:white;margin:auto;width:70%;max-width:300px'>You have not joined any rooms</div>
                 ";

        }
        
}
function OwnedRooms($providerid, $find )
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $rootserver;

    if($_SESSION['superadmin']=='Y'){
        return "";
    }

    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, 
            roominfo.room, provider.providername as ownername, statusroom.owner,
            statusroom.lastaccess,
            ( select 'Y' from publicrooms where 
                statusroom.roomid = publicrooms.roomid 
            ) as publicadmin,
            roomhandle.handle,
            roomhandle.public,            
            roominfo.private,
            roominfo.photourl,
            ( select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end
            ) as category,
            roominfo.organization,
            ( select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end
            ) as org,
            datediff( now(), 
            roominfo.lastactive) as active

            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on provider.providerid = statusroom.owner
            where 
            statusroom.owner=$providerid 
            and
            statusroom.providerid=$providerid 
            and statusroom.roomid!=1
            and roominfo.room!=''
            and 
            ( roominfo.room like '%$find%' or 
              roomhandle.handle like '%$find%' or
              roominfo.roomdesc like '%$find%'
            )
            and roominfo.profileflag !='Y'
            and ( radiostation!='Y' or statusroom.owner = $providerid)

            order by roominfo.room asc
            ");


        //    (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount


        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = do_mysqli_fetch("1",$result)){
            
            if($count == 0){
                echo "<br><br><br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>ROOMS - OWNER</div>";
                echo "<img class='icon15 rounded' src='../img/Arrow-Circle_120px.png' style='padding:3px;background-color:white;top:3px;opacity:.3' /> <span class='smalltext2'>Active this Week</span>";
                echo "<div class='smalltext' style='padding-bottom:10px'></div><br>";
                
            }
            
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            if( $row['publicadmin']=='Y') {
                $public = "<span style='color:firebrick'> (Public Room)</span>";
                $activecolor = 'transparent';
            }
            if( $row['lastaccess']!=''){
                $activecolor = 'black';
            }
            if( $row['roomid']==$lastroomid)
                continue;

            if( $row['org']=='Y' ){
                $row['category']="$row[organization]";
            }
            if( $row['org']!='Y' && $row['category']=='' ){
                $row['category']='Private';
            }

            if(rtrim($row['organization'])==''){
                $row['organization']=$row['category'];
            }
            $dot = "";


            /*
            if("$row[owner]"=="$providerid"){
                $dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
            }
             * 
             */
            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
            //$row['room']=htmlentities($row['room']);
            $row['room']=rtrim(ltrim($row['room']));


            if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
                $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            } else {
                $row['photourl'] = HttpsWrapper($row['photourl']);
            }
            $photourl = "
                    <div class='circular2 icon50' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                        <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                    </div><span class='smalltext2'>&nbsp; </span><br>
                    ";
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "";
        }

            echo "
                <div class='feed rounded roomselectbutton tapped2 $shadow' data-roomid='$row[roomid]' 
                  data-room='$row[room]' data-selectedroom='Y' 
                  style='display:inline-block;cursor:pointer;
                  text-align:left;
                  background-color:white;
                  min-height:120px;
                  min-width:12%;
                  padding:10px;margin:5px'>
                      <div class=smalltext 
                      style='float:left;display:inline-block;color:black;
                      '>
                          <b>$row[room]</b> 
                      </div><br>
                        $photourl  $dot $active 
                </div>
                 ";

        }
        if($count == 0 && $find==''){

        }
        
}


function OwnedRooms2($providerid, $find, $owned )
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $rootserver;
    global $global_activetextcolor;
    global $global_background;
    global $menu_myrooms;
    global $menu_rooms;
    global $menu_all;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    $circular = "circular";
    $roomtitle = $menu_all;
    $nonmobile = "nonmobile";
    
    if($_SESSION['roomdiscovery']=='N'){
        $circular = "circular2";
        $roomtitle = "$menu_all";
        $nonmobile = "";
    };
    $ownedquery = "and (roomhandle.community!='Y' or roomhandle.community is null) ";
    if($owned == 'Y' && $_SESSION['enterprise']=='Y'){
        $roomtitle = "$menu_myrooms";
        $ownedquery = " and statusroom.owner = statusroom.providerid and (roomhandle.community!='Y' or roomhandle.community is null) ";
    }
    

    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, 
            roominfo.room, provider.providername as ownername, statusroom.owner,
            statusroom.lastaccess,
            ( select 'Y' from publicrooms where 
                statusroom.roomid = publicrooms.roomid 
            ) as publicadmin,
            roomhandle.handle,
            roomhandle.public,    
            roominfo.external,
            roominfo.private,
            roominfo.photourl,
            ( select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end
            ) as category,
            roominfo.organization,
            ( select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end
            ) as org,
            datediff( now(), 
            roominfo.lastactive) as active

            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on provider.providerid = statusroom.owner
            where 
            statusroom.providerid=$providerid 
            and statusroom.roomid!=1
            and roominfo.room!=''
            and 
            ( roominfo.room like '%$find%' or 
              roomhandle.handle like '%$find%' or
              roominfo.roomdesc like '%$find%' or
              (roomhandle.category like '%$find%' and roomhandle.category!= 'Private')
            )
            and roominfo.profileflag !='Y'
            and ( radiostation!='Y' )
            and 
            (
                roominfo.external = 'N' 
            )
            $ownedquery
            order by roominfo.room asc
            ");


        //    (select count(*) from statuspost where statuspost.roomid = roominfo.roomid  and statuspost.providerid > 0 ) as postcount


        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = do_mysqli_fetch("1",$result)){
            
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext' style='padding-bottom:20px;'></div>";
                
            }
            
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            if( $row['publicadmin']=='Y') {
                $public = "<span style='color:firebrick'> (Public Room)</span>";
                $activecolor = 'transparent';
            }
            if( $row['lastaccess']!=''){
                $activecolor = 'black';
            }
            if( $row['roomid']==$lastroomid)
                continue;

            if( $row['org']=='Y' ){
                $row['category']="$row[organization]";
            }
            if( $row['org']!='Y' && $row['category']=='' ){
                $row['category']='Private';
            }

            if(rtrim($row['organization'])==''){
                $row['organization']=$row['category'];
            }
            $dot = "";

            $owned = "";
            if("$row[owner]"=="$providerid"){
                $owned = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
            }
            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
            //$row['room']=htmlentities($row['room']);
            $row['room']=rtrim(ltrim($row['room']));


            if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
                $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            } else {
                $row['photourl'] = HttpsWrapper($row['photourl']);
            }
            //if($_SESSION['superadmin']=='Y'){
            $photourl = "
                    <div class='gridnoborder' style='float:left;width:100%;max-height:70%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img src='$row[photourl]' style='width:100%;height:auto;overflow:hidden;max-height:100$' />
                    </div>
                    ";
            /*
            } else {
            $photourl = "
                    <span class='$nonmobile'>
                    <div class='$circular' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                        <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                    </div>
                    </span>
                    ";
                
            }
             * 
             */
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }

            if( true ){
                //test
                echo "
                    <div class='feed pagetitle2a' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='height:40px;max-width:500px;margin:auto;position:relative;display:block;cursor:pointer;
                      text-align:left;
                      background-color:$global_background;color:$global_textcolor;
                      min-width:15%;
                      overflow:hidden'>
                              <div style='padding:10px'>
                              $row[room] $owned
                              </div>
                    </div>
                     ";
            } else {
                
                echo "
                    <div class='stdlistbox feed tapped2 $shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='position:relative;display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$background;
                      min-width:15%;
                      margin:5px;overflow:hidden'>
                            $photourl
                          <div class=mainfont 
                          style='position:absolute;bottom:10px;;color:$global_textcolor;
                          '>
                              <div style='padding:10px'>
                              $row[room] $owned
                              </div>
                          </div>
                    </div>
                     ";
                
            }
                 

        }
        if($count == 0 && $find==''){

        }
        if($count > 0){
            echo "<br><br>";
        }
        return $count;
}

function DiscoverRooms($providerid, $find, $roomdiscovery )
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $rootserver;
    global $global_activetextcolor;
    global $global_background;
    global $menu_myrooms;
    global $menu_rooms;
    global $menu_all;
    global $menu_discoverrooms;
    
    //Only if FIND is used
    if($find == ''){
        return;
    }
    if($roomdiscovery == 'N'){
        return;
    }
    $circular = "circular";
    $roomtitle = $menu_discoverrooms;
    $nonmobile = "nonmobile";
    
    

    $result = do_mysqli_query("1","
            select 
            statusroom.roomid, 
            roominfo.room, provider.providername as ownername, statusroom.owner,
            (select 'Y' from statusroom where providerid=$providerid and statusroom.roomid = roominfo.roomid ) as existing,
            roomhandle.handle,
            roomhandle.public,    
            roominfo.external,
            roominfo.private,
            roominfo.photourl,
            ( select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end
            ) as category,
            roominfo.organization,
            ( select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end
            ) as org,
            datediff( now(), 
            roominfo.lastactive) as active,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount

            from roominfo
            left join roomhandle on roomhandle.roomid = roominfo.roomid 
            left join statusroom on statusroom.roomid = roominfo.roomid and statusroom.providerid = statusroom.owner
            left join provider on provider.providerid = statusroom.owner
            where 
            statusroom.providerid = statusroom.owner
            
            and statusroom.roomid!=1
            and roominfo.room!=''
            and 
            ( roominfo.room like '%$find%' or 
              roomhandle.handle like '%$find%' or
              roominfo.roomdesc like '%$find%' or
              provider.providername like '%$find%' or
              (roomhandle.category like '%$find%' and roomhandle.category!= 'Private')
            )
            and roominfo.profileflag !='Y'
            and roominfo.private!='Y'
            and roomhandle.community!='Y'
            and ( radiostation!='Y' )
            and 
            (
                roominfo.external = 'N' 
            )
            order by roominfo.room asc
            ");




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = do_mysqli_fetch("1",$result)){
            if($row['existing']=='Y'){
                continue;
            }
            if($count == 0){
                echo "<br><br><br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext' style='padding-bottom:10px'></div>";
                
            }
            
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            if( $row['publicadmin']=='Y') {
                $public = "<span style='color:firebrick'> (Public Room)</span>";
                $activecolor = 'transparent';
            }
            if( $row['lastaccess']!=''){
                $activecolor = 'black';
            }
            if( $row['roomid']==$lastroomid)
                continue;

            if( $row['org']=='Y' ){
                $row['category']="$row[organization]";
            }
            if( $row['org']!='Y' && $row['category']=='' ){
                $row['category']='Private';
            }

            if(rtrim($row['organization'])==''){
                $row['organization']=$row['category'];
            }
            $dot = "";

            $owned = "";
            if("$row[owner]"=="$providerid"){
                $owned = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
            }
            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
            //$row['room']=htmlentities($row['room']);
            $row['room']=rtrim(ltrim($row['room']));


            if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
                $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            } else {
                $row['photourl'] = HttpsWrapper($row['photourl']);
            }
            //if($_SESSION['superadmin']=='Y'){
            $photourl = "
                    <div class='gridnoborder' style='float:left;width:100%;max-height:70%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img src='$row[photourl]' style='width:100%;height:auto;overflow:hidden;max-height:100$' />
                    </div>
                    ";
            /*
            } else {
            $photourl = "
                    <span class='$nonmobile'>
                    <div class='$circular' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                        <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                    </div>
                    </span>
                    ";
                
            }
             * 
             */
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }

            //if($_SESSION['superadmin']=='Y'){
                //test
                echo "
                    <div class='stdlistbox feed tapped2 $shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='position:relative;display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$background;
                      min-width:15%;
                      margin:5px;overflow:hidden'>
                            $photourl
                          <div class=mainfont 
                          style='position:absolute;bottom:10px;;color:$global_textcolor;
                          '>
                              <div style='padding:10px'>
                              $row[room] $owned
                              </div>
                          </div>
                    </div>
                     ";
            //} else {
                /*
                echo "
                    <div class='gridstdborder feed tapped2 shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$global_background;
                      min-width:15%;
                      padding:10px;margin:5px'>
                          <div class=mainfont 
                          style='float:left;display:inline-block;color:$global_activetextcolor;
                          '>
                            $photourl
                              $row[room] $owned 
                          </div>
                    </div>
                     ";
                
            }
                 */

        }
        if($count == 0 ){

        }
        return $count;
}

function FavoriteRooms($providerid, $find, $owned )
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $rootserver;
    global $global_activetextcolor;
    global $global_background;
    global $menu_myrooms;
    global $menu_rooms;
    global $menu_all;
    global $menu_roomfavorites;
    
    $circular = "circular";
    $nonmobile = "nonmobile";
    
    $roomtitle = "$menu_roomfavorites";

    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, 
            roominfo.room, provider.providername as ownername, statusroom.owner,
            statusroom.lastaccess,
            ( select 'Y' from publicrooms where 
                statusroom.roomid = publicrooms.roomid 
            ) as publicadmin,
            roomhandle.handle,
            roomhandle.public,    
            roominfo.external,
            roominfo.private,
            roominfo.photourl,
            ( select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end
            ) as category,
            roominfo.organization,
            ( select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end
            ) as org,
            datediff( now(), 
            roominfo.lastactive) as active,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount
            
            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on provider.providerid = statusroom.owner
            where statusroom.roomid in (select roomid from roomfavorites where roomfavorites.providerid = statusroom.providerid )
            and
            roominfo.profileflag !='Y'
            and
            statusroom.providerid = $providerid
            order by roominfo.room asc
            ");




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = do_mysqli_fetch("1",$result)){
            
            if($count == 0){
                echo "<br><br><br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext' style='padding-bottom:10px'></div>";
                
            }
            
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            if( $row['publicadmin']=='Y') {
                $public = "<span style='color:firebrick'> (Public Room)</span>";
                $activecolor = 'transparent';
            }
            if( $row['lastaccess']!=''){
                $activecolor = 'black';
            }
            if( $row['roomid']==$lastroomid)
                continue;

            if( $row['org']=='Y' ){
                $row['category']="$row[organization]";
            }
            if( $row['org']!='Y' && $row['category']=='' ){
                $row['category']='Private';
            }

            if(rtrim($row['organization'])==''){
                $row['organization']=$row['category'];
            }
            $dot = "";

            $owned = "";
            if("$row[owner]"=="$providerid"){
                $owned = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
            }
            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
            //$row['room']=htmlentities($row['room']);
            $row['room']=rtrim(ltrim($row['room']));


            if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
                $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            } else {
                $row['photourl'] = HttpsWrapper($row['photourl']);
            }
            //if($_SESSION['superadmin']=='Y'){
            $photourl = "
                    <div class='gridnoborder' style='float:left;width:100%;max-height:70%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img src='$row[photourl]' style='width:100%;height:auto;overflow:hidden;max-height:100$' />
                    </div>
                    ";
            /*
            } else {
            $photourl = "
                    <span class='$nonmobile'>
                    <div class='$circular' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                        <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                    </div>
                    </span>
                    ";
                
            }
             * 
             */
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        $background = $global_background;
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";filter:brightness(120%);";
        }

            //if($_SESSION['superadmin']=='Y'){
                //test
                echo "
                    <div class='stdlistbox feed tapped2 $shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='position:relative;display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$background;
                      min-width:15%;
                      margin:5px;overflow:hidden'>
                            $photourl
                          <div class=mainfont 
                          style='position:absolute;bottom:10px;;color:$global_textcolor;
                          '>
                              <div style='padding:10px'>
                              $row[room] $owned
                              </div>
                          </div>
                    </div>
                     ";
            //} else {
                /*
                echo "
                    <div class='gridstdborder feed tapped2 shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$global_background;
                      min-width:15%;
                      padding:10px;margin:5px'>
                          <div class=mainfont 
                          style='float:left;display:inline-block;color:$global_activetextcolor;
                          '>
                            $photourl
                              $row[room] $owned 
                          </div>
                    </div>
                     ";
                
            }
                 */

        }
        if($count == 0 && $find==''){

        }
        if($count > 0){
            echo "<br><br>";
        }
        
}
function WebsiteRooms($providerid, $find )
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $rootserver;
    global $global_activetextcolor;
    global $global_background;
    global $menu_websites;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    $circular = "circular";
    $roomtitle = $menu_websites;
    $nonmobile = "nonmobile";
    if($_SESSION['roomdiscovery']=='N'){
        $circular = "circular2";
        $nonmobile = "";
    };
    

    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, 
            roominfo.room, provider.providername as ownername, statusroom.owner,
            statusroom.lastaccess,
            ( select 'Y' from publicrooms where 
                statusroom.roomid = publicrooms.roomid 
            ) as publicadmin,
            roomhandle.handle,
            roomhandle.public,    
            roominfo.external,
            roominfo.private,
            roominfo.photourl,
            ( select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end
            ) as category,
            roominfo.organization,
            ( select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end
            ) as org,
            datediff( now(), 
            roominfo.lastactive) as active,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount

            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on provider.providerid = statusroom.owner
            where 
            
            ( statusroom.providerid=$providerid or 
              statusroom.roomid in (select roomid from publicrooms) 
            )
            and statusroom.roomid!=1
            and roominfo.room!=''
            and 
            ( roominfo.room like '%$find%' or 
              roomhandle.handle like '%$find%' or
              roominfo.roomdesc like '%$find%'
            )
            and roominfo.profileflag !='Y'
            and 
            (
                ( statusroom.owner = statusroom.providerid and roominfo.external = 'Y' and provider.enterprise ='Y' )
            )
            order by roominfo.room asc
            ");




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = do_mysqli_fetch("1",$result)){
            
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext' style='padding-bottom:10px'></div>";
                
            }
            
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            if( $row['publicadmin']=='Y') {
                $public = "<span style='color:firebrick'> (Public Room)</span>";
                $activecolor = 'transparent';
            }
            if( $row['lastaccess']!=''){
                $activecolor = 'black';
            }
            if( $row['roomid']==$lastroomid)
                continue;

            if( $row['org']=='Y' ){
                $row['category']="$row[organization]";
            }
            if( $row['org']!='Y' && $row['category']=='' ){
                $row['category']='Private';
            }

            if(rtrim($row['organization'])==''){
                $row['organization']=$row['category'];
            }
            $dot = "";

            $owned = "";
            if("$row[owner]"=="$providerid"){
                $owned = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
            }
            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
            //$row['room']=htmlentities($row['room']);
            $row['room']=rtrim(ltrim($row['room']));


            if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
                $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            } else {
                $row['photourl'] = HttpsWrapper($row['photourl']);
            }
            //if($_SESSION['superadmin']=='Y'){
            $photourl = "
                    <div class='' style='float:left;width:100%;max-height:70%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img src='$row[photourl]' style='width:100%;height:auto;overflow:hidden;max-height:100$' />
                    </div>
                    ";
            /*
            } else {
            $photourl = "
                    <span class='$nonmobile'>
                    <div class='$circular' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                        <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                    </div>
                    </span>
                    ";
                
            }
             * 
             */
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "gridnoborder";
        }

            //if($_SESSION['superadmin']=='Y'){
                //test
                echo "
                    <div class='stdlistbox feed tapped2 $shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='position:relative;display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:whitesmoke;
                      min-width:15%;
                      margin:5px;overflow:hidden'>
                            $photourl
                          <div class=mainfont 
                          style='position:absolute;bottom:10px;;color:black;
                          '>
                              <div style='padding:10px'>
                              $row[room] $owned
                              </div>
                          </div>
                    </div>
                     ";
            //} else {
                /*
                echo "
                    <div class='gridstdborder feed tapped2 shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$global_background;
                      min-width:15%;
                      padding:10px;margin:5px'>
                          <div class=mainfont 
                          style='float:left;display:inline-block;color:$global_activetextcolor;
                          '>
                            $photourl
                              $row[room] $owned 
                          </div>
                    </div>
                     ";
                
            }
                 */

        }
        if($count == 0 && $find==''){

        }
        if($count > 0){
            echo "<br><br>";
        }
        
}

function TrendingRooms($providerid, $roomdiscovery)
{
    global $lock;
    global $rootserver;
    global $global_textcolor;
    global $iconsource_braxglobe_common;
    global $menu_trending;
    global $installfolder;
    
    $result = null;
    
    if($roomdiscovery =='Y'){
        
        $result = do_mysqli_query("1","
            select distinct roomhandle.roomid, roominfo.room, statusroom.owner,
            statusroom.lastaccess,
            roomhandle.handle,
            roomhandle.public,
            roominfo.roomdesc,
            roominfo.private,
            (select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end)
            as category,
            roominfo.organization,
            (select case 
                when roominfo.rsscategory ='' then ''
                when roominfo.rsscategory !='' then 'Y' 
                else '' end)
            as rss,
            (select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end)
            as org,
            datediff( now(), roominfo.lastactive) as active,
            roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount
            from roominfo
            left join statusroom on statusroom.roomid = roominfo.roomid
                and statusroom.owner = statusroom.providerid
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            where 
            roomhandle.minage <= $_SESSION[age]
            and roomhandle.public = 'Y'                
            and datediff( now(), roominfo.lastactive ) < 16
            and not exists 
            ( 
                select providerid from statusroom where
                statusroom.roomid = roominfo.roomid and 
                statusroom.providerid = $providerid 
            )
            and (roominfo.private = 'N' or featured > 0 )
            and roominfo.radiostation !='Y'
            and roomhandle.community!='Y'
            and (roominfo.rsscategory is null or roominfo.rsscategory = '')
            and (select count(*) from statuspost where roominfo.roomid = statuspost.roomid ) > 1
            order by featured desc, rss, postcount desc, roominfo.room asc limit 30
            ");
    } else {
        $result = do_mysqli_query("1","
            select distinct roomhandle.roomid, statusroom.owner,
            statusroom.lastaccess,
            roomhandle.handle,
            roomhandle.public,roominfo.room, 
            roominfo.roomdesc,
            roominfo.private,
            (select case 
                when roominfo.rsscategory ='' then ''
                when roominfo.rsscategory !='' then 'Y' 
                else '' end)
            as rss,
            (select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end)
            as category,
            roominfo.organization,
            (select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end)
            as org,
            datediff( now(), roominfo.lastactive) as active,
            roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount
            from roominfo
            left join statusroom on roominfo.roomid = statusroom.roomid
            left join roomhandle on roomhandle.roomid = roominfo.roomid 
            where 
            roomhandle.minage <= $_SESSION[age]
            and roomhandle.public = 'Y'                
            and datediff( now(), roominfo.lastactive ) < 16
            and not exists 
            ( 
                select providerid from statusroom where
                statusroom.roomid = roominfo.roomid and 
                statusroom.providerid = $providerid 
            )
            and statusroom.owner = statusroom.providerid
            and roominfo.groupid in (select groupid from groupmembers where providerid =$providerid)
            and roominfo.private = 'N'
            and roominfo.radiostation !='Y'
            and roomhandle.community!='Y'
            and (roominfo.rsscategory is null or roominfo.rsscategory = '')
            and (select count(*) from statuspost where roominfo.roomid = statuspost.roomid ) > 1
            order by rss, postcount desc, roominfo.room asc limit 20
            ");        
    }

    
    $lastroomid = '';
    $lastcategory = 'Unspecified';
    $count = 0;
    while($row = do_mysqli_fetch("1",$result)){
        
        if($count == 0){
            echo "<br><br><br><div class='pagetitle2' style='color:$global_textcolor;'>
                    $menu_trending
                 </div><br>
            ";
        }
            
        
        
        if($row['handle']!='') {
            $row['ownername']=$row['handle'];
        }
        $activecolor = 'gray';
        $public = '';
        /*
        if( $row['publicadmin']=='Y') {
            $public = "<span style='color:firebrick'> (Public Room)</span>";
            $activecolor = 'transparent';
        }
         * 
         */
        if( $row['lastaccess']!=''){
            $activecolor = 'steelblue';
        }
        if( $row['roomid']==$lastroomid){
            continue;
        }
        
        if( $row['org']=='Y' ){
            $row['category']="$row[organization]";
        }
        if( $row['org']!='Y' && $row['category']=='' ){
            $row['category']='Private';
        }
        
        if(rtrim($row['organization'])==''){
            $row['organization']=$row['category'];
        }
        $dot = "";
        if("$row[owner]"=="$providerid"){
            $dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
        }

        $active = "";
        if($row['active']!= '' && $row['active'] < 8){
            $active = "<img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' />";
        }
        
        if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
            $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        } else {
            $row['photourl'] = HttpsWrapper($row['photourl']);
        }
        $photourl = "
                <div style='width:100%;text-align:center;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
        $roomdesc = limit_words($row['roomdesc'],20);
            
        if($roomdesc == ''){
            $roomdesc = "<br><br>";
        }
        //$photourl="";
        global $icon_darkmode;
        $shadow = "shadow";
        if($icon_darkmode){
            $shadow = "";
        }
        
        echo "
              <div class='roomjoin tapped2 gridstdborder $shadow rounded' data-roomid='$row[roomid]' 
                data-room='$row[room]' data-mode='J' data-handle='$row[handle]'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:white;
                min-width:15%;padding-left:10px;padding:10px;margin:5px'>
                    <b>$row[room]</b>
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
        $count++;
        
    }

echo "<br><br><br>";    
echo "<center>
                <div class='pagetitle3' style='display:inline;white-space:nowrap;margin-top:20px;margin-left:0px;color:$global_textcolor'>
                    <span class='roomdiscover' style='cursor:pointer' title='Discover Rooms'>
                        <img class='icon30 showhidden' src='$iconsource_braxglobe_common' title='Discover Rooms' />
                    </span>
                </div>
     </center>           
    ";



    
}
function RadioRooms($providerid, $roomdiscovery, $mode)
{
    global $lock;
    global $rootserver;
    global $appname;
    global $global_titlebar_color;
    
    $result = null;
    
    $member = "
            and not exists 
            ( 
                select providerid from statusroom where
                statusroom.roomid = roominfo.roomid and 
                statusroom.providerid = $providerid 
            )
            ";
    $adultfilter = " and roomhandle.handle not like '%adult%'  and roomhandle.handle not like '%test%' ";

    $actionmode = "J";
    $dataaction = "";
    if($mode == 'S'){
        $member = "";
        $actionmode = "R";
        $dataaction = "RADIO";
        //$adultfilter = "";
    }
    
    if($roomdiscovery =='Y'){
        
        $result = do_mysqli_query("1","
            select distinct roomhandle.roomid, roominfo.room, statusroom.owner,
            statusroom.lastaccess,
            roomhandle.handle,
            roomhandle.public,
            roominfo.roomdesc,
            roominfo.private,
            (select 'Y' from statusroom where statusroom.roomid = roominfo.roomid and statusroom.providerid = $providerid) as member,
            (select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end)
            as category,
            roominfo.organization,
            (select case 
                when roominfo.rsscategory ='' then ''
                when roominfo.rsscategory !='' then 'Y' 
                else '' end)
            as rss,
            (select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end)
            as org,
            datediff( now(), roominfo.lastactive) as active,
            roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount
            from roominfo
            left join statusroom on statusroom.roomid = roominfo.roomid
                and statusroom.owner = statusroom.providerid
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            where 
            (roominfo.radiostation = 'Y'  )
            and (roominfo.parentroom = '' or roominfo.parentroom is null)           
            $member
            $adultfilter
            order by radiostation desc, member asc, roomhandle.handle asc
            ");
    } else {
        $result = do_mysqli_query("1","
            select distinct roomhandle.roomid, statusroom.owner,
            statusroom.lastaccess,
            roomhandle.handle,
            roomhandle.public,roominfo.room, 
            roominfo.roomdesc,
            roominfo.private,
            (select 'Y' from statusroom where statusroom.roomid = roominfo.roomid and statusroom.providerid = $providerid) as member,
            (select case 
                when roominfo.rsscategory ='' then ''
                when roominfo.rsscategory !='' then 'Y' 
                else '' end)
            as rss,
            (select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end)
            as category,
            roominfo.organization,
            (select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end)
            as org,
            datediff( now(), roominfo.lastactive) as active,
            roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount
            from roominfo
            left join statusroom on roominfo.roomid = statusroom.roomid
            left join roomhandle on roomhandle.roomid = roominfo.roomid 
            where 
            (roominfo.radiostation = 'Y'  )
            and (roominfo.parentroom = '' or roominfo.parentroom is null)           
            $member
            $adultfilter
            and statusroom.owner = statusroom.providerid
            and roominfo.groupid in (select groupid from groupmembers where providerid =$providerid)
            order by radiostation desc, member asc, roomhandle.handle asc
            ");        
    }

    
    $lastroomid = '';
    $lastcategory = 'Unspecified';
    $count = 0;
    while($row = do_mysqli_fetch("1",$result)){
        
        if($count == 0){
            echo "<div class='pagetitle2a' style='color:#68809f'>
                    <b>JOIN PUBLIC LIVE STREAMING CHANNELS</b>
                 </div>
            ";
            if($mode == 'S'){
            /*
            echo "
                 <div class='smalltext' style='padding:20px;max-width:500px;margin:auto'>
                     Join these channels to access the open live streams. New channels may be added here.
                    <br><br><br>
                    <div class='divbuttontext selectchatlist pagetitle2a gridnoborder' data-mode='LIVE' style='background-color:$global_titlebar_color;color:white;cursor:pointer;border:0'>
                         Back to LIVE Channels
                    </div>
                 </div>
                ";
             * 
             */
            } else {
            echo "
                 <div class='smalltext' style='padding:20px'>
                    Streaming Channels available for you to join.
                 </div>
                ";
                
            }
            echo "<br>";
        } else {
            /*
            echo "
                 <div class='smalltext' style='padding:20px'>
                    No Channels available for you to join.
                 </div>
                ";
            */
        }
            
        
        
        if($row['handle']!='') {
            $row['ownername']=$row['handle'];
        }
        $activecolor = 'gray';
        $public = '';
        /*
        if( $row['publicadmin']=='Y') {
            $public = "<span style='color:firebrick'> (Public Room)</span>";
            $activecolor = 'transparent';
        }
         * 
         */
        if( $row['lastaccess']!=''){
            $activecolor = 'steelblue';
        }
        if( $row['roomid']==$lastroomid){
            continue;
        }
        
        if( $row['org']=='Y' ){
            $row['category']="$row[organization]";
        }
        if( $row['org']!='Y' && $row['category']=='' ){
            $row['category']='Private';
        }
        
        if(rtrim($row['organization'])==''){
            $row['organization']=$row['category'];
        }
        $dot = "";
        if("$row[owner]"=="$providerid"){
            $dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
        }

        $active = "";
        if($row['active']!= '' && $row['active'] < 8){
            $active = "<img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px' />";
        }
        
        if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
            $row['photourl'] = "https://bytz.io/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        } else {
            $row['photourl'] = HttpsWrapper($row['photourl']);
        }
        $photourl = "
                <div style='width:100%;text-align:center;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:80px;width:auto;max-width:100%' />
                </div>
                ";
        $roomdesc = limit_words($row['roomdesc'],20);
            
        if($roomdesc == ''){
            $roomdesc = "<br><br>";
        }
        $chatid = "";
        //$photourl="";
        $insert = "
                    <div class=mainfont 
                    style='display:inline-block;text-align:center;color:black;
                    '>
                    <div class='smalltext' style='max-width:90%;width:200px;word-break:break-word'>$roomdesc</div>
                    </div>
            ";
        $opacity="";
        if($row['member']=='Y'){
            $opacity = "opacity:0.2;";
            $insert = "
                    <div class=mainfont 
                    style='display:inline-block;text-align:center;color:black;
                    '>
                    Joined
                    </div>
                    <br>
            ";
            $action = "";
            $result2 = do_mysqli_query("1","select chatid from chatspawned where roomid='$row[roomid]' ");
            if($row2 = do_mysqli_fetch("1",$result2)){
                $chatid = $row2['chatid'];
            }
            
        } else {
            $action = "roomjoin tapped2";
        }
        global $icon_darkmode;
        $shadow = "gridstdborder shadow";
        if($icon_darkmode){
            $shadow = "";
        }
        
        echo "
              <div class='$action $shadow rounded' data-roomid='$row[roomid]' 
                data-room='$row[room]' data-mode='$actionmode' data-handle='$row[handle]' data-chatid='$chatid' 
                data-action='$dataaction'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:white;$opacity
                min-width:15%;padding-left:10px;padding:10px;margin:5px'>
                    $row[room]
                    $photourl
                    $insert
              </div>
             ";
        $count++;
        
    }

    if($count > 0){
        echo "<br><br><br>";
    } else {
        echo "
             <div class='smalltext' style='padding:20px'>
                No Channels available for you to join.
             </div>
             <br><br><br>
            ";
        
    }
    
    
}


function CommunityRooms($providerid, $find )
{
    global $installfolder;
    global $lock;
    global $global_textcolor;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $rootserver;
    global $global_activetextcolor;
    global $global_background;
    global $menu_websites;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    $circular = "circular";
    $roomtitle = "Communities";
    $nonmobile = "nonmobile";
    if($_SESSION['roomdiscovery']=='N'){
        $circular = "circular2";
        $nonmobile = "";
    };
    

    $result = do_mysqli_query("1","
            select 
            distinct statusroom.roomid, 
            roominfo.room, provider.providername as ownername, statusroom.owner,
            statusroom.lastaccess,
            ( select 'Y' from publicrooms where 
                statusroom.roomid = publicrooms.roomid 
            ) as publicadmin,
            roomhandle.handle,
            roomhandle.public,    
            roominfo.external,
            roominfo.private,
            roominfo.photourl,
            ( select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end
            ) as category,
            roominfo.organization,
            ( select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end
            ) as org,
            datediff( now(), 
            roominfo.lastactive) as active,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount

            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on provider.providerid = statusroom.owner
            where 
            
            ( statusroom.providerid=$providerid or 
              statusroom.roomid in (select roomid from publicrooms) 
            )
            and statusroom.roomid!=1
            and roominfo.room!=''
            and 
            ( roominfo.room like '%$find%' or 
              roomhandle.handle like '%$find%' or
              roominfo.roomdesc like '%$find%'
            )
            and roominfo.profileflag !='Y'
            and 
            (
                ( statusroom.owner = statusroom.providerid and roomhandle.community = 'Y' )
            )
            order by roominfo.room asc
            ");




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = do_mysqli_fetch("1",$result)){
            
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext' style='padding-bottom:10px'></div>";
                
            }
            
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            if( $row['publicadmin']=='Y') {
                $public = "<span style='color:firebrick'> (Public Room)</span>";
                $activecolor = 'transparent';
            }
            if( $row['lastaccess']!=''){
                $activecolor = 'black';
            }
            if( $row['roomid']==$lastroomid)
                continue;

            if( $row['org']=='Y' ){
                $row['category']="$row[organization]";
            }
            if( $row['org']!='Y' && $row['category']=='' ){
                $row['category']='Private';
            }

            if(rtrim($row['organization'])==''){
                $row['organization']=$row['category'];
            }
            $dot = "";

            $owned = "";
            if("$row[owner]"=="$providerid"){
                $owned = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
            }
            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
            //$row['room']=htmlentities($row['room']);
            $row['room']=rtrim(ltrim($row['room']));


            if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
                $row['photourl'] = "$rootserver/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            } else {
                $row['photourl'] = HttpsWrapper($row['photourl']);
            }
            //if($_SESSION['superadmin']=='Y'){
            $photourl = "
                    <div class='' style='float:left;width:100%;max-height:70%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img src='$row[photourl]' style='width:100%;height:auto;overflow:hidden;max-height:100$' />
                    </div>
                    ";
            /*
            } else {
            $photourl = "
                    <span class='$nonmobile'>
                    <div class='$circular' style='text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                        <img src='$row[photourl]' style='height:100%;width:auto;max-width:100%' />
                    </div>
                    </span>
                    ";
                
            }
             * 
             */
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "gridnoborder";
        }

            //if($_SESSION['superadmin']=='Y'){
                //test
                echo "
                    <div class='stdlistbox feed tapped2 $shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='position:relative;display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:whitesmoke;
                      min-width:15%;
                      margin:5px;overflow:hidden'>
                            $photourl
                          <div class=mainfont 
                          style='position:absolute;bottom:10px;;color:black;
                          '>
                              <div style='padding:10px'>
                              $row[room] $owned
                              </div>
                          </div>
                    </div>
                     ";
            //} else {
                /*
                echo "
                    <div class='gridstdborder feed tapped2 shadow' data-roomid='$row[roomid]' 
                      data-room='$row[room]' data-selectedroom='Y' 
                      style='display:inline-block;cursor:pointer;
                      text-align:left;
                      background-color:$global_background;
                      min-width:15%;
                      padding:10px;margin:5px'>
                          <div class=mainfont 
                          style='float:left;display:inline-block;color:$global_activetextcolor;
                          '>
                            $photourl
                              $row[room] $owned 
                          </div>
                    </div>
                     ";
                
            }
                 */

        }
        if($count == 0 && $find==''){

        }
        
}

function JoinCommunity($providerid, $roomdiscovery, $preformat, $postformat)
{
    global $lock;
    global $rootserver;
    global $global_textcolor;
    global $global_background;
    global $global_activetextcolor;
    global $iconsource_braxglobe_common;
    global $menu_trending;
    global $installfolder;
    global $customsite;
    
    $result = null;
    
    if($customsite || $roomdiscovery =='N'){
        return "";
    }
    $list = '';
        
        $result = do_mysqli_query("1","
            select distinct roomhandle.roomid, roominfo.room, statusroom.owner,
            statusroom.lastaccess,
            roomhandle.handle,
            roomhandle.public,
            roominfo.roomdesc,
            roominfo.private,
            (select case  
                when roomhandle.category is null then 'Private' 
                else roomhandle.category end)
            as category,
            roominfo.organization,
            (select case 
                when roominfo.rsscategory ='' then ''
                when roominfo.rsscategory !='' then 'Y' 
                else '' end)
            as rss,
            (select case 
                when roominfo.organization is null then ''
                when roominfo.organization !='' then 'Y' 
                else '' end)
            as org,
            datediff( now(), roominfo.lastactive) as active,
            roominfo.photourl,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount
            from roominfo
            left join statusroom on statusroom.roomid = roominfo.roomid
                and statusroom.owner = statusroom.providerid
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            where 
            not exists 
            ( 
                select providerid from statusroom where
                statusroom.roomid = roominfo.roomid and 
                statusroom.providerid = $providerid 
            )
            and 
            roomhandle.community='Y'
            and (roominfo.rsscategory is null or roominfo.rsscategory = '')
            order by featured desc, roominfo.room asc 
            ");

        /*
            and
            not exists
            ( 
                select joinedvia from provider where providerid=$providerid and joinedvia !=''
            )
*/

    
    $lastroomid = '';
    $lastcategory = 'Unspecified';
    $count = 0;
    while($row = do_mysqli_fetch("1",$result)){
        
        if($count == 0){
            $list .=
           "<div class='pagetitle2' style='display:inline-block;margin-auto;width:90%;text-align:center;color:$global_textcolor;'>
                $preformat
                Meet New People!<br>Join a Community Chat
                <br>
                <br>
            </div>
             $postformat
            ";
        }
            
        
        
        if($row['handle']!='') {
            $row['ownername']=$row['handle'];
        }
        $activecolor = 'gray';
        $public = '';
        /*
        if( $row['publicadmin']=='Y') {
            $public = "<span style='color:firebrick'> (Public Room)</span>";
            $activecolor = 'transparent';
        }
         * 
         */
        if( $row['lastaccess']!=''){
            $activecolor = 'steelblue';
        }
        if( $row['roomid']==$lastroomid){
            continue;
        }
        
        if( $row['org']=='Y' ){
            $row['category']="$row[organization]";
        }
        if( $row['org']!='Y' && $row['category']=='' ){
            $row['category']='Private';
        }
        
        if(rtrim($row['organization'])==''){
            $row['organization']=$row['category'];
        }
        $dot = "";
        if("$row[owner]"=="$providerid"){
            $dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
        }

        $active = "";
        if($row['active']!= '' && $row['active'] < 8){
            $active = "<img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' />";
        }
        
        if($row['photourl']=='' || strstr($row['photourl'],"http://")!==false ) {
            $row['photourl'] = "$rootserver/$installfolder/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
        } else {
            $row['photourl'] = HttpsWrapper($row['photourl']);
        }
        $photourl = "
                <div style='display:inline-block;width:100%;text-align:center;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
        $roomdesc = limit_words($row['roomdesc'],20);
            
        if($roomdesc == ''){
            $roomdesc = "<br><br>";
        }
        //$photourl="";
        global $icon_darkmode;
        $shadow = "shadow";
        if($icon_darkmode){
            $shadow = "";
        }
        
        /*
        $list .= "
              <div class='roomjoin tapped2 gridstdborder $shadow rounded mainfont' data-roomid='$row[roomid]' 
                data-room='$row[room]' data-mode='JCOMMUNITY' data-handle='$row[handle]'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:white;
                min-width:15%;max-width:300px;padding-left:10px;padding:10px;margin:5px'>
                    <b>$row[room]</b>
                    $photourl
                    <div class=smalltext 
                    style='display:inline-block;text-align:center;color:black;
                    '>
                        $active $dot 
                    <div class='mainfont' style='max-width:90%;width:200px;word-break:break-word'>$roomdesc</div>
                    </div>
              </div>
             ";
         * 
         */
        $list .= "
              <div class='roomjoin gridnoborder rounded mainfont' data-roomid='$row[roomid]' 
                data-room='$row[room]' data-mode='JCOMMUNITY' data-handle='$row[handle]'
                style='display:inline-block;cursor:pointer;
                text-align:center;vertical-align:top;
                background-color:$global_background;
                min-width:15%;max-width:300px;padding-left:10px;padding:10px;margin:5px'>
                    $photourl
                    <div class='mainfont' style='color:$global_textcolor;max-width:90%;width:200px;word-break:break-word'>
                        <b>$row[room]</b>
                    </div>
              </div>
             ";
        $count++;
        
    }

    $list .= "<br><br><br>";    
    if($count == 0){
        $list = "";
    }

    return $list;


    
}

?>
