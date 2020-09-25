<?php
session_start();
require_once("config-pdo.php");
require ("SmsInterface.inc");
require ("sendmail.php");

if($batchruns!='Y')
    exit();


    //Flag old Alerts as DONE
    pdo_query("1","update alerts set status='Y' ");

    /******
     * 
     * 
     *  CHAT ALERTS
     * 
     */
    $result = pdo_query("1","
            select chatmembers.providerid,
            chatmessage.msgdate
            from chatmembers 
            left join chatmessage on chatmembers.chatid = chatmessage.chatid
            where lastread < msgdate
            and datediff( curdate(), msgdate)<3
        ",null);
    while( $row = pdo_fetch($result))
    {
        $providerid = $row['providerid'];
        $result2 = pdo_query("1","
            insert into alerts (providerid, alerttype, alertdate, status )
            values
            (?, 'Chat', now(), 'N' )
            ",array($providerid));
    }
    /******
     * 
     * 
     *  TEXT ALERTS
     * 
     
        
    $result = pdo_query("1",
         "
         select providerid from textmsg where  
         (readtime < createdate or readtime is null) and reply='Y'
         "
     );
    if($row = pdo_fetch($result))
    {
        $result2 = pdo_query("1","
            insert into alerts (providerid, alerttype, alertdate, status )
            values
            ($row[providerid], 'Text', now(), 'N' )
            ");
    }
     * 
     */
        
    /******
     * 
     * 
     *  ROOMS ALERTS
     * 
     */
    
    $result = pdo_query("1",
        "
            SELECT owner, providerid FROM statusroom  where
            roomid in
            ( select roomid from
              statuspost
              where statuspost.postdate > 
              (
                select xacdate from activitylog where providerid = statuspost.providerid 
                 and xaccode = 'ROOMS' order by xacdate desc limit 1 
              )
              and datediff( now(), statuspost.postdate ) <= 3
             )
         ",null);    
    if($row = pdo_fetch($result))
    {
        $providerid = $row['providerid'];
        $result2 = pdo_query("1","
            insert into alerts (providerid, alerttype, alertdate, status )
            values
            ($providerid, 'Rooms', now(), 'N' )
            ",array($providerid));
    }
        
    
    
    /******
     * 
     * 
     *  ROOM JOIN ALERT - TODAY'S JOINS - NOT SELF INITIATED - ALERT ROOM MEMBERS
     * 
     */
    
    $result = pdo_query("1",
        "
            SELECT distinct statusroom.providerid, providername, roomid FROM statusroom  
            left join provider on statusroom.providerid = provider.providerid
            where
            roomid in 
            (
            SELECT roomid FROM statusroom  where
            createdate > curdate()  and creatorid!=providerid 
            )
         ",null);    
    if($row = pdo_fetch($result))
    {
        $providerid = $row['providerid'];
        $result2 = pdo_query("1","
            insert into alerts (providerid, alerttype, alertdate, status )
            values
            ($providerid, 'RoomJ', now(), 'N' )
            ",null);
    }
    
    /******
     * 
     * 
     *  UNREAD SECURE SENT ALERTS
     * 
     */
        
    
    $result = pdo_query("1",
     
         "
            select msgto.providerid, msgmain.createtime from msgto
            left join msgmain on msgmain.sessionid = msgto.sessionid
            where readtime is null and msgto.replyflag!='Y'
            and datediff(now(), msgmain.createtime) <=1
         ",null
     );
    if($row = pdo_fetch($result))
    {
        $providerid = $row['providerid'];
        $result2 = pdo_query("1","
            insert into alerts (providerid, alerttype, alertdate, status )
            values
            ($providerid, 'SecureSent', now(), 'N' )
            ",null);
    }
    /******
     * 
     * 
     *  UNREAD SECURE INBOX ALERTS
     * 
     */
    
    $result = pdo_query("1",
     
         "
            select msgto.providerid, msgmain.createtime from msgto
            left join msgmain on msgmain.sessionid = msgto.sessionid
            where readtime is null and msgto.replyflag='Y'
            and datediff(now(), msgmain.createtime) <=1
         ",null
     );
    if($row = pdo_fetch($result))
    {
        $providerid = $row['providerid'];
        $result2 = pdo_query("1","
            insert into alerts (providerid, alerttype, alertdate, status )
            values
            ($providerid, 'SecureInbox', now(), 'N' )
            ",null);
    }
    
    
    
     $result = pdo_query("1","
         select distinct alerts.providerid, providername, replyemail from alerts
         left join provider on 
            provider.providerid = alerts.providerid
            where datediff( curdate(), alertdate) < 1
            and provider.replyemail in (select email from verification 
            where verification.email = provider.replyemail and verifieddate is not null)
         ",null);   
     while( $row = pdo_fetch($result))
     {
         $providerid = $row['providerid'];
         $providername = $row['providername'];
         $email = $row['replyemail'];
         
         $chatalert = "";
         $textalert = "";
         $roomalert = "";
         $roomjoinalert = "";
         $securesentalert = "";
         $secureinboxalert = "";
         
         $result2 = pdo_query("1","
            select alerttype from alerts where providerid=$providerid and status='N'
            ",null);   
         while( $row2 = pdo_fetch($result2))
         {
             if( $row2['alerttype']=='Chat')
                 $chatalert = 'Y';
             if( $row2['alerttype']=='Text')
                 $textalert = 'Y';
             if( $row2['alerttype']=='Rooms')
                 $roomalert = 'Y';
             if( $row2['alerttype']=='RoomJ')
                 $roomjoinalert = 'Y';
             if( $row2['alerttype']=='SecureSent')
                 $securesentalert = 'Y';
             if( $row2['alerttype']=='SecureInbox')
                 $secureinboxalert = 'Y';
             
             HandleAlert($providerid, $providername, $email, 
                         $chatalert, $textalert, $roomalert, $roomjoinalert, 
                         $securesentalert, $secureinboxalert );
             
         }
         
     }
    
    function HandleAlert($providerid, $providername, $email, 
                $chatalert, $textalert, $roomalert, $roomjoinalert,
                $securesentalert, $secureinboxalert )
    {
        global $appname;
        global $rootserver;
        global $installfolder;
            
        $result = pdo_query("1","select * from notifytokens where providerid=$providerid and arn!='' ");
        if( ($row = pdo_fetch($result) ) )
        {
            //No email notifications if device notifications are available
            return;
        }
        
        $alertmessage = "";
        
        if( $chatalert=='Y')
            $alertmessage .= "<b>Unread Chat Messages</b><br>";
        if( $textalert=='Y')
            $alertmessage .= "<b>Incoming Text Replies</b><br>";
        if( $roomalert=='Y')
            $alertmessage .= "<b>Room Post Activity</b><br>";
        if( $roomjoinalert=='Y')
            $alertmessage .= "<b>Room Join Activity</b><br>";
        if( $securesentalert=='Y')
            $alertmessage .= "<b>Unread Sent Secure Messages</b><br>";
        if( $secureinboxalert=='Y')
            $alertmessage .= "<b>Secure Message Replies</b><br>";

        $alertmessageAlt = "";
        
        if( $chatalert=='Y')
            $alertmessageAlt .= "- Unread Chat Messages\r\n";
        if( $textalert=='Y')
            $alertmessageAlt .= "- Incoming Text Replies\r\n";
        if( $roomalert=='Y')
            $alertmessageAlt .= "- Private Room Activity\r\n";
        if( $roomjoinalert=='Y')
            $alertmessageAlt .= "- Room Join Activity\r\n";
        if( $securesentalert=='Y')
            $alertmessageAlt .= "- Unread Sent Secure Messages\r\n";
        if( $secureinboxalert=='Y')
            $alertmessageAlt .= "- Secure Message Replies\r\n";
        
        
        
        $message = "
                <html><body style='font-family:helvetica;font-size:13px;'>
                You have the following account activity on $appname. <br>
                <br>$alertmessage<br>
                Please log in to read it.<br><br>
                <a href='$rootserver/$startupphp'>$appname</a>
                <br><br>
                <img src='$rootserver/img/lock.png' style='height:30px; width: auto'><br>
                <b>$appname</b>
                </body></html>
                ";
        
        
        $messagealt = "
                You have the following account activity on $appname.\r\n\r\n
                $alertmessageAlt\r\n
                Please log in to read it.\r\n\r\n
                $rootserver/l.php
                \r\n\r\n    
                $appname
                ";
        
        
        
        SendMail("0", "Activity on Your Account", "$message", "$messagealt", "$providername", "$email" );
        
        
    }
    
    
    
    
    
        
        
    

        


     

    function CleanPhone( $phone )
    {
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );
        
        return $phone;
    }
    function FormatPhone( $phone )
    {
        $area = substr( $phone, 0, 3);
        $num1 = substr( $phone, 3, 3);
        $num2 = substr( $phone, 6, 4);
        
        if( $area == '')
            return "";
        
        return "(".$area.") ".$num1."-".$num2;
    }


?>