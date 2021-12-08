<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("roommanage.inc.php");



    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_POST['providerid']);

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $handle = stripslashes(@tvalidator("PURIFY",$_POST['handle']));
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $roomid = intval(@tvalidator("ID",$_POST['roomid']));
    $action = @tvalidator("PURIFY",$_POST['action']);
    $inviteid = @tvalidator("PURIFY",$_POST['inviteid']);
    
    
    
    
    $result = pdo_query("1",
            "select roomid, timestampdiff(HOUR, now(), expires) as diff from roominvite where inviteid = ?
            ",array($inviteid)
            );
    $diffval = -1;
    //$diffval = 0;
    
    if($row = pdo_fetch($result)){
    
        $roomid = $row['roomid'];
        $diffval = $row['diff'];
        if(intval($diffval)< 0 ){
        
            $msg =  "This invitation has expired.";
            $arr = array('roomid'=> "$roomid",
                         'inforequest'=> "N",
                         'msg' => "$msg"
                        );

            echo json_encode($arr);
            exit();
        }
        
    } else {
    }
    
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img class='icon20' src='../img/arrow-stem-circle-left-128.png' style='padding-top:0;padding-left:5px;padding-right:2px;padding-bottom:0px;' />";
    $help = "<img class='helpinfo' src='../img/help-gray-128.png' 
            style='height:20px;width:auto;position:relative;top:3px;padding-left:10px;padding-right:10px;cursor:pointer' 
            data-help='#RoomTag<br><br>This is an ID given to allow quick access to public room. Case-insensitive. Alphanumeric only.' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    if($action == 'RADIO' || $action == 'RADIO2'){
        $mode = "J";
    }
    
    if( $mode == 'J' || $mode == 'JENTERPRISE' || $mode == 'JCOMMUNITY')
    {
        if($roomid == ''){
            $roomid = 0;
        }
        
        if( $handle[0]!='#') {
            $handle = "#".$handle;
        }
        
        $plainhashtag = substr($handle,1);
        
        $handle = str_replace(" ","", $handle);

        if(($handle == '' || $handle =='#')&& $roomid == 0){
            $msg =  "Room handle not specified";
            $arr = array('roomid'=> "",
                         'inforequest'=> "N",
                         'msg' => "$msg"
                        );

            echo json_encode($arr);
            exit();

        }

        
        $result = pdo_query("1"," 
            select statusroom.roomid, roominfo.private, roominfo.room, 
            roominfo.groupid, provider.handle, roominfo.external, roominfo.featured,
            (select 'Y' from groupmembers where providerid =? and groupmembers.groupid = roominfo.groupid ) as groupmember,
            statusroom.owner, blocked.blockee,
            (select 'Y' from statusroom s2 where providerid= ? and statusroom.roomid = s2.roomid ) as existing
            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid
            left join roominfo on roominfo.roomid = statusroom.roomid
            left join provider on provider.providerid = statusroom.owner
            left join blocked on blocked.blocker = statusroom.owner and blocked.blockee = ?
            where (roomhandle.handle = ? or statusroom.roomid = ? ) and statusroom.providerid = statusroom.owner
                   ",array($providerid,$providerid,$providerid,$handle,$roomid));
            
        
        if($row = pdo_fetch($result)){

            
            if($row['blockee']!=''){
                $msg =  "You are restricted by the owner from joining this room.";
                $arr = array('roomid'=> "$roomid",
                             'inforequest'=> "N",
                             'msg' => "$msg"
                            );

                echo json_encode($arr);
                exit();
                
            }
            if($row['private']=='Y' && $row['featured']!=''){
                $msg =  "This room is a restricted community. Please contact $row[handle] to request access.";
                $arr = array('roomid'=> "$roomid",
                             'inforequest'=> "N",
                             'msg' => "$msg"
                            );

                echo json_encode($arr);
                exit();
                
            }
            if($row['private']=='Y' && $inviteid==''){
                $msg =  "This is a private room. You have to be invited by $row[handle].";
                $arr = array('roomid'=> "$roomid",
                             'inforequest'=> "N",
                             'msg' => "$msg"
                            );

                echo json_encode($arr);
                exit();
                
            }
            if($row['groupid']!='' && $row['groupmember']!='Y'){
                
                $msg =  "This room is restricted to a community. Contact $row[handle]";
                $arr = array('roomid'=> "$roomid",
                             'inforequest'=> "N",
                             'msg' => "$msg"
                            );

                echo json_encode($arr);
                exit();
                
            }
            
            $owner = $row['owner'];
            $roomid = $row['roomid'];
            $room = tvalidator("PURIFY",$row['room']);
            $exists = $row['existing'];
            
            //This is a website so don't display website
            $altroomid = $roomid;
            if($row['external']=='Y'){
                $altroomid = 0;
            }

            SaveLastFunction($providerid,"R", $altroomid);
            
            if($exists == 'Y'){
                
                //header("location: https://brax.me/l.php");
                if( AddMember($row['owner'], $providerid, $row['roomid'] )
                ){
                    
                }
                ConfigureSponsor($mode, $providerid, $plainhashtag);  
                
            
                $msg =  "";
                //$_SESSION['inforequest']= ActiveInformationRequest($providerid);
                $arr = array('roomid'=> "$altroomid",
                             //'inforequest'=> "$_SESSION[inforequest]",
                             'inforequest'=> "N",
                             'msg' => "$msg"
                            );

                echo json_encode($arr);
                exit();
                
                
            } else {
                
                if( AddMember($row['owner'], $providerid, $row['roomid'] )
                ){
                
                    $msg =  "";
                }

                
                ConfigureSponsor($mode, $providerid, $plainhashtag);  
                
                
                

                //$_SESSION['inforequest']= ActiveInformationRequest($providerid);
                $arr = array('roomid'=> "$altroomid",
                             //'inforequest'=> "$_SESSION[inforequest]",
                             'inforequest'=> "N",
                             'msg' => "$msg"
                            );

                echo json_encode($arr);
                exit();
            }
        } else {
            $msg =  "Room not Found";
            $_SESSION['inforequest']= ActiveInformationRequest($providerid);
            $arr = array('roomid'=> "$roomid",
                         'inforequest'=> "$_SESSION[inforequest]",
                         'msg' => "$msg"
                        );

            echo json_encode($arr);
            exit();
        }
        
        
        echo "   
                <br>
                <span class='pagetitle2a' style='color:black'>&nbsp;$msg</span>      
                <div style='padding:20px'>
                    <div class='mainfont roomjoin showtop'  style='color:black'
                        id='feed' data-roomid='$roomid'>
                            $braxsocial
                               Join an Open Room&nbsp;&nbsp;
                    </div>
                </div>
                ";
        
        $_SESSION['inforequest']= ActiveInformationRequest($providerid);
        
        exit();
        
    }
    
    if($caller == 'room')
    {
        echo "  <br>
                <span class='pagetitle2a' style='color:black'>&nbsp;&nbsp;<b>Join an Open Room</b></span> 
                <br>
                &nbsp;&nbsp;
                <div class='mainfont feed showtop tapped'  style='color:black;display:inline;padding-left:5px'
                    id='feed' data-roomid='$roomid'>
                        $braxsocial
                            All Rooms&nbsp;&nbsp;
                </div>
                ";
    }
    else 
    if($caller == 'join')
    {
        echo "  <br>
                <span class='pagetitle2a' style='color:black'>&nbsp;&nbsp;<b>Join an Open Room</b></span> 
                <br>
                &nbsp;&nbsp;
                <div class='mainfont feed'  style='color:black;display:inline'
                    id='feed' data-roomid='$roomid'>
                        $braxsocial
                            Room&nbsp;&nbsp;
                </div>
                ";
    }
    else 
    {
        echo "  <br>
                <span class='pagetitle2a' style='color:black'>&nbsp;&nbsp;<b>Join an Open Room</b></span> 
                <br>
                &nbsp;&nbsp;
                <div class='mainfont feed showtop' 
                    id='feed' data-roomid='$roomid' style='display:inline;color:black'>
                        $braxsocial
                            Room
                </div>
                &nbsp;&nbsp;
                <div class='mainfont roomdiscover'  style='display:inline;color:black'
                    id='feed' data-roomid='$roomid' data-caller='join'>
                            Discover Rooms&nbsp;
                    <img class='icon20' src='../img/arrow-stem-circle-right-128.png' 
                        style='
                        padding:left:10px;padding-right:2px;padding-bottom:0px;' />                
                </div>
                ";
        
    }
    echo "      
                    

                        <br><br>
                        <table style='margin:auto;padding:20px;color:black'>
                        <tr>
                        <td>
                        <br>
                        <span  style='color:black'>Join Room using #HashTag</span>
                        <br>
                        <input class='inputline dataentry mainfont' id='roomhandle' name='roomhandle' type='text' size=20 value=''              
                            style='max-width:200px;background:url(../img/hash.png) no-repeat scroll;background-size:20px 20px;background-color:ivory;padding-left:20px;'/>
                            <img class='icon25 roomjoin' id='roomjoin' data-mode='J' src='../img/arrow-stem-circle-right-128.png' 
                            style='' >
                        ";
    if($_SESSION['enterprise']!='Y'){
    echo "
                        $help
                        <br>
                        <span class='smalltext' style='color:black'>
                            Note: You can only self-join open rooms. 
                            You have to be invited to private-membership rooms by the room owner.
                        </span>
                        <br>
                        <br>
                        <br><br>
                        <br><br><br><br><br><br><br><br><br>
                        ";
    }
    
    echo                "
                        <br>
                        </td>
                        </tr>
                        <tr>
                        <td>
                        </td>
                        </tr>
                        </table>

        ";
        exit();
       
function ConfigureSponsor($mode, $providerid, $hashtag)  
{
    if($mode !== 'JENTERPRISE'){
        return;
    }

    $result = pdo_query("1","select partitioned, industry from sponsor where sponsor=? ",array($hashtag));
    if($row = pdo_fetch($result)){
        if($row['partitioned']=='Y'){
            /*
             * we will not partition you if you are open
             * 
            pdo_query("1"," 
                update provider set roomdiscovery = 'N' 
                where providerid = $providerid ");
            $_SESSION['roomdiscovery']='N';
             * 
             */

        }
        pdo_query("1"," 
            update provider set industry = '$row[industry]' 
            where providerid = ? ",array($providerid));

    }

}

?>