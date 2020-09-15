<?php
session_start();
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");

$providerid = @tvalidator("PURIFY",$_SESSION['pid']);
$mode = @tvalidator("PURIFY",$_POST['mode']);
$lastfunc = @tvalidator("PURIFY",$_POST['lastfunc']);
$parm1 = @tvalidator("PURIFY",$_POST['parm1']);
if( $mode == 'S')
{
    //echo "parm1-$parm1";
    SaveLastFunction($providerid, $lastfunc, $parm1);
    exit();
    
}
if( $mode == 'G')
{

    $lastfunc = GetLastFunction($providerid, 0);
    echo json_encode((array) $lastfunc);
    exit();
}

//Notifications - Inline
if( $mode == 'N')
{
    
    $getlastfunc = GetLastFunction($providerid, 120);
    /*
    if( $getlastfunc->lastfunc=='C' )
    {
        //Currently in Chat 
        //
        //$arr = array("notification" => "LastFunc:$getlastfunc->lastfunc");
        $arr = array("notification" => "");
        echo json_encode( $arr);
        exit();
        
    }
     * 
     */
    $payload = InlineNotification($getlastfunc->lastfunc, $getlastfunc->parm1, $providerid );
    
    $arr = array("notification" => "$payload->notification", "soundalert" => "$payload->soundalert");
    echo json_encode( $arr);
    exit();
}

function InlineNotification( $lastfunc, $parm1, $providerid )
{
    $arr['notification'] = "";
    $arr['soundalert'] = "";
    
    if($parm1 == '') {
        $parm1 = '0';
    }
    if(intval($providerid) == 0){
        return (object)  $arr;
    }

    
    //Get the LATEST Notification
    
    $result = pdo_query("1","
        select 
            notification.providerid, notification.notifydate, notification.status, 
            notification.notifytype, notification.email, 
            notification.sms, notification.name, notification.recipientid, 
            notification.notifyid, notification.mobile,
            provider.providername, provider.alias, provider.companyname,
            provider.replyemail, notification.roomid, notification.chatid,
            notification.payload,notification.encoding, notification.soundalert,
            (select keyhash from chatmaster where chatmaster.chatid = notification.chatid) as keyhash
        from notification 
        left join provider on notification.providerid = provider.providerid
        where notification.status = 'Y' and notification.displayed!='Y'
        and notification.recipientid = ? 
        and (notification.providerid != ? or notification.notifytype!='CP')
        and notification.notifytype in ('CP','RP','EN')
        order by notification.notifydate desc limit 1
            ",array($providerid,$providerid));
    $payload = "";
    $total = "";
    $soundalert = "";
    while( $row = pdo_fetch($result))
    {
        $result2 = pdo_query("1", "
                select count(*) as total from notification where displayed='N'
                and recipientid=?  ",array($providerid));
        $row2 = pdo_fetch($result2);
        $total = "($row2[total] new)";
        
        $notifyid = $row['notifyid'];
        $notifytype = "$row[notifytype]";
        
        
        $providername = "$row[providername]"; 
        $chatid = $row['chatid'];
        $alias = "$row[alias]";
        $soundalert = "$row[soundalert]";
        if($alias!='')
        {
            $providername = $alias;
        }
        if( $notifytype === 'CP')
        {
            $chatid = intval($row['chatid']);
            
            //Alert only if not in Chat or Chat is a Different session than alert
            if( $lastfunc !=='C' || ($lastfunc == 'C' && $chatid != intval($parm1)))
            {
                
                $payload .= " 
                    <div class='setchatsession mainbutton tapped2 smalltext' data-keyhash='$row[keyhash]' data-chatid='$chatid' style='background-color:transparent;padding:5px' >
                        Chat $providername -
                        ".DecryptChat( "$row[payload]", "$row[encoding]","$chatid","")."</div>";
            }
        }
        if( $notifytype === 'RP')
        {
            $roomid = intval($row['roomid']);
            
            //Alert only if not in Rooms or if in a different room
            if( $lastfunc !=='R' || ($lastfunc == 'R' && $roomid != intval($parm1)))
            {
                $result = pdo_query("1","select room from statusroom where roomid=? and providerid=?",array($roomid,$providerid));
                $row = pdo_fetch($result);
                $room = "$row[room]";
                //No alert if already in Chat
                $payload .= "
                    <div class='feed mainbutton tapped2 smalltext' data-roomid='$roomid' style='background-color:transparent;padding:5px' >
                        Room Post: $providername -
                        $room
                    </div>
                        ";
            }
        }
        if( $notifytype === 'EN')
        {
            $payload .= "
                <div class='tilebutton tapped smalltext'  style='background-color:transparent;padding:5px' >
                    Secure Email: $providername
                </div>
                    ";
            //@pdo_query("1", "update notification set displayed='Y' where recipientid=$providerid  and notifyid = $notifyid ");
        }
        @pdo_query("1", "update notification set displayed='Y' where recipientid=$providerid and displayed !='Y' ");
    }
    $arr['notification'] = FormattedPayload($payload, $total, $soundalert);
    $arr['soundalert'] = $soundalert;
    return (object) $arr;
    
}
function FormattedPayload( $payload, $total, $soundalert )
{
    if($payload == '')
    {
        return "";
    }
    if($soundalert == '1')
    {
        return "
                <div class='gridstdborder' style='margin:0;padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                    <img class='notificationdismiss tapped' src='../img/delete-gray-01-128.png' style='cursor:pointer;float:right;height:25px' />
                    <div class='pagetitle2a' style='color:firebrick'><b>Alert</b></div>
                    $payload 
                </div>
                ";
        
    }
    return "
            <div class='gridstdborder' style='margin:0;padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px'>
                <img class='notificationdismiss tapped' src='../img/delete-gray-01-128.png' style='cursor:pointer;float:right;height:25px' />
                <div class='pagetitle2a' style='color:firebrick'>Notifications</div>
                $payload 
            </div>
            ";
}
?>
