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
            style='background-color:transparent;margin:auto;cursor:pointer;color:white;text-align:center'>
            <br><br>
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
    global $global_textcolor_reverse;
    global $global_activetextcolor;
    global $global_activetextcolor_reverse;
    global $menu_rooms;
    global $menu_discoverrooms;
    global $global_bottombar_color;
    global $appname;
    
        echo "
                <div class='pagetitle3' 
                    style='padding:20px;text-align:center;margin:auto;max-width:300px;width:80%;color:$global_textcolor;background-color:transparent'>
                    <div class='circular3' style=';overflow:hidden;margin:auto'>
                        <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                    </div>
                    <div class='tipbubble pagetitle3' style='padding:30px;color:$global_textcolor_reverse;background-color:$global_bottombar_color'>
                        You are not subscribed to any blogs. <br><br>
                        Tap on 
                        <div class='roomselect' data-mode='TRENDING' style='cursor:pointer;color:$global_activetextcolor'>Trending</div> 
                            to see what $menu_rooms are popular.<br><br>
                        Or<br> 
                        <div class='roomdiscover' data-mode='TRENDING' style='cursor:pointer;color:$global_activetextcolor'>
                            <img class='icon20' src='../img/globe-white-128.png' /> $menu_discoverrooms.
                        </div>
                        <br><br>
                        Tap on the Gear icon on the top right to create your own Blogs.
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
                Blogs - Unread
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
        
        $discoverbutton = "";
    
    $result = pdo_query("1","
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
            where statusroom.providerid= ?
            and ( datediff( now(), roominfo.lastactive)  < 2  )
            and roominfo.room!=''
            and (roominfo.rsscategory is null or roominfo.rsscategory ='')
            and (roominfo.radiostation='' or roominfo.radiostation='Q')
            and roominfo.profileflag !='Y'
            and statusreads.xaccode = 'R'
            and roominfo.external !='Y'
            and (roominfo.adminroom !='Y' or roominfo.adminroom is null)
            order by statusroom.pin desc, statusreads.xaccode desc, roominfo.room asc         
            ",array($providerid));
    //            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid and statuspost.providerid > 0  ) as postcount

    $count = 0;
              
    while($row = pdo_fetch($result)){
        
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
    
    
    
        $photourl = "
                <div class='circular2 icon50' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='../img/familyreunion.jpg' style='height:100%;width:auto;max-width:100%' />
                </div><span class='smalltext'><br></span>
                ";
    
    $result = pdo_query("1","
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
            where statusroom.providerid= ?
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
            ",array($providerid));

    $count = 0;
              
    while($row = pdo_fetch($result)){
        
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
        $background = $global_background.";color:black;";
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";color:white;filter:brightness(120%);";
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

    $result = pdo_query("1","
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
            where statusroom.providerid= ?
            and roominfo.room!='' and roominfo.private='N'
            and roominfo.rsscategory is not null and roominfo.rsscategory !=''
            order by statusreads.xaccode desc, roominfo.room asc         
            ",array($providerid));

    $count = 0;
              
    while($row = pdo_fetch($result)){
        
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
                      style='padding-left:20px;height:40px;max-width:500px;margin:auto;position:relative;display:block;cursor:pointer;
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

    $result = pdo_query("1","
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
            statusroom.owner!=? and
            statusroom.providerid=? and
            statusroom.roomid!=1 and
            roominfo.room!=''
            and 
            ( roominfo.room like ? or 
              roomhandle.handle like ? or
              roominfo.roomdesc like ?
            )
            and roominfo.profileflag !='Y'
            and ( radiostation!='Y' or statusroom.owner = ?)

            order by roominfo.room asc
            ",array($providerid,$providerid,"%".$find."%","%".$find."%","%".$find."%",$providerid));


        //    (select count(*) from statuspost where statuspost.roomid = roominfo.roomid and statuspost.providerid > 0 ) as postcount


        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = pdo_fetch($result)){
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='max-width:300px;padding:20px;margin:auto;color:$global_textcolor;'>ROOMS - MEMBER</div>";
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


            if($row['private']=='Y'){
                $dot .= "<div style='float:left'>$lock</div>";
            }

            $active = "";
            if($row['active']!= '' && $row['active'] < 8){
                $active = "<div style='float:left'><img class='icon15' src='../img/Arrow-Circle_120px.png' style='top:3px;opacity:.3' title='Active this week' /></div>";
            }
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
                  background-color:white;color:black;
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

    $result = pdo_query("1","
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
            statusroom.owner=? 
            and
            statusroom.providerid=? 
            and statusroom.roomid!=1
            and roominfo.room!=''
            and 
            ( roominfo.room like ? or 
              roomhandle.handle like ? or
              roominfo.roomdesc like ?
            )
            and roominfo.profileflag !='Y'
            and ( radiostation!='Y' or statusroom.owner = ? )

            order by roominfo.room asc
            ",array($providerid,$providerid,"%".$find."%","%".$find."%","%".$find."%",$providerid));




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = pdo_fetch($result)){
            
            if($count == 0){
                echo "<br><br><br><div class='pagetitle2'  style='max-width:300px;padding:20px;margin:auto;color:$global_textcolor;'>ROOMS - OWNER</div>";
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
    global $global_menu_color;
    global $global_textcolor;
    global $global_textcolor_reverse;
    global $global_titlebar_color;
    global $global_titlebar_alt_color;
    global $rootserver;
    global $global_activetextcolor;
    global $global_activetextcolor_reverse;
    global $global_background;
    global $menu_myrooms;
    global $menu_rooms;
    global $menu_all;
    global $global_icon_check;
    global $global_icon_lock;
    global $iconsource_braxclose_common;
    
    if($_SESSION['superadmin']!='Y'){
        //return "";
    }
    $circular = "circular";
    $roomtitle = $menu_all." Subscriptions";
    $nonmobile = "nonmobile";
    
    if($_SESSION['roomdiscovery']=='N'){
        $circular = "circular2";
        $roomtitle = "$menu_all Subscriptions";
        $nonmobile = "";
    };
    $ownedquery = "";
    //$ownedquery = "and (roomhandle.community!='Y' or roomhandle.community is null) ";
    if($owned == 'Y' && $_SESSION['enterprise']=='Y'){
        $roomtitle = "$menu_myrooms";
        $ownedquery = " and statusroom.owner = statusroom.providerid ";
    }
    

    $result = pdo_query("1","
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
            roomhandle.community

            from statusroom
            left join roominfo on roominfo.roomid = statusroom.roomid 
            left join provider on provider.providerid = statusroom.owner
            left outer join roomhandle on statusroom.roomid = roomhandle.roomid
            where 
            statusroom.providerid=? 
            and statusroom.roomid!=1
            and roominfo.room!=''
            and 
            ( roominfo.room like ? or 
              roominfo.roomdesc like ? or
              roomhandle.handle like ? or
              (roomhandle.category like ? and roomhandle.category!= 'Private')
               
            )
            and roominfo.profileflag !='Y'
            and ( radiostation!='Y' )
            and 
            (
                roominfo.external = 'N' 
            )
            and roominfo.roomstyle !='faq'
            and (roomhandle.community not in ('Y','F')  or roomhandle.community is null )
            $ownedquery
            order by roominfo.room asc
            ",array($providerid,"%".$find."%","%".$find."%","%".$find."%","%".$find."%"));


        //    (select count(*) from statuspost where statuspost.roomid = roominfo.roomid  and statuspost.providerid > 0 ) as postcount


        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = pdo_fetch($result)){
            
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='padding:20px;max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext ' style='padding-bottom:20px;'></div>";
                
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
            $private = "";
            if("$row[owner]"=="$providerid"){
                //$owned = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
                $owned = $global_icon_check;
            } else {
                //unsubscribe
                $owned = "
                    <span class='friends' style='cursor:pointer'
                        id='deletefriends' 
                        data-providerid='$providerid' data-roomid='$row[roomid]' data-mode='D' data-caller='room' >
                    <img class='icon15 friends tapped' src='$iconsource_braxclose_common' />
                    </span>
                    ";
            }
            if($row['private']=='Y'){
                $private = "$global_icon_lock";
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
                    <div class='gridnoborder' style='width:100%;max-height:70%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img class='circular' src='$row[photourl]' style='max-height:90%;width:100px;height:auto;border-color:transparent;background-color:black;overflow:hidden;max-height:100$' />
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
        $textcolor = 'black';
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $textcolor = 'white';
            //$background = $global_background.";filter:brightness(120%);";
        }

            echo "
                <div class='pagetitle3' 
                  style='padding-left:10px;height:100px;max-width:600px;margin:auto;
                  text-align:left;
                  background-color:$background;color:$global_textcolor;
                  min-width:15%;
                  overflow:hidden'>
                    <table style='padding-top:10px;padding-bottomL10px;margin:0'>
                       <tr>
                            <td style='padding-right:10px'>
                               <span class=feed data-roomid='$row[roomid]'  data-room='$row[room]' data-selectedroom='Y' style='cursor:pointer;color:$global_activetextcolor'>
                               $photourl
                               </span>
                            </td>
                            <td style='vertical-align:top'>
                               <span class=feed data-roomid='$row[roomid]'  data-room='$row[room]' data-selectedroom='Y' style='cursor:pointer;color:$global_activetextcolor'>
                               $row[room]&nbsp;&nbsp;
                               </span>
                               $private $owned<br>
                               <span class='smalltext' >($row[ownername])</span>
                            </td>
                       </tr>
                    </table>
                </div>
                 ";

        }
        if($count == 0 && $find==''){

        }
        if($count > 0){
            echo "<br>$global_icon_check = My Blog&nbsp;&nbsp;&nbsp;&nbsp;$global_icon_lock = Private";
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
    
    

    $result = pdo_query("1","
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
            ",null);




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = pdo_fetch($result)){
            if($row['existing']=='Y'){
                continue;
            }
            if($count == 0){
                echo "<br><br><br><div class='pagetitle2'  style='padding:20px;max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext' style='padding-bottom:10px'></div>";
                
            }
            
            $count++;


            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
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
        $background = $global_background.";color:black;";
        if($icon_darkmode){
            $shadow = "gridnoborder";
            $background = $global_background.";color:white;filter:brightness(120%);";
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
                              $row[room] $owned $private
                              </div>
                          </div>
                    </div>
                     ";

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

    $result = pdo_query("1","
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
            ",null);


        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = pdo_fetch($result)){
            
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
                    <div class='gridnoborder' style='float:left;width:100%;max-height:50%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img src='$row[photourl]' style='width:100%;height:auto;overflow:hidden;max-height:100%' />
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
    

    $result = pdo_query("1","
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
            ",null);




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = pdo_fetch($result)){
            
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='padding:20px;max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
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
        
        $result = pdo_query("1","
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
            roomhandle.public = 'Y'                
            and datediff( now(), roominfo.lastactive ) < 100
            and not exists 
            ( 
                select providerid from statusroom where
                statusroom.roomid = roominfo.roomid and 
                statusroom.providerid = $providerid 
            )
            and ((roominfo.private = 'N' and featured >=0) or featured > 0 )
            and roominfo.radiostation !='Y'
            and roomhandle.community!='Y'
            and (roominfo.rsscategory is null or roominfo.rsscategory = '')
            and (select count(*) from statuspost where roominfo.roomid = statuspost.roomid ) > 1
            order by featured desc, rss, postcount desc, roominfo.room asc limit 30
            ",null);
    } else {
        $result = pdo_query("1","
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
            roomhandle.public = 'Y'                
            and datediff( now(), roominfo.lastactive ) < 100
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
            ",null);        
    }

    
    $lastroomid = '';
    $lastcategory = 'Unspecified';
    $count = 0;
    
    
    while($row = pdo_fetch($result)){
        
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
        $row['photourl']= str_replace("//bytz.io","//brax.me",$row['photourl']);
        if(strstr($row['photourl'],"//bytz.io")===false ){
            $photourl = "
                <div style='width:100%;text-align:center;padding-bottom:10px;overflow:hidden;color:black'>
                   <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
        }
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
                background-color:white;color:black;
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
                    <span class='roomdiscover' style='cursor:pointer' title='Discover Blogs'>
                        <img class='icon30 showhidden' src='$iconsource_braxglobe_common' title='Discover Blogs' />
                    </span>
                </div>
     </center>           
    ";



    
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
    $roomtitle = "Join Community Chats";
    $nonmobile = "nonmobile";
    if($_SESSION['roomdiscovery']=='N'){
        $circular = "circular2";
        $nonmobile = "";
    };
    

        $result = pdo_query("1","
            select distinct roomhandle.roomid, roominfo.room, statusroom.owner,
            statusroom.lastaccess,
            roomhandle.handle,
            roomhandle.public,
            roominfo.roomdesc,
            roominfo.private,
            roominfo.featured,
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
            (select 'Y' from statusroom where providerid = $providerid and roominfo.roomid = statusroom.roomid) as member,
            (select count(*) from statuspost where statuspost.roomid = roominfo.roomid ) as postcount
            from roominfo
            left join statusroom on statusroom.roomid = roominfo.roomid
                and statusroom.owner = statusroom.providerid
            left join roomhandle on roomhandle.roomid = statusroom.roomid 
            where 
            roomhandle.community='Y'
            and (roominfo.rsscategory is null or roominfo.rsscategory = '')
            order by member asc, roominfo.featured desc, lastactive desc, roominfo.room asc 
            ",null);




        $lastroomid = '';
        $lastcategory = 'Unspecified';
        $count = 0;
        while($row = pdo_fetch($result)){
            
            if($count == 0){
                echo "<br><div class='pagetitle2'  style='max-width:300px;margin:auto;color:$global_textcolor;'>$roomtitle</div>";
                echo "<div class='smalltext' style='padding-bottom:10px'></div>";
                
            }
            
            $count++;
            $memberopacity = "1";
            if($row['member']=='Y'){
                $memberopacity = ".3";
            }
            
            if($row['handle']!='') {
                $row['ownername']=$row['handle'];
            }
            $activecolor = 'gray';
            $public = '';
            //if( $row['publicadmin']=='Y') {
            //    $public = "<span style='color:firebrick'> (Public Room)</span>";
            //    $activecolor = 'transparent';
            //}
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
                    <div class='' style='float:left;width:100%;max-height:50%;text-align:center;vertical-align:top;;overflow:hidden;top:0px;'>
                        <img src='$row[photourl]' style='width:100%;height:auto;overflow:hidden;max-height:100%' />
                    </div>
                    ";
        global $icon_darkmode;
        $shadow = "shadow gridstdborder";
        if($icon_darkmode){
            $shadow = "gridnoborder";
        }

                echo "
                      <div class='stdlistbox $shadow roomjoin gridnoborder rounded mainfont' data-roomid='$row[roomid]' 
                        data-room='$row[room]' data-mode='JCOMMUNITY' data-handle='$row[handle]' data-caller='FAQ'
                        style='display:inline-block;cursor:pointer;
                        text-align:center;vertical-align:top;
                        background-color:$global_background;opacity:$memberopacity;
                        min-width:15%;max-width:300px;padding-left:10px;padding:10px;margin:5px'>
                            $photourl
                            <div class='mainfont' style='color:$global_textcolor;max-width:90%;width:200px;word-break:break-word'>
                                <b>$row[room]</b>
                            <br>
                                <span class='smalltext' style='color:$global_textcolor'>$row[roomdesc]</span>
                                    
                            </div>
                      </div>
                     ";

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
    global $icon_darkmode;
    $shadow = "shadow";
    if($icon_darkmode){
        $shadow = "";
    }
    
    
    if($customsite || $roomdiscovery =='N'){
        return "";
    }
    $list = '';
        
        $result = pdo_query("1","
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
            ",null);

    $lastroomid = '';
    $lastcategory = 'Unspecified';
    $count = 0;
    while($row = pdo_fetch($result)){
        
        if($count == 0){
            $list .=
           "<div class='pagetitle2' style='display:inline-block;margin-auto;width:90%;text-align:center;color:$global_textcolor;'>
                $preformat
                Join a Community Chat
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
                <div style='display:inline-block;width:100%;text-align:center;height:50%;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='width:auto;' />
                </div>
                ";
        $roomdesc = limit_words($row['roomdesc'],20);
            
        if($roomdesc == ''){
            $roomdesc = "<br><br>";
        }
        $list .= "
              <div class='roomjoin gridnoborder rounded mainfont' data-roomid='$row[roomid]' 
                data-room='$row[room]' data-mode='JCOMMUNITY' data-handle='$row[handle]'  data-caller='FAQ'
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
    
        $list .=
       "<div class='pagetitle2' style='display:inline-block;margin-auto;width:90%;text-align:center;color:$global_textcolor;'>
            $preformat
            Community FAQ
            <br>
            <br>
        </div>
         $postformat
        ";
    
        $result = pdo_query("1","select photourl,roomid,room from roominfo where roomid = (select roomid from roomhandle where handle = '#privacyfaq') ",null);
        if($row=pdo_fetch($result)){
            
        $photourl = "
                <div style='display:inline-block;width:100%;text-align:center;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
            
           
            $list .= "
                <div class='stdlistbox roomjoin mainbutton tapped2 $shadow' data-handle='#privacyfaq' 
                  data-room=''  data-roomid=$row[roomid] data-mode='J' data-caller='FAQ'
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
                          $row[room]
                            <br>
                                <span class='smalltext' style='color:$global_textcolor'>$row[roomdesc]</span>
                                    
                              
                          </div>
                      </div>
                </div>
                 ";
        }
        $result = pdo_query("1","select photourl,roomid,room from roominfo where roomid = (select roomid from roomhandle where handle = '#techsupport') ",null);
        if($row=pdo_fetch($result)){
            
        $photourl = "
                <div style='display:inline-block;width:100%;text-align:center;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
            
            
        $list .= "
                <div class='stdlistbox roomjoin mainbutton tapped2 $shadow' data-handle='#techsupport' 
                  data-room=''  data-roomid=$row[roomid] data-mode='J'  data-caller='FAQ'
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
                          $row[room]
                            <br>
                                <span class='smalltext' style='color:$global_textcolor'>$row[roomdesc]</span>
                          </div>
                      </div>
                </div>
                 ";
        }
    
    
    if($count == 0){
        $list = "";
    }
        $list = "";

    return $list;


    
}
function FAQRooms($providerid, $find )
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
    $roomtitle = "Community FAQ";
    $nonmobile = "nonmobile";
    if($_SESSION['roomdiscovery']=='N'){
        $circular = "circular2";
        $nonmobile = "";
    };
        global $icon_darkmode;
        $shadow = "shadow";
        if($icon_darkmode){
            $shadow = "";
        }

        echo 
           "<br><br><div class='pagetitle2' style='display:inline-block;margin-auto;width:90%;text-align:center;color:$global_textcolor;'>
                $roomtitle 
                <br>
                <br>
            </div>
            ";
        
        $result = pdo_query("1","select photourl,roomdescshort,roomid,room from roominfo where roomid = (select roomid from roomhandle where handle = '#brax2faq') ",null);
        if($row=pdo_fetch($result)){
            
        $photourl = "
                <div style='display:inline-block;width:100%;text-align:center;background-color:black;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
            
           
            echo "
                <div class='stdlistbox rounded roomjoin mainbutton tapped2 $shadow' data-handle='#brax2faq' 
                  data-room=''  data-roomid=$row[roomid] data-mode='J' data-caller='FAQ'
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
                          $row[room]
                            <br>
                                <span class='smalltext' style='color:black'>$row[roomdescshort]</span>
                          </div>
                      </div>
                </div>
                 ";
        }        
        
        $result = pdo_query("1","select photourl,roomdescshort, roomid,room from roominfo where roomid = (select roomid from roomhandle where handle = '#privacyfaq') ",null);
        if($row=pdo_fetch($result)){
            
        $photourl = "
                <div style='display:inline-block;width:100%;text-align:center;background-color:black;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
            
           
            echo "
                <div class='stdlistbox rounded roomjoin mainbutton tapped2 $shadow' data-handle='#privacyfaq' 
                  data-room=''  data-roomid=$row[roomid] data-mode='J' data-caller='FAQ'
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
                          $row[room]
                            <br>
                                <span class='smalltext' style='color:black'>$row[roomdescshort]</span>
                          </div>
                      </div>
                </div>
                 ";
        }
        
        $result = pdo_query("1","select photourl,roomdescshort, roomid,room from roominfo where roomid = (select roomid from roomhandle where handle = '#bytzvpn') ",null);
        if($row=pdo_fetch($result)){
            
        $photourl = "
                <div style='display:inline-block;width:100%;text-align:center;background-color:black;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
            
            
            echo "
                <div class='stdlistbox rounded roomjoin mainbutton tapped2 $shadow' data-handle='#techsupport' 
                  data-room=''  data-roomid=$row[roomid] data-mode='J'  data-caller='FAQ'
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
                          $row[room]
                            <br>
                                <span class='smalltext' style='color:black'>$row[roomdescshort]</span>
                          </div>
                      </div>
                </div>
                 ";
        }
        
        
        $result = pdo_query("1","select photourl,roomdescshort,roomid,room from roominfo where roomid = (select roomid from roomhandle where handle = '#techsupport') ",null);
        if($row=pdo_fetch($result)){
            
        $photourl = "
                <div style='display:inline-block;width:100%;text-align:center;background-color:black;padding-bottom:10px;overflow:hidden'>
                    <img src='$row[photourl]' style='height:120px;width:auto;max-width:100%' />
                </div>
                ";
            
            
            echo "
                <div class='stdlistbox rounded roomjoin mainbutton tapped2 $shadow' data-handle='#techsupport' 
                  data-room=''  data-roomid=$row[roomid] data-mode='J'  data-caller='FAQ'
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
                          $row[room]
                            <br>
                                <span class='smalltext' style='color:black'>$row[roomdescshort]</span>
                          </div>
                      </div>
                </div>
                 ";
        }
        

        echo "<br><br><br>";
        
}
?>
