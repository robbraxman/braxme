<?php
function CSVText( 
        $senderid, 
        $message,
        $phone )
{
    
    $phone = str_replace( "(", "", $phone );
    $phone = str_replace( "/", "", $phone );
    $phone = str_replace( ")", "", $phone );
    $phone = str_replace( " ", "", $phone );
    $phone = str_replace( "-", "", $phone );
    $phone = str_replace( ".", "", $phone );

    if( $phone!='' && $phone[0]!='+'){
        $phone = "+1".$phone;
    }
    
        $messagesql = mysql_safe_string($message);
        do_mysqli_query("1"," 
            insert into csvtext (ownerid, message, sms, uploaded, status, error )
            values ($senderid, '$messagesql','$phone',now(), 'N','' )
                ");
        
}
function BatchSendText()
{
        $encoding = $_SESSION['responseencoding'];
        
        $result = do_mysqli_query("1","
            select id, ownerid, message, sms from csvtext where status='N'
            limit 100
            ");
        while($row = do_mysqli_fetch("1",$result)){
            
            $payload = EncryptChat ($row['message'],"0","" );
            $payloadsms = EncryptChat ($row['message'],"0","" );

            GenerateNotification( 
            $row['ownerid'], 
            0, 
            "TXT", null, 
            0, 0, 
            $payload, $payloadsms,
            $encoding, "", $row['sms'] );
            
            do_mysqli_query("1","update csvtext set status='Y' where id=$row[id] and status='N' ");
        }
        
}


function GenerateNotificationSms( 
        $senderid, 
        $name, $email, $sms,
        $notifytype, 
        $roomid,  
        $payloadsms,
        $encoding )
{
    if($payloadsms != null){
        $payloadsms_quoted = "'".$payloadsms."'";
    }
    else {
        $payloadsms_quoted = 'null';
    }
    if($encoding != null){
        $encoding_quoted = "'".$encoding."'";
    }
    else {
        $encoding_quoted = 'null';
    }
    if($roomid == null){
        $roomid = 'null';
    }
    
    $soundalert = '0';
    if( intval($roomid)>1  ){
    
        $result = do_mysqli_query("1"," 
            select notifications, soundalert from roominfo where roomid=$roomid 
            ");
        if( $row = do_mysqli_fetch("1",$result)){
        
            if($row['notifications']!='Y' ){
            
               // return;
            }
            $soundalert = $row['soundalert'];
        }
        
    }
    
    do_mysqli_query("1"," 
        insert into notification (
        providerid, notifydate, status, notifytype, notifysubtype,
        name, email, sms, 
        recipientid, mobile, roomid, chatid, payload, payloadsms, 
        encoding, displayed, soundalert,reference ) values (
        $senderid, now(), 'N', '$notifytype',null,
        '$name','$email','$sms',
        0, 'N', $roomid, null, 
        $payloadsms_quoted, $payloadsms_quoted, $encoding_quoted, 'N','$soundalert',''
        )
    ");

}

function ChatNotificationRequest($providerid, $chatid, $encodeshort, $encoding, $subtype )
{
    //No Need for Notification in Lounge
    if( intval($chatid) == 0  ){
        return;
    }
    
    $roomid = 0;
    $postid = '';
    $shareid = '';
    $anonymous = '';
    $notificationflags = "";
    
    $result = do_mysqli_query("1","
        select notificationflags from provider where providerid = $providerid
    ");
    if($row = do_mysqli_fetch("1",$result)){
        if(strpos("$row[notificationflags]","M")!==FALSE){
            $encodeshort = "Message";
            $encoding = "PLAINTEXT";
        }
    }
    
    //Photo upload
    if($subtype == 'P'){
        $result = do_mysqli_query("1","select radiostation from chatmaster where chatid=$chatid and radiostation='Y");
        if( $row = do_mysqli_fetch("1",$result)){
            return;
        }
        $subtype='';
    }

    //Community Check - if this a room spawned chat - limit notifications
    if($subtype == ''){
        $result = do_mysqli_query("1",
                "select roomid from chatmaster where chatid=$chatid "
                . "and roomid is not null and roomid > 0  ");
        if( $row = do_mysqli_fetch("1",$result)){
            $subtype='CY';
        }
    
    
    do_mysqli_query("1","
        insert into notifyrequest 
        ( requestdate, status, providerid, 
            chatid, encodeshort, encoding, 
            roomid, subtype, postid, shareid, anonymous ) 
            values
        (
          now(),'N',$providerid, $chatid, '$encodeshort', '$encoding', 
              $roomid, '$subtype', '$postid', '$shareid','$anonymous' 
        )
    ");
    
    
}


function ChatNotification($providerid, $chatid, $encodeshort, $encoding, $subtype )
{
 
    if($providerid == '' || $chatid == ''){
        return;
    }
    
    //Live Notifications
    if($subtype == 'LV' && $chatid !=''){
        $result = do_mysqli_query("1",
            "
            delete from notification where chatid = $chatid and
            notifytype='CP' and notifysubtype = 'LV' and notifyid > 0
            ");
        
    }
    
    //Check for First Response so Send Notification to Original Party
    /*
    $result = do_mysqli_query("1",
        "
        select provider.providerid, provider.replyemail, 
        provider.providername, provider.mobile, provider.notificationflags
        from chatmaster
        left join provider on provider.providerid = chatmaster.owner
        where owner!=$providerid
        and chatid=$chatid
        and (select count(*) from chatmessage where 
        chatmessage.chatid = chatmaster.chatid and providerid=$providerid) = 0
        and chatmaster.status = 'Y'
        order by chatid desc           
        ");
    if(!$result){
        //return;
    }
    if($row = do_mysqli_fetch("1",$result)){
        
        if(strstr("$row[notificationflags]","M")!==false){
            //$payload_quoted = "null";
        }
        
        
        GenerateNotification( 
            $providerid, 
            $row['providerid'], 
            'CN', '', 
            null, $chatid, 
            $encodeshort, '',
            $encoding,'','' );
        
    }    
     * 
     */
    
    
    $result = do_mysqli_query("1",
        "
        select provider.providerid, provider.replyemail, 
        provider.providername, provider.mobile, chatmaster.keyhash,
        provider.notificationflags,
        (select 'Y' from notifymute where providerid = chatmembers.providerid and id = chatmembers.chatid and idtype='C' ) as mute,
        (select techsupport from chatmembers cm2 where providerid = $providerid
            and chatmembers.chatid = cm2.chatid ) as techsupport,
        (select 'Y' from ban where ban.chatid = chatmaster.chatid and ban.banid in (select banid from provider where providerid = $providerid) ) as banned
        from chatmembers
        left join chatmaster on chatmaster.chatid = chatmembers.chatid
        left join provider on provider.providerid = chatmembers.providerid
        left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = $providerid
        left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = $providerid
        where 
        chatmembers.providerid != $providerid and
        
        chatmembers.chatid=$chatid and (provider.notifications = 'Y' or provider.notifications is null )
        and blocked1.blockee is null and blocked2.blocker is null
        order by chatmembers.chatid desc           
        
        ");
    while($row = do_mysqli_fetch("1",$result)){
    
        $notifytype = 'CP';
        if($row['keyhash']!=''){
            $encodeshort = "E2E Encrypted";
            $encoding = "PLAINTEXT";
        }
        if($subtype == 'M'){
            $notifytype = 'CM';
            $encodeshort = "Email Notification";
            $encoding = "PLAINTEXT";
            $subtype = '';
        }
        if(strpos("$row[notificationflags]","M")!==FALSE){
            $encodeshort = "Message";
            $encoding = "PLAINTEXT";
            //$payload_quoted = "null";
        }
        $loop = true;
        if(strstr("$row[notificationflags]","L")!==false && $subtype == 'LV'){
            $loop = false;
            //$payload_quoted = "null";
        }
        if(strstr("$row[notificationflags]","C")!==false && $notifytype == 'CP'){
            $loop = false;
            //$payload_quoted = "null";
        }
        if($row['mute']=='Y' && $notifytype = "CP"){
            $loop = false;
        }
        if($row['techsupport']=='Y'){
            $providerid = 1;
            $encodeshort = "Tech Support";
            $encoding = "PLAINTEXT";
            
        }
        if($row['banned']=='Y'){
            $loop = false;
        }
        if($loop){
            GenerateNotification( 
                $providerid, 
                $row['providerid'], 
                $notifytype, $subtype, 
                null, $chatid, 
                $encodeshort, '',
                $encoding,'','' );
        }
        
    }    
        
}

function RoomNotificationRequest($providerid, $roomid, $subtype, $shareid, $postid, $anonymous )
{
    //No Need for Notification in Lounge
    if( intval($roomid) <=1 ){
        return;
    }
    
    $chatid = 0;
    $encodeshort = '';
    $encoding = '';

    //No notifications for website posts
    $result = do_mysqli_query("1","
        select external from roominfo where roomid=$roomid and external='Y'
        ");
    if($row = do_mysqli_fetch("1",$result)){
        return;
    }

    
    do_mysqli_query("1","
        insert into notifyrequest 
        ( requestdate, status, providerid, 
            chatid, encodeshort, encoding, 
            roomid, subtype, postid, shareid, anonymous ) 
            values
        (
          now(),'N',$providerid, $chatid, '$encodeshort', '$encoding', 
              $roomid, '$subtype', '$postid', '$shareid','$anonymous' 
        )
    ");
    
    
}


function RoomNotification($providerid, $roomid, $subtype, $shareid, $postid, $anonymous )
{
    
    //Disable Like Notification for now
    if( $subtype == 'L'){
        return;
    }
    
    $soundalert = '0';
    if( intval($roomid)>1  ){
    
        $result = do_mysqli_query("1"," 
            select notifications, soundalert from roominfo where roomid=$roomid 
            ");
        if( $row = do_mysqli_fetch("1",$result)){
        
            if($row['notifications']!='Y'){
            
                return;
            }
            $soundalert = $row['soundalert'];
        }
        
    }
    
    if($subtype=='L'){
        $result = do_mysqli_query("1",
        "
            select provider.providerid,provider.replyemail, provider.providername,
            (select anonymousflag from roominfo where roominfo.roomid = statusroom.roomid ) as anonymousflag
            from statusroom 
            left join provider on statusroom.providerid = provider.providerid
            left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = $providerid
            left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = $providerid
            where statusroom.roomid = $roomid
            and provider.active = 'Y' and
            ( statusroom.providerid=statusroom.owner or statusroom.providerid in
                (select providerid from statuspost where postid = '$postid')
            )
            and blocked1.blockee is null and blocked2.blocker is null
        ");
        $notifytype = 'RL';
    } else {
        $result = do_mysqli_query("1",
        "
            select statusroom.providerid, provider.replyemail, provider.providername, 
            (select anonymousflag from roominfo where roominfo.roomid = statusroom.roomid ) as anonymousflag
            from statusroom 
            left join provider on statusroom.providerid = provider.providerid
            left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = $providerid
            left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = $providerid
            where statusroom.roomid = $roomid 
            and statusroom.providerid != $providerid
            and provider.active = 'Y' and  (provider.notifications = 'Y' or provider.notifications is null )
            and blocked1.blockee is null and blocked2.blocker is null
        ");
        $notifytype = 'RP';
        
    }
    

    
    while( $row = do_mysqli_fetch("1",$result)){
    
        do_mysqli_query("1","
            insert into statusreads 
            (providerid, shareid, postid, xaccode, actiontime, roomid ) 
            select provider.providerid, '$shareid', '$postid', 'R', now(), $roomid
            from provider
            where provider.providerid = $row[providerid] and providerid
            not in ( select providerid from statusreads 
            where roomid = $roomid and xaccode='R' and statusreads.providerid = provider.providerid )            
            
            ");
            $poster = $providerid;
            
        if( $anonymous ==='Y' || $row['anonymousflag'] === 'Y'){
            $poster = 0;
        }

        GenerateNotificationV2( 
            $poster, 
            $row['providerid'], 
            $notifytype, $subtype, 
            $roomid, null, 
            null, null,
            null, "$postid",'',$soundalert);

        
    }
}
function NotificationRequestLoop()
{

    $result = do_mysqli_query("1","
        update notifyrequest set status = 'P' where status = 'N'
    ");
    
    $result = do_mysqli_query("1","
        select requestid,
            requestdate, status, providerid, 
            chatid, encodeshort, encoding, 
            roomid, subtype, postid, shareid, anonymous 
        from notifyrequest 
        where status = 'P' 
        order by requestdate asc
    ");
    while($row = do_mysqli_fetch("1",$result)){
        //Mark it so it doesn't get called again
        do_mysqli_query("1","update notifyrequest set status='Y' where requestid= $row[requestid]");
        
        //if(ThrottleCheck( $row['chatid'], $row['roomid'], $row['requestid'])){
            
            //These runs could take awhile if there are many members of each
            if(intval($row['roomid'])>0){
                RoomNotification($row['providerid'], $row['roomid'], $row['subtype'], $row['shareid'], 
                    $row['postid'], $row['anonymous']);
            }
            if(intval($row['chatid'])>0){
                ChatNotification($row['providerid'],$row['chatid'],"$row[encodeshort]","$row[encoding]", "$row[subtype]");
            }
        //}
    }
    
}
function ThrottleCheck($chatid, $roomid, $requestid)
{
    if(intval($chatid) > 0 ){
        return true;
        $result = do_mysqli_query("1"," 
            select time_to_sec(timediff( now(), requestdate)) as diff 
            from notifyrequest where status = 'Y' and chatid = $chatid and requestid!=$requestid
            order by requestdate desc limit 1
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            $diff = $row['diff'];
            //LogDebug( "($diff)", "/$chatid Test");
            if($diff < 60){
                return false;
            }
            return true;
        }
    }
    if(intval($roomid) > 0 ){
        $result = do_mysqli_query("1"," 
            select time_to_sec(timediff( now(), requestdate)) as diff 
            from notifyrequest where status = 'Y' and roomid = $roomid 
             and requestid!=$requestid
            order by requestdate desc limit 1
            ");
        if( $row = do_mysqli_fetch("1",$result)){
            $diff = $row['diff'];
            if($diff < 900){
                return false;
            }
            return true;
        }
    }
}


function GenerateNotification( 
        $senderid, 
        $recipientid, 
        $notifytype, $subtype, 
        $roomid, $chatid, 
        $payload, $payloadsms,
        $encoding, $reference, $sms )
{
    if($payload != null){
        $payload_quoted = "'".$payload."'";
    }
    else {
        $payload_quoted = 'null';
    }
    if($payloadsms != null){
        $payloadsms_quoted = "'".$payloadsms."'";
    }
    else {
        $payloadsms_quoted = 'null';
    }
    if($encoding != null){
        $encoding_quoted = "'".$encoding."'";
    }
    else {
        $encoding_quoted = 'null';
    }
    if($subtype != null){
        $subtype_quoted = "'".$subtype."'";
    }
    else {
        $subtype_quoted = 'null';
    }
    if($roomid == null){
        $roomid = 'null';
    }
    if($chatid == null){
        $chatid = 'null';
    }
    
    if($notifytype == 'TXT' && strlen($sms) >= 10 ){
        
        do_mysqli_query("1"," 
            insert into notification (
            providerid, notifydate, status, notifytype, notifysubtype,
            name, email, sms, 
            recipientid, mobile, roomid, chatid, payload, payloadsms, 
            encoding, displayed,soundalert, reference, notifyread ) values (
            $senderid, now(), 'N', '$notifytype',null,
            '','','$sms',
            0, 'N', null, 0, 
            $payload_quoted, $payloadsms_quoted, 
            $encoding_quoted, 'N','','',''
            )
        ");
        return;
    }

    
    $soundalert = '0';
    if( intval($roomid)>1 && 
      ( substr($notifytype,0,1)=='R' || substr($notifytype,0,1)=='T') ){
    
        $result = do_mysqli_query("1"," 
            select notifications, soundalert from roominfo where roomid=$roomid 
            ");
        if( $row = do_mysqli_fetch("1",$result)){
        
            if($row['notifications']!='Y' && $notifytype!='T'){
            
                return;
            }
            $soundalert = $row['soundalert'];
        }
        
    }
    
    
    $result = do_mysqli_query("1"," 
        select providerid, mobile, replyemail, 
        datediff(curdate(), lastaccess) as lastheredays,
        (select count(*) from notifytokens 
          where provider.providerid = notifytokens.providerid) 
          as tokens 
        from provider
        where providerid = $recipientid and active='Y' and notifications = 'Y'
            ");
        if( $row = do_mysqli_fetch("1",$result)){
        //Existing Member
        //
            //Don't send a CHAT notification to a user that hasn't been back in 365 days
            if(intval($row['lastheredays'])>365 || $row['lastheredays']==''){
                return;
            }

            $status = 'N';
            $email = $row['replyemail'];
            if(strstr("$email",".account@brax.me")!==false){
                $email = "";
            }
            if( $email =='' && intval($row['tokens'])==0  ){
                $status = 'Y';

            }
            if(intval($row['tokens'])>0){
                $email = "";
            }

            do_mysqli_query("1"," 
                insert into notification (
                providerid, notifydate, status, notifytype, notifysubtype,
                name, email, sms, 
                recipientid, mobile, roomid, chatid, payload, payloadsms, 
                encoding, displayed,soundalert, reference ) values (
                $senderid, now(), 'N', '$notifytype',$subtype_quoted,
                '','$email','',
                $row[providerid], '$row[mobile]', $roomid, $chatid, 
                $payload_quoted, $payloadsms_quoted, 
                $encoding_quoted, '$status','$soundalert','$reference'
                )
            ");
        } else {
        //Invited Member

            $result = do_mysqli_query("1"," 
                select name, email, sms from invites where chatid=$chatid 
                ");
            if( $row = do_mysqli_fetch("1",$result)){

                do_mysqli_query("1"," 
                    insert into notification (
                    providerid, notifydate, status, notifytype, notifysubtype,
                    name, email, sms, 
                    recipientid, mobile, roomid, chatid, payload, payloadsms, 
                    encoding, displayed,soundalert, reference ) values (
                    $senderid, now(), 'N', '$notifytype',$subtype_quoted,
                    '$row[name]','$row[email]','$row[sms]',
                    0, 'N', null, $chatid, 
                    $payload_quoted, $payloadsms_quoted, 
                    $encoding_quoted, 'N','$soundalert',''
                    )
                ");

            }

        }
}


function GenerateNotificationV2( 
        $senderid, 
        $recipientid, 
        $notifytype, $subtype, 
        $roomid, $chatid, 
        $payload, $payloadsms,
        $encoding, $reference, $sms, $soundalert )
{
    
    $payload_quoted = QuoteItOrNull($payload);
    $payloadsms_quoted = QuoteItOrNull($payloadsms);
    $encoding_quoted = QuoteItOrNull($encoding);
    $subtype_quoted = QuoteItOrNull($subtype);
    
    if($roomid == null){
        $roomid = 'null';
    }
    if($chatid == null){
        $chatid = 'null';
    }
    
    if($notifytype == 'TXT' && strlen($sms) >= 10 ){
        
        do_mysqli_query("1"," 
            insert into notification (
            providerid, notifydate, status, notifytype, notifysubtype,
            name, email, sms, 
            recipientid, mobile, roomid, chatid, payload, payloadsms, 
            encoding, displayed,soundalert, reference, notifyread ) values (
            $senderid, now(), 'N', '$notifytype',null,
            '','','$sms',
            0, 'N', null, 0, 
            $payload_quoted, $payloadsms_quoted, 
            $encoding_quoted, 'N','','',''
            )
        ");
        return;
    }

    
    
    
    $result = do_mysqli_query("1"," 
        select providerid, mobile, replyemail, 
        datediff(curdate(), lastaccess) as lastheredays,
        (select count(*) from notifytokens 
          where provider.providerid = notifytokens.providerid) 
          as tokens 
        from provider
        where providerid = $recipientid and active='Y' and notifications = 'Y'
            ");
        if( $row = do_mysqli_fetch("1",$result)){
        //Existing Member
            
            //Don't send a notification to a user that hasn't been back in 30 days
            if(intval($row['lastheredays'])>30 || $row['lastheredays']==''){
                return;
            }

            $status = 'N';
            $email = $row['replyemail'];
            $email = "";
            if(strstr("$email",".account@brax.me")!==false){
                $email = "";
            }
            if( $email =='' && intval($row['tokens'])==0  ){
                $status = 'Y';

            }

            do_mysqli_query("1"," 
                insert into notification (
                providerid, notifydate, status, notifytype, notifysubtype,
                name, email, sms, 
                recipientid, mobile, roomid, chatid, payload, payloadsms, 
                encoding, displayed,soundalert, reference ) values (
                $senderid, now(), 'N', '$notifytype',$subtype_quoted,
                '','$email','$sms',
                $row[providerid], '$row[mobile]', $roomid, $chatid, 
                $payload_quoted, $payloadsms_quoted, 
                $encoding_quoted, '$status','$soundalert','$reference'
                )
            ");
        } else {
        //Invited Member

            $result = do_mysqli_query("1"," 
                select name, email, sms from invites where chatid=$chatid 
                ");
            if( $row = do_mysqli_fetch("1",$result)){

                do_mysqli_query("1"," 
                    insert into notification (
                    providerid, notifydate, status, notifytype, notifysubtype,
                    name, email, sms, 
                    recipientid, mobile, roomid, chatid, payload, payloadsms, 
                    encoding, displayed,soundalert, reference ) values (
                    $senderid, now(), 'N', '$notifytype',$subtype_quoted,
                    '$row[name]','$row[email]','$row[sms]',
                    0, 'N', null, $chatid, 
                    $payload_quoted, $payloadsms_quoted, 
                    $encoding_quoted, 'N','$soundalert',''
                    )
                ");

            }

        }
}

function QuoteItOrNull($string)
{
    if($string != null){
        return $string = "'".$string."'";
    } 
    return "null";
    
}


?>
