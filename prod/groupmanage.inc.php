<?php
require_once("config.php");

    function DeleteGroup( $groupid )
    {
    
        do_mysqli_query("1","delete from groups where groupid = $groupid and creator=$_SESSION[pid] ");
        do_mysqli_query("1","delete from groupmembers where groupid = $groupid ");
        return;
    }
    
    
    function DeleteGroupMember( $groupid, $providerid, $friendproviderid )
    {
        //Delete from statusroom if member\ is inactive
        $result = do_mysqli_query("1","
            delete from groupmembers 
            where groupid=$groupid and 
            providerid = $friendproviderid 
            and exists (select * from groups where creator = $providerid and groupid = $groupid )
            ");
        

        return;
    
    }

    function AddGroupMember($providerid, $friendproviderid, $groupid )
    {
        if($groupid <= 0){
            return false;
        }
    
            
        do_mysqli_query("1","
            insert into groupmembers ( groupid, providerid, createdate ) values 
            ($groupid, $friendproviderid, now() )
            ");

        return true;
        
        
    }

    
    function GroupEdit($mode, $groupid, $nameHtml,$desc, $organization, $photourl, $roommode, $roomid
            )
    {
        global $appname;
        global $providerid;
        global $global_background;
        global $global_textcolor;
        global $iconsource_braxarrowleft_common;
        
        $selectroom = "<select class='grouproom' id='grouproomid' name='grouproomid'  style='width:250px'>";
        $selectroom .= "<option value=''>- Not Used -</option>";
         $result = do_mysqli_query("1","
                select distinct room, roomid
                from roominfo where roomid in (select roomid from statusroom where owner = $_SESSION[pid] )
                order by room
                ");
        while($row = do_mysqli_fetch("1",$result)){
        
            $roomname = htmlentities($row['room']);
            $selected = '';
            if($roomid!='' && $roomid == $row['roomid']){
                $selected = 'selected=selected';
            }
            $selectroom .= "<option value='$row[roomid]' $selected >$roomname </option>";
        }
        $selectroom .= "</select>";

        
        $roomedit = "
                    <div class='mainfont' style='color:$global_textcolor;background-color:$global_background;max-width:70%;margin:auto;padding:20px;max-width:400px;text-align:left'>
                        <div class='groupmanage tapped' 
                            id='roomchange' data-groupid='' data-mode=''>
                            <img class='icon20' src='$iconsource_braxarrowleft_common' /> Back
                        </div><br><br>
                        <div class='divbuttontext divbuttontext_unsel groupmanage tapped' 
                            id=''  data-groupid='$groupid' data-mode='$roommode'>
                            Save Properties
                        </div>
                        <br><br>
                        <hr>
                        <div class='pagetitle2a' style='color:$global_textcolor;'>
                            <b>Community Identification</b> 
                        </div>
                        <br>
                        Community Name<br>
                        <input id='groupname' name='room' placeholder='Community Name' type='text' size=20 maxlength=30 value='$nameHtml' style='width:250px'>
                        <br><br>
                        Description of Community<br>
                        <input id='groupdesc' name='roomdesc' placeholder='Description' type='text' size=20 value='$desc' style='width:250px'>
                        <br><br>
                        Organization/Company<br>
                        <input id='grouporganization' name='roomorganization' placeholder='Organization Name (Optional)' type='text' size=20 value='$organization' style='width:250px'>
                        <br><br>
                        
                        Community Photo<br>
                        <input id='groupphotourl' class='smalltext' name='photourl' placeholder='Select a Photo' type='text' size=20 value='$photourl' readonly=readonly style='background-color:whitesmoke;width:250px'>
                        <br>
                        <span class='photoselect'
                             id='photoselect' style='cursor:pointer'
                             data-target='#groupphotourl' data-src='' data-filename='' data-mode='X' data-caller='roomsetup' title='My Photo Library' >
                            <img class='icon20' src='../img/brax-photo-round-white-128.png' style='cursor:pointer;top:5px;' />
                             &nbsp;Select from My Photos
                        </span>
                            <br>
                        
                        <br><br>
                        Auto populate Membership from a Room<br>
                        $selectroom
                        <br>
                        <span class='smalltext'>
                        If membership is based on a room, then there is no need to maintain 
                        the membership list manually. It will be automatically matched to the membership
                        of the selected room.
                        </span>
                        <br><br>
                    </div>
            ";        
        
        if( $mode == 'N' || $mode == 'E'){
        

            $modedesc = "Community Properties";
            if( $mode == 'N'){
                $modedesc = "Create a New Community";
            }
        }
        
        $roomtip = '';
        
        $roomtext =  "
                        <br>
                        <center>
                        <div class='pagetitle2'>$roomHtml</div>
                        $roomedit

                        <br>
           ";
        return $roomtext;
    }
    function GetNewGroupID( $parmkey, $parmcode )
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
                        select max(groupid)+1 as maxval from statusroom
                        ");
                    if( $row = do_mysqli_fetch("1",$result)){
                    
                        $maxval =intval($row['maxval']);
                    }
                }
                
                $result = do_mysqli_query("1","
                    insert into parms (parmkey, parmcode, val1, val2 ) values 
                    ('$parmkey','$parmcode', $maxval, 0 )
                ");
                $val1 = $maxgroupid;
     
            }
            $result = do_mysqli_query("1","
                update parms set val1=val1+1 where parmkey='$parmkey' and parmcode='$parmcode'
            ");
            $val1++;
            
            return $val1;
        
    }
    function SaveGroup($groupid, $name, $description, $organization, $photourl, $roomid )
    {
        
        //echo "anon = $roomanonymous";
        
        
        if(intval($roomid)==0){
            $roomid = 'null';
        }
        $desc = htmlentities($description, ENT_QUOTES);
        $nameclean = mysql_safe_string(stripslashes($name));
        $photourlclean = mysql_safe_string($photourl);
        $organizationclean = mysql_safe_string(stripslashes($organization));
        $roomid = mysql_safe_string(stripslashes($roomid));
        
        $result = do_mysqli_query("1","
            update groups set groupname ='$nameclean', groupdesc='$desc', 
                photourl='$photourlclean',organization='$organizationclean', roomid=$roomid
                where groupid = $groupid
        ");
        
        if(intval($roomid)>0){

            do_mysqli_query("1"," 
                delete from groupmembers where groupid = (select groupid from groups where groupid=$groupid and 
                creator = $_SESSION[pid] )
                    ");
            
            
            do_mysqli_query("1"," 
                insert into groupmembers( groupid, providerid, createdate ) 
                select $groupid, providerid, now() from statusroom where 
                roomid = $roomid and owner = $_SESSION[pid]
                    ");
            
        }
        
        return "";
    }
    
    function ConstructGroupSelect($providerid, $groupid)
    {
        global $global_textcolor;
        global $global_background;
        
        if(intval($groupid) > 0){
            //return "test";
        }
        //$room1 = htmlentities($room,ENT_QUOTES);
        //$room2 = stripslashes($room);
        
        $select = "
            <div class='pagetitle2'  style='color:$global_textcolor;background-color:$global_background;text-align:center;margin:auto;'>
                  ";
        

        $result = do_mysqli_query("1","
            select groupid, groupname, organization, photourl, roomid from
            groups
            where 
                creator = $providerid 
            order by groupname asc
        ");
//                statusroom.owner=$providerid or
//                statusroom.groupid in (select groupid from roommoderator where providerid = $providerid )
    
    
        $i=0;
        while($row = do_mysqli_fetch("1",$result)){
        

            $selected = "";

            $room1 = htmlentities(stripslashes($row['groupname']),ENT_QUOTES);
            $room2 = stripslashes($row['groupname']);
            if($row['photourl']==''){
                $row['photourl'] = "https://bytz.io/prod/sharedirect.php?a=T4AZ5435f7a219bb08.41310993";
            }
            $photourl = "
                    <div style='width:100%;text-align:center;padding:10px;margin:auto;overflow:hidden'>
                        <img src='$row[photourl]' style='height:80px;width:auto;max-width:100%' />
                    </div>
                    ";


            $select .=      "<div data-groupid='$row[groupid]' data-mode='V' 
                            class='groupmanage pagetitle3 smalltext' 
                           style='width:280px;min-width:25%;
                           vertical-align:middle;
                           margin-left:5px;margin-right:5px;margin-top:0px;margin-bottom:5px;
                           font-weight:500;cursor:pointer;color:black;
                           background-color:whitesmoke;display:inline-block;
                           padding-left:10px;padding-right:10px;border-radius:5px;
                           padding-bottom:5px;padding-top:10px;overflow:hidden;'>$photourl$room2</div>";
            $i++;
        }
    
        $select .=
        "
            </div>
            <br>
        ";
        if( $i == 0) {
            //return "";
        }
        return $select;
    
    }
    function GroupMemberList($providerid, $groupid, $filter )
    {
        global $rootserver;
        
        $result = do_mysqli_query("1",
            "
                select provider.replyemail, providername as name, provider.providerid,
                avatarurl, groups.groupname, groupmembers.groupid, provider.handle, 
                groups.creator
                from groupmembers
                left join groups on groupmembers.groupid = groups.groupid
                left join provider on groupmembers.providerid = provider.providerid
                where 
                provider.active='Y'
                and groups.groupid=$groupid
                and (provider.providername like '%$filter%' or provider.handle like '%$filter%')
                order by providername limit 500
             ");

        while($row = do_mysqli_fetch("1",$result)){
        
            $avatar = $row['avatarurl'];
            if($avatar == "$rootserver/img/faceless.png"){
                $avatar = "$rootserver/img/egg-blue.png";
            }
            if($row['handle']!=''){
                $row['replyemail']=$row['handle'];
            }
            $row['name']=substr($row['name'],0,20);
            $row['replyemail']=substr($row['replyemail'],0,20);
            echo "
                <div class='roomlistbox rounded' 
                    style='display:inline-block;padding-top:2px;;
                    background-color:#a1a1a4;color:white;margin-bottom:10px;overflow:hidden;border-color:gray;border-width:1px'>

                    <div style='background-color:white;color:white;height:65%;overflow:hidden;width:100%;' >
                        <img class='avatar1' src='$avatar' style='width:auto;max-width:100%;min-height:100%;;overflow:hidden' />
                            <br>
                    </div>
                    <div class='smalltext2' style='word-wrap:break-word;width:95%;margin-top:5px;margin-bottom:2px;padding-left:5px;padding-right:5px;overflow:hidden'>
                        $row[name]
                        <br>
                        $row[replyemail]
                        <br>
                    </div>
                    <img class='icon20 groupmanage' src='../img/delete-circle-white-128.png' style='' 
                        data-providerid='$row[providerid]'  data-groupid='$groupid' data-mode='D' />
                </div>
                   ";
            


        }
        

        
    }        
    function GetGroupData($groupid, $providerid)
    {
        $groupdata = array();
        
        $groupdata['IsOwner'] = false;
        $groupdata['groupname'] = '';
        $groupdata['groupForSql'] = '';
        $groupdata['groupHtml'] = '';

        $groupdata['organization'] = '';
        $groupdata['roomid'] = '';
        
        
        $result = do_mysqli_query("1","
            select groupid, groupname, groupdesc, organization, photourl, creator, roomid from groups 
            where groupid=$groupid and creator = $providerid
            ");

        if( $row = do_mysqli_fetch("1",$result)){

            $groupdata['IsOwner'] = false;
            if($row['creator']==$providerid ){
                $groupdata['IsOwner'] = true;
            }
            
            $groupdata['groupname'] = stripslashes($row['groupname']);
            $groupdata['groupForSql'] = mysql_safe_string(stripslashes($row['groupname']));
            $groupdata['groupHtml'] = htmlentities(stripslashes($row['groupname']),ENT_QUOTES);
            
            $groupdata['groupdesc'] = $row['groupdesc'];
            $groupdata['photourl'] = $row['photourl'];
            $groupdata['organization'] = $row['organization'];
            $groupdata['roomid'] = $row['roomid'];
            
        }
        return (object) $groupdata;
    }
    
    
?>