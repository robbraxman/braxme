<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("room.inc.php");

    $providerid = tvalidator("ID",$_POST['providerid']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $room = @tvalidator("PURIFY",$_POST['room']);
    $friendproviderid = @tvalidator("PURIFY",$_POST['friendproviderid']);
    $roomid = intval(@tvalidator("ID",$_POST['roomid']));
    $caller = @tvalidator("PURIFY",$_POST['caller']);

    
    
    if( $mode == 'D')
    {
        //Delete from statusroom if owner is inactive
        $result = pdo_query("1","
            delete from statusroom where roomid=$roomid and owner=$providerid and
            providerid not in (select providerid from provider where
            statusroom.providerid = provider.providerid and active='Y')
            ");

        $result = pdo_query("1","
            select count(*) as total from statusroom where roomid=$roomid  
            ");
        $row = pdo_fetch($result);
        $membercount = $row['total'];
        
        if($membercount > 1)
        {
            //delete user but not owner
            pdo_query("1","
                delete from statusroom where roomid=$roomid and 
                providerid = $friendproviderid and providerid!=owner
                and 
                ( owner = $providerid 
                  or
                  roomid in
                    (select roomid from roommoderator r2 where r2.roomid=$roomid and r2.providerid =$providerid)
                )
                ");
        }
        else
        {
            //delete user even of owner
            pdo_query("1","
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
        
        $result = pdo_query("1","
            select count(*) as total from statusroom where roomid=$roomid  
            ");
        $row = pdo_fetch($result);
        $membercount = $row['total'];
        
        
        if( $membercount == 0) //no more users so delete room and its contents
        {
            pdo_query("1","
                delete from statusroom where roomid=$roomid and owner=$providerid 
                ");
            pdo_query("1","
                delete from roominfo where roomid=$roomid  
                ");
            pdo_query("1","
                delete from roomhandle where roomid=$roomid  
                ");
            $result = pdo_query("1","
                delete from statuspost where roomid=$roomid and providerid=$providerid 
                ");
            $result = pdo_query("1","
                delete from statusreads where roomid=$roomid and providerid=$providerid 
                ");
            $result = pdo_query("1","
                delete from credentials where roomid=$roomid
                ");
            $result = pdo_query("1","
                delete from credentialrequest where roomid=$roomid 
                ");
            $result = pdo_query("1","
                delete from events where roomid=$roomid 
                ");
            $result = pdo_query("1","
                delete from tasks where roomid=$roomid
                ");
            $result = pdo_query("1","
                delete from tasksaction where roomid=$roomid 
                ");
            $result = pdo_query("1","
                delete from roomwebstyle where roomid=$roomid 
                ");
            $result = pdo_query("1","
                delete from roomfiles where roomid=$roomid 
                ");
            $result = pdo_query("1","
                delete from roomfilefolders where roomid=$roomid 
                ");
            $result = pdo_query("1","
                delete from roominvite where roomid=$roomid 
                ");
            $result = pdo_query("1","
                delete from roommoderator where roomid=$roomid 
                ");
            $roomid = 0;
            $friendproviderid = "";
        }
        
        
        
    }
    if( $mode == 'M')
    {
        
            pdo_query("1","
                delete from statusroom where roomid='$roomid' and owner!=$providerid and
                providerid = $friendproviderid and roomid not in (select roomid from publicrooms)
                ");
        
            pdo_query("1","
                update invites set status='N' where roomid='$roomid' and email in (select replyemail from 
                provider where providerid = $providerid )
                ");
            $roomid = "All";
            $friendproviderid = "";
    }
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img class='icon20' src='../img/arrow-stem-circle-left-128.png' style='padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    if($caller == 'room')
    {
        echo "      
                <div>
                <center>
                <span class='pagetitle' style='color:black'>Room Members</span>
                <br><br>
                <div class='mainfont feed showtop tapped'  style='color:black'
                    id='feed' data-roomid='$roomid'>
                        $braxsocial
                            Room&nbsp;&nbsp;
                </div>
                    <br><br>
                    </center>
                 <div style='background-color:#E5E5E5'>
           ";
    }
    else
    {
        echo "      
                <div>
                <center>
                <span class='pagetitle' style='color:black'>Room Members</span>
                <br><br>
            
                <div class='mainfont roommanage showtop tapped'  style='color:black'
                    id='feed' data-roomid='$roomid'>
                        $braxsocial
                            Room
                        $braxsocial
                            Manage Room&nbsp;&nbsp;
                </div>
                    <br><br>
                    </center>
                 <div style='background-color:#E5E5E5'>
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
            provider.avatarurl, room, roomid,
            (select handle from roomhandle where roomhandle.roomid = statusroom.roomid ) as roomhandle,
            (select roomdesc from roominfo where roominfo.roomid = statusroom.roomid ) as roomdesc
            from statusroom 
            left join provider on statusroom.providerid = provider.providerid
            where statusroom.owner = $providerid
            and (roomid = $roomid  or '$roomid' = '0' )
            and provider.active = 'Y' and roomid not in (select roomid from publicrooms )
             order by  roomid, name asc
        ");
    
    $lastroom = "";
    echo "<div style='padding:0;margin-auto;text-align:center'>";
    while($row = pdo_fetch($result))
    {
        if($row['avatarurl'] == "$rootserver/img/faceless.png"){
            $row['avatarurl'] = "$rootserver/img/newbie2.jpg";
        }
        if($row['alias']!='')
        {
            //$row['name'] = $row['alias'];
        }
        if( $row['roomhandle']!='' && $row['alias']=='')
        {
            if($row['name2']!=''){
                $row['name']=$row['name2'];
            }
            if($row['name2']=='')
            {
                $row['name'] = 'Anonymous';
            }
            if( $row['superadmin']=='Y') {
                $row['name']='Admin';
            }
        }
        if( $lastroom == '')
        {
            echo "
                <br>
                <div style='margin:auto;width:200px;text-align:center;vertical-align:top'>
                <span class=pagetitle2 style='color:black'>Role: Moderator</span>
                </div>
                ";
            
        }
        
        if($lastroom !=$row['roomid'])
        {
            echo "
                <br>
                <hr>
                <div class='tooltipcredential' style='margin:auto;width:250px;text-align:center'>
                    <span class='pagetitle2a' style='color:black;'>$row[room]</span>
                    <br>
                    <span class='smalltext' style='color:black;'>$row[roomhandle] </span>
                    <span class='smalltext' style='color:black;'>$row[roomdesc]</span>
                    <br><br>
                        <img 
                            id='addfriends' 
                            data-friendproviderid='$row[providerid]' data-roomid='$row[roomid]' 
                            data-mode='' data-caller='friendlist'
                            class='friends icon25' src='../img/add-circle-128.png' style='' />
                </div>
                <br>
                ";
            if(intval($roomid)!=0)
            {
                $shareroom = ShareRoom("friendlist",$providerid, $roomid,"","", false);
                if($shareroom!=''){
                    echo "<br><div style='display:block;position:relative;right:0px;top:0px;text-align:center'>$shareroom</div><br>";
                }
            }    
            echo "<br><br>";
            
        }
        
        
        echo "
                <div class='chatlistbox gridstdborder' style='display:inline-block;background-color:whitesmoke;margin:auto;;padding-bottom:20px;text-align:center;overflow:hidden'>
                    <div style='height:50%;width:100%;background-color:#404040'>
                    <img class='chatlistphoto1' src='$row[avatarurl]' style='width:auto;max-width:100%;text-align:center' />
                    </div>
                    <div class=smalltext style='height:30px;width:100%;text-align:center'>
                    $row[name]
                    </div>
                    <img class='icon20 friends' src='../img/delete-circle-128.png' style=''  
                    id='deletefriends' 
                    data-providerid='$row[providerid]' data-roomid='$row[roomid]' data-mode='D' />
                    <br><br>
                </div>
            ";
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
            provider.providerid, statusroom.owner, 
            (select distinct providername from provider
            where providerid in 
                (select owner from statusroom s1 where s1.roomid = statusroom.roomid 
                and providerid = owner)
            )
            as ownername,
            provider.avatarurl, roominfo.room, statusroom.roomid, provider.handle, provider.superadmin,
            (select handle from roomhandle where roomhandle.roomid = statusroom.roomid ) as roomhandle,
            provider.alias
            from statusroom 
            left join roominfo on roominfo.roomid = statusroom.roomid
            left join provider on statusroom.providerid = provider.providerid 
            
            where statusroom.roomid in 
            (select roomid from statusroom where providerid =$providerid and owner!=$providerid
            )
            and (roomid = $roomid or '$roomid' = '0')
                and provider.active = 'Y'
            
            order by  statusroom.roomid asc, provider.providername asc
        ");

    //echo "<table  class='' style='background-color:transparent;border:0;border-collapse:collapse;margin:auto;width:250px'>";
    echo "<div style='padding:0;margin-auto;text-align:center'>";
    
    $lastroom = "";
    while($row = pdo_fetch($result))
    {
        if($row['avatarurl'] == "$rootserver/img/faceless.png"){
            $row['avatarurl'] = "$rootserver/img/newbie2.jpg";
        }
        if($row['alias']!='')
        {
            $row['name'] = $row['alias'];
        }
        if( $row['roomhandle']!='' && $row['alias']=='')
        {
            $row['name'] = $row['name2'];
            if($row['name2']==''){
                $row['name']='Anonymous';
            }
                
        }
        if( $row['superadmin']=='Y') {
            $row['name']='Admin';
        }
        if( $row['roomhandle']!='')
        {
            $row['ownername']=$row['roomhandle'];
        }
        
        if( $row['superadmin']=='Y' && $row['roomhandle']!='' )
        {
            continue;
        }
            
        
        if( $lastroom == '')
        {
                echo "
                <br><br><br><br>
                <div style='margin:auto;width:200px;text-align:center'>
                <span class=pagetitle2  style='color:black'>Role: Member</span>
                </div>
               ";
            
        }
        if($lastroom !=$row['roomid'])
        {
        echo "
                <hr>
                <div style='margin:auto;width:300px;padding:0;height:20px;text-align:center'>
                    <span class='pagetitle2a' style='color:black;'>$row[ownername] - $row[room] </span>
                </div>
                <br>
               ";
            if(intval($roomid)!=0)
            {
                $shareroom = ShareRoom("friendlist",$roomid,"","","", false);
                if($shareroom!=''){
                    echo "<div style='display:block;position:relative;right:0px;top:0px;text-align:center'>$shareroom</div><br>";
                }
            }    
            echo "<br><br>";
            
        }
        $deleteme = "";
        if( $row['providerid']==$providerid )// || $providerid ==690001027) 
        {
            $deleteme =
                    "<br>
                    <img class='icon20' src='../img/delete-circle-128.png' style='' class='friendlist' 
                    id='deletefriends' 
                    data-providerid='$row[providerid]' data-roomid='$row[roomid]' data-mode='M' />
                    ";
            
        }
        
        echo "
                <div class='chatlistbox gridstdborder' style='display:inline-block;background-color:whitesmoke;margin:auto;;padding-bottom:20px;text-align:center;overflow:hidden'>
                <div style='height:50%;width:100%;background-color:#404040'>
                    <img class='chatlistphoto1' src='$row[avatarurl]' style='width:auto;max-width:100%;text-align:center' />
                </div>
                <div class='smalltext' style='height:30px;'>
                    $row[name]<br>$row[handle]
                </div>
                <div style=''>
                    $deleteme
                </div>
                </div>
            ";
            $lastroom = $row['roomid'];
        
        
    }
    echo "</div></div></div>";
    
    
   
    
    
?>
