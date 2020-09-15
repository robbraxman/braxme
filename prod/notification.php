<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

$providerid = $_SESSION['pid'];
$mode = @mysql_safe_string($_POST['mode']);


?>
<div class='appbody'>
    <br>
        <div class='tilebutton tapped mainfont'>
            <img class='icon25' src='../img/arrow-stem-circle-left-128.png' style='float:left;top:0px' >
            &nbsp;&nbsp;Settings
        </div>
    <br><br>
        <div class='pagetitle'>
            <img class='icon25' src='../img/folded-icons/flag-folded-icon.png' 
                style='' />
            Notifications</div>
        <br>
        <div style='text-align:center;margin:auto;width:100%'>
<?php

$result = do_mysqli_query("1","
            select 
            DATE_FORMAT(date_add(notification.notifydate,INTERVAL $_SESSION[timezoneoffset]*60 MINUTE), '%b %d %a %h:%i %p') as notifydate2, 
            notifydate, notification.notifytype, notification.roomid, 
            notification.providerid, provider.providername, roominfo.room, notification.chatid
            from notification
            left join provider on provider.providerid = notification.providerid
            left outer join roominfo on statusroom.roomid = roominfo.roomid 
            where
            datediff(curdate(), notification.notifydate) < 3
            and notification.recipientid = $providerid
            and notification.notifytype in ('RP','RL','CP')
            order by notification.notifydate desc
        ");
$i1 = 0;
$lastnotifytype = '';
$lastid = '';
while($row = do_mysqli_fetch("1",$result))
{
    if($row['notifytype']=='RP'){
        $notifytype = 'Room Activity';
        
        if($row['providername']==''){
            $row['providername']='Anonymous';
        }
        
        //if($row['notifytype']!= $lastnotifytype && $row['roomid']!= $lastid){
        echo  
            "
            <div class='feed mainbutton' data-roomid='$row[roomid]' >
                <div class='gridstdborder smalltext'
                    style='display:inline-block;cursor:pointer; 
                    background-color:whitesmoke;color:black;
                    padding-top:10px;
                    padding-bottom:10px;
                    padding-left:20px;
                    padding-right:20px;
                    width:90%;max-width:300px;
                    height:40px;margin-bottom:10px'; 
                    text-align:left;
                >
                    <div style='width:auto;text-align:left'>
                        <b style='color:#00A0E3'>$notifytype</b> $row[notifydate2] <br>
                        $row[room]<br>
                        $row[providername] 
                    </div>
                </div>
            </div>

            ";
            $lastid = $row['roomid'];
        //}
        
    }
    if($row['notifytype']=='RL'){
        $notifytype = 'Room Like';
        
        if($row['providername']==''){
            $row['providername']='Anonymous';
        }
        
        //if($row['notifytype']!= $lastnotifytype && $row['roomid']!= $lastid){
            echo  
            "
            <div class='feed mainbutton' data-roomid='$row[roomid]'  >
                <div class='gridstdborder smalltext'
                    style='display:inline-block;cursor:pointer; 
                    background-color:whitesmoke;color:black;
                    padding-top:10px;
                    padding-bottom:10px;
                    padding-left:20px;
                    padding-right:20px;
                    width:90%;max-width:300px;
                    height:40px;margin-bottom:10px'; 
                    text-align:left;
                >
                    <div style='width:auto;text-align:left'>
                        <b style='color:#00A0E3'>$notifytype</b> $row[notifydate2] <br>
                        $row[room]<br>
                        $row[providername] 
                    </div>
                </div>
            </div>
            

            ";
            $lastid = $row['roomid'];
        //}
        
    }
    if($row['notifytype']=='CP'){
        $notifytype = 'Chat';
        if($row['notifytype']!= $lastnotifytype && $row['chatid']!= $lastid) {
            echo  
            "
            <div class='setchatsession mainbutton' data-chatid='$row[chatid]' data-keyhash='' >
                <div class='gridstdborder smalltext'
                    style='display:inline-block;cursor:pointer; 
                    background-color:whitesmoke;color:black;
                    padding-top:10px;
                    padding-bottom:10px;
                    padding-left:20px;
                    padding-right:20px;
                    width:90%;max-width:300px;
                    height:40px;margin-bottom:10px'; 
                    text-align:left;
                >
                    <div style='width:auto;text-align:left'>
                        <b style='color:#F7931D'>$notifytype</b>  $row[notifydate2]<br>
                        $row[providername] 
                    </div>
                </div>
            </div>
            ";
            $lastid = $row['chatid'];
        }
    }
    
    $lastnotifytype = $row['notifytype'];
    $i1++;
}




?>
        </div>
        <br>
        <br>
        <br>
</div>