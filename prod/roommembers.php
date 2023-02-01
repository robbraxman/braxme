<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("room.inc.php");
require_once("roommanage.inc.php");
require_once("internationalization.php");

    $providerid = tvalidator("ID",$_POST['providerid']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $room = @tvalidator("PURIFY",$_POST['room']);
    $friendproviderid = @tvalidator("PURIFY",$_POST['friendproviderid']);
    $roomid = intval(@tvalidator("ID",$_POST['roomid']));
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $filter = @tvalidator("PURIFY",$_POST['filter']);

    
    
    if( $mode == 'D'){
        DeleteMember($roomid, $providerid, $friendproviderid);

        //$roomid = 0;
        $friendproviderid = "";
        $mode = "";
    }    
    if( $mode == 'M'){ //Adding Member
        if(!AddMember($providerid, $friendproviderid, $roomid )){
            $room = "";
            //$roomid = "";
            $mode = "";
        }
        $friendproviderid = "";
    }
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' style='padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    if($caller == 'room'){
    
        echo "      
                <span class='roomcontent'>
                    <div class='gridstdborder' 
                        data-room='All' data-roomid='All'                
                        style='background-color:transparent;color:$global_textcolor;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        <img class='icon20 feed' Title='Back to Room' data-roomid='$roomid' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                            style='' />
                        &nbsp;
                        <span class='pagetitle2a' style='color:white'>$menu_roommembers</span> 
                    </div>
                </span>
                <div>
                <div style='background-color:white'>
           ";
    } else {
        
        echo "      
                 <span class='roomcontent'>
                    <div class='gridstdborder roomselect' 
                        data-room='All' data-roomid='All'                
                        style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        <span style='opacity:.5'>
                        $icon_braxroom2
                        </span>
                        <span class='pagetitle2a' style='color:white'>$menu_roommembers</span> 
                    </div>
                </span>
               <div>
            
                <div class='mainfont feed showtop tapped'  style='color:black;padding-left:20px'
                    id='feed' data-roomid='$roomid'>
                        $braxsocial
                            $menu_room
                </div>
                    <br>
                    </center>
                 <div style='background-color:white'>
           ";
        
    }
    if($roomid == 'All'){
        echo "<br>Room Deleted";
        exit();
    }
//    echo "<table  class='' style='background-color:transparent;border:0;border-collapse:collapse;width:200px;margin:auto'>
//        ";
    $result = pdo_query("1",
        "
            select distinct provider.replyemail, provider.providername as name, provider.alias,
            provider.providerid, provider.name2, provider.handle, provider.superadmin,
            provider.avatarurl, roominfo.room, statusroom.roomid, roominfo.private,
            (select handle from roomhandle where roomhandle.roomid = statusroom.roomid ) as roomhandle,
            (select roomdesc from roomhandle where roomhandle.roomid = statusroom.roomid ) as roomdesc,
            roommoderator.providerid as moderator, statusroom.owner,
            date_format(statusroom.subscribedate,'%m/%d/%y') as subscribedate, 
            date_format(statusroom.expiredate,'%m/%d/%y') as expiredate, 
            provider.profileroomid
            from statusroom 
            left join provider on statusroom.providerid = provider.providerid
            left join roominfo on statusroom.roomid = roominfo.roomid 
            left join roommoderator on roommoderator.providerid = statusroom.providerid
                and roommoderator.roomid = statusroom.roomid
            where 
            (statusroom.roomid = ?  or ? = '0' )
            and provider.active = 'Y' and statusroom.roomid not in (select roomid from publicrooms )
            and (providername like ? or handle like ? )
            and
            ( statusroom.roomid in
               (select roomid from statusroom s2 where s2.roomid = statusroom.roomid and s2.owner = ? )
              or
              statusroom.roomid in
                (select roomid from roommoderator r2 where r2.roomid = statusroom.roomid and r2.providerid = ? )
            )
               
             order by  statusroom.roomid, name2 asc limit 500
        ",array($roomid,$roomid,"%".$filter."%","%".$filter."%",$providerid,$providerid));
    
    $lastroom = "";
    echo "<div style='padding:0;margin-auto;text-align:center'>";
    echo "<input class='inputline showhidden dataentry' id='roommemberfilter' type='text' value='$filter' size=30 placeholder='$menu_find $menu_handle' style='width:250px' /> ";
    echo "<img class='showhiddenarea roommembers icon25'  src='../img/Refresh_120px.png' style='display:none' data-roomid='$roomid' data-caller='$caller' /><br> ";
    echo "<br>";
    while($row = pdo_fetch($result)){
    
        if($row['avatarurl'] == "$rootserver/img/faceless.png"){
            $row['avatarurl'] = "$rootserver/img/newbie2.jpg";
        }
        if($row['alias']!=''){
        
            //$row['name'] = $row['alias'];
        }
        //if( $row['private']!='Y' && $row['alias']==''){
        if( $row['private']!='Y' ){
        
            if($row['name2']!=''){
                $row['name']=$row['name2'];
            }
            if($row['name2']==''){
            
                //$row['name'] = 'Anonymous';
            }
            if( $row['superadmin']=='Y') {
                $row['name']='Admin';
                $row['avatarurl'] = "$rootserver/img/admin.png";
                $row['handle'] = '';
            }
        }
        $row['name']=substr($row['name'],0,20);
        if( $lastroom == ''){
            $role = "Moderator";
            if( $row['owner']==$providerid){
                $role = "Admin";
            }
            
            
            echo "
                <br>
                <div style='margin:auto;width:200px;text-align:center;vertical-align:top'>
                <span class=pagetitle2a style='color:black'><b>Role: $role</b></span>
                </div>
                ";
            
        }
        
        //if($lastroom !=$row['roomid'] && $row['owner']==$providerid){
        if($lastroom !=$row['roomid']){
        
            echo "
                <br>
                <div class='tooltipcredential' style='margin:auto;width:250px;text-align:center'>
                    <span class='pagetitle2a' style='color:black;'>$row[room]</span>
                    <br>
                    <span class='smalltext' style='color:black;'>$row[roomhandle] </span>
                    <br><br>
                    <div id='addfriends'
                            data-friendproviderid='$providerid' data-roomid='$row[roomid]' data-mode='' data-caller='friendlist'
                            class='friends' style='cursor:pointer'>
                        <span class='mainfont' style='color:$global_activetextcolor'> $menu_manageroom</span>
                    </div>
                </div>
                <br>
                ";
            if(intval($roomid)!=0){
            
                //$shareroom = ShareRoom("friendlist",$providerid, $roomid,"","", false);
                //if($shareroom!=''){
                 //   echo "<br><div style='display:block;position:relative;right:0px;top:0px;text-align:center'>$shareroom</div>";
                //}
            }    
            echo "<br>";
           
        }
        
        $action = "feed";
        if(intval($row['profileroomid'])==0){
            $action = "userview";
        }
        
        echo "
                <div class='roomlistbox gridstdborder rounded shadow' style='display:inline-block;background-color:white;;margin:5px;;padding-bottom:20px;text-align:center;overflow:hidden'>
                    <div style='height:65%;width:100%;background-color:white;overflow:hidden'>
                        <div class='chatlistphoto1' style='overflow:hidden;background-color:white'>
                            <img class='$action' 
                               src='$row[avatarurl]' 
                               data-name='$row[name]'
                               data-providerid='$row[providerid]'
                               data-roomid='$row[profileroomid]'
                               data-mode='S' data-passkey64=''
                               style='width:auto;max-width:100%;min-height:100%;text-align:center;cursor:pointer' />
                        </div>
                    </div>
                    <div class=smalltext style='height:30px;width:100%;margin-top:5px;text-align:center;color:black'>
                        $row[name]<br>$row[handle]<br>

                    </div>
                    <img class='icon20 roommembers' src='../img/delete-circle-128.png' style=''  
                    id='deletefriends' 
                    data-providerid='$row[providerid]' data-roomid='$row[roomid]' data-mode='D' />
            ";
        if($row['moderator']!='' || $row['owner']==$row['providerid']){
            echo "&nbsp; <img class='icon20' src='$iconsource_braxmoderator_common' style='cursor:none' />";
        }
        $expires = "";
        if($row['expiredate']!=''){
            $expires = "Exp: $row[expiredate]";
        }
        if($row['subscribedate']!='' && $row['expiredate']==''){
            $expires = "Subscribed";
        }
        echo "
                    <br><br>
                    <span class='smalltext2'>$expires</span>
                    <br>
                </div>";
            $lastroom = $row['roomid'];
        
        
    }
    echo "</div>";
    //echo "</table>";
//               <br>
//               $row[replyemail]
    
 
    //******************************************
    
    $result = pdo_query("1",
        "
            select provider.replyemail, provider.providername as name,  provider.name2,
            provider.providerid, statusroom.owner, roominfo.private,
            (select distinct providername from provider
            where providerid in 
                (select owner from statusroom s1 where s1.roomid = statusroom.roomid 
                and providerid = owner) limit 1
            )
            as ownername,
            roommoderator.providerid as moderator, 
            provider.avatarurl, roominfo.room, statusroom.roomid, provider.handle, provider.superadmin,
            (select handle from roomhandle where roomhandle.roomid = statusroom.roomid ) as roomhandle,
            provider.alias,
            provider.profileroomid
            from statusroom 
            left join provider on statusroom.providerid = provider.providerid 
            left join roominfo on statusroom.roomid = roominfo.roomid 
            left join roommoderator on roommoderator.providerid = statusroom.providerid
                and roommoderator.roomid = statusroom.roomid
            
            where
            (statusroom.roomid =? or ? = '0')
                and provider.active = 'Y'
            and
            ( statusroom.roomid not in
               (select roomid from statusroom s2 where s2.roomid = statusroom.roomid and s2.owner = $providerid )
              and
              statusroom.roomid not in
                (select roomid from roommoderator r2 where r2.roomid = statusroom.roomid and r2.providerid = $providerid )
            )
            
            order by  statusroom.roomid asc, provider.providername asc limit 500
        ",array($roomid,$roomid));
    
    

    //echo "<table  class='' style='background-color:transparent;border:0;border-collapse:collapse;margin:auto;width:250px'>";
    echo "<div style='padding:0;margin-auto;text-align:center'>";
    
    $lastroom = "";
    while($row = pdo_fetch($result)){
    
        if($row['avatarurl'] == "$rootserver/img/faceless.png"){
            $row['avatarurl'] = "$rootserver/img/newbie2.jpg";
        }
        if($row['alias']!=''){
        
            //$row['name'] = $row['alias'];
        }
        if( $row['private']!='Y' && $row['alias']==''){
        
            $row['name'] = $row['name2'];
            if($row['name2']==''){
                //$row['name']='Anonymous';
            }
                
        }
        if( $row['superadmin']=='Y') {
            $row['name']='Admin';
            $row['avatarurl'] = "$rootserver/img/admin.png";
            $row['handle'] = '';
        }
        if( $row['roomhandle']!=''){
        
            $row['ownername']=$row['roomhandle'];
        }
        
        if( $row['superadmin']=='Y' && $row['roomhandle']!='' ){
        
            //continue;
        }
            
        
        if( $lastroom == ''){
        
            echo "
                
                <div style='margin:auto;width:200px;text-align:center'>
                <span class=pagetitle2a  style='color:black'><b>Role: Member</b></span>
                </div>
               ";
            
        }
        if($lastroom !=$row['roomid']){
        
            echo "
                <div style='margin:auto;width:300px;padding:0;height:20px;text-align:center'>
                    <span class='pagetitle2a' style='color:black;'>$row[ownername] - $row[room] </span>
                </div>
                <br>
               ";
            if(intval($roomid)!=0){
            
                //$shareroom = ShareRoom("friendlist",$roomid,"","","", false);
                //if($shareroom!=''){
                //    echo "<div style='display:block;position:relative;right:0px;top:0px;text-align:center'>$shareroom</div><br>";
                //}
            }    
            echo "<br><br>";
            
        }
        $deleteme = "";
        /*
        if( $row['providerid']==$providerid ){// || $providerid ==$admintestaccount) 
        
            $deleteme =
                    "
                    &nbsp; <img class='icon20' src='../img/delete-circle-white-128.png' style='' class='friendlist' 
                    id='deletefriends' 
                    data-providerid='$row[providerid]' data-roomid='$row[roomid]' data-mode='M' />
                    ";
            
        }
         * 
         */
        $action = "feed";
        if(intval($row['profileroomid'])==0){
            $action = "userview";
        }
        
        echo "
                <div class='roomlistbox gridstdborder rounded shadow' style='display:inline-block;background-color:white;margin:5px;;padding-bottom:20px;text-align:center;overflow:hidden'>
                    <div style='height:65%;width:100%;background-color:white;overflow:hidden'>
                        <div class='chatlistphoto1' style='overflow:hidden;background-color:black'>
                        <img class='$action' 
                            src='$row[avatarurl]' 
                            data-name='$row[name]'
                            data-providerid='$row[providerid]'
                            data-roomid='$row[profileroomid]'
                            data-mode='S' data-passkey64=''
                            style='width:auto;max-width:100%;min-height:100%;text-align:center;cursor:pointer' />
                        </div>
                    </div>
                    <div class='smalltext' style='height:30px;margin-top:5px;color:black'>
                        $row[name]<br>$row[handle]<br>
                    </div>
            ";
        if($row['moderator']!='' || $row['owner']==$row['providerid']){
                echo "<img class='icon20' src='$iconsource_braxmoderator_common' style='cursor:none' />";
        }
        echo "
                    $deleteme
                </div>";
            $lastroom = $row['roomid'];
        
        
    }
    echo "<br><br><img class='icon15' src='$iconsource_braxmoderator_common' style='cursor:none;top:3px' /><span class='smalltext2'> =  Moderator</span><br><br>";
    echo "</div></div></div>";
    
    
   
    
    
?>
