<?php
session_start();
require_once("config.php");
require_once("crypt.inc.php");
require_once("sendmail.php");
require_once("notify.inc.php");
require ("SmsInterface.inc");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
require ("aws.php");

    if($batchruns !='Y') {
        exit();
    }
        
    //Based on PDT Timezone where servers are
    $result = do_mysqli_query("1","
        select eventid, roomid, eventdate, eventname, eventdesc, notificationstatus, timezone,
        (select room from statusroom where owner=providerid and events.roomid = statusroom.roomid) as roomname,
        date_format( date_add(eventdate, INTERVAL timezone HOUR ),'%m/%d %a %h:%i%p') as eventdate2
        from events where 
        (
        eventdate > now() and 
        date_add(eventdate, INTERVAL timezone HOUR) < date_add(now(), INTERVAL timezone+24 HOUR) 
        and notificationstatus = ''
        )
        or
        (
        eventdate > now() and 
        date_add(eventdate, INTERVAL timezone HOUR) < date_add(now(), INTERVAL timezone+1 HOUR) 
        and notificationstatus = '1'
        )
        ");
    while( $row = do_mysqli_fetch("1",$result))
    {
        NotifyRoomMembers( $row['roomid'], $row['roomname'], $row['eventdate2'], $row['eventname'], $row['notificationstatus'] );
        do_mysqli_query("1"," 
            update events set notificationstatus='Y' where eventid=$row[eventid] and notificationstatus='1'
                ");
        do_mysqli_query("1"," 
            update events set notificationstatus='1' where eventid=$row[eventid] and notificationstatus=''
                ");
    }
    
    
    $result = do_mysqli_query("1","
        select eventid, roomid, eventdate, eventname, eventdesc, notificationstatus,
        (select room from statusroom where owner=providerid and tasks.roomid = statusroom.roomid) as roomname,
        date_format( date_add(eventdate, INTERVAL timezone HOUR ),'%m/%d %a %h:%i%p') as eventdate2
        from tasks where eventid in
        (
            select eventid from tasksaction where donecode='Done' and tasksaction.eventid = tasks.eventid
        )
        and notificationstatus = ''
        ");
    while( $row = do_mysqli_fetch("1",$result))
    {
        NotifyRoomMembers( $row['roomid'], $row['roomname'], '', $row['eventname'], $row['notificationstatus'] );
        do_mysqli_query("1"," 
            update tasks set notificationstatus='Y' where eventid=$row[eventid] 
                ");
    }
    
    
function NotifyRoomMembers( $roomid, $roomname, $eventdate, $eventname, $notificationstatus )
{

    if($notificationstatus==''){
        $notifytype = 'E1';
    }
    else
    {
        $notifytype = 'E2';
    }
        $result = do_mysqli_query("1",
        "
            select providerid
            from statusroom where roomid = $roomid 
        ");
    if($eventdate!='')
    {
        $encodedpayload = base64_encode("Event Reminder - $roomname: $eventname $eventdate");
    }
    //Task
    else 
    {
        $encodedpayload = base64_encode("Task Done - $roomname: $eventname");
 
    }
        
    while( $row = do_mysqli_fetch("1",$result))
    {
        
        GenerateNotification( 
            0, 
            $row['providerid'], 
            $notifytype, null, 
            null, null, 
            $encodedpayload, '',
            'BASE64' );
        
        
    }
}
        
?>