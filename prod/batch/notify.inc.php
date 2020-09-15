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
        pdo_query("1"," 
            insert into csvtext (ownerid, message, sms, uploaded, status, error )
            values (?, ?,?,now(), 'N','' )
                ",$senderid, $messagesql,$phone);
        
}
function BatchSendText()
{
        $encoding = $_SESSION['responseencoding'];
        
        $result = pdo_query("1","
            select id, ownerid, message, sms from csvtext where status='N'
            limit 100
            ");
        while($row = pdo_fetch($result)){
            
            $payload = EncryptChat ($row['message'],"0","" );
            $payloadsms = EncryptChat ($row['message'],"0","" );

            GenerateNotification( 
            $row['ownerid'], 
            0, 
            "TXT", null, 
            0, 0, 
            $payload, $payloadsms,
            $encoding, "", $row['sms'] );
            
            pdo_query("1","update csvtext set status='Y' where id=$row[id] and status='N' ");
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
    
        $result = pdo_query("1"," 
            select notifications, soundalert from roominfo where roomid=?
            ",array($roomid));
        if( $row = pdo_fetch($result)){
        
            if($row['notifications']!='Y' ){
            
               // return;
            }
            $soundalert = $row['soundalert'];
        }
        
    }
    
    pdo_query("1"," 
        insert into notification (
        providerid, notifydate, status, notifytype, notifysubtype,
        name, email, sms, 
        recipientid, mobile, roomid, chatid, payload, payloadsms, 
        encoding, displayed, soundalert,reference ) values (
        ?, now(), 'N', ?,null,
        ?,?,?,
        0, 'N', ?, null, 
        ?,?,?, 'N',?,''
        )
    ",array($senderid,$notifytype,$name,$email,$sms,$roomid,$payloadsms_quoted,$payloadsms_quoted,$encoding_quoted,$soundalert));

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
    
    $result = pdo_query("1","
        select notificationflags from provider where providerid = ?
    ",array($providerid));
    if($row = pdo_fetch($result)){
        if(strpos("$row[notificationflags]","M")!==FALSE){
            $encodeshort = "Message";
            $encoding = "PLAINTEXT";
        }
    }
    
    //Photo upload
    if($subtype == 'P'){
        $result = pdo_query("1","select radiostation from chatmaster where chatid=? and radiostation='Y",array($chatid));
        if( $row = pdo_fetch($result)){
            return;
        }
        $subtype='';
    }

    //Community Check - if this a room spawned chat - limit notifications
    if($subtype == ''){
        $result = pdo_query("1",
                "select roomid from chatmaster where chatid=? "
                . "and roomid is not null and roomid > 0  ",array($chatid));
        if( $row = pdo_fetch($result)){
            $subtype='CY';
        }
    }
    
    pdo_query("1","
        insert into notifyrequest 
        ( requestdate, status, providerid, 
            chatid, encodeshort, encoding, 
            roomid, subtype, postid, shareid, anonymous ) 
            values
        (
          now(),'N',?, ?, ?, ?, 
              ?, ?, ?, ?,? 
        )
    ",array(
          $providerid, $chatid, $encodeshort, $encoding, 
          $roomid, $subtype, $postid, $shareid,$anonymous 
        
    ));
    
    
}


function ChatNotification($providerid, $chatid, $encodeshort, $encoding, $subtype )
{
 
    if($providerid == '' || $chatid == ''){
        return;
    }
    
    //Live Notifications
    if($subtype == 'LV' && $chatid !=''){
        $result = pdo_query("1",
            "
            delete from notification where chatid = ? and
            notifytype='CP' and notifysubtype = 'LV' and notifyid > 0
            ",array($chatid));
        
    }
    
    
    
    $result = pdo_query("1",
        "
        select provider.providerid, provider.replyemail, verified,
        provider.providername, provider.mobile, chatmaster.keyhash,
        provider.notificationflags,
        (select 'Y' from notifymute where providerid = chatmembers.providerid and id = chatmembers.chatid and idtype='C' ) as mute,
        (select techsupport from chatmembers cm2 where providerid = ?
            and chatmembers.chatid = cm2.chatid ) as techsupport,
        (select 'Y' from ban where ban.chatid = chatmaster.chatid and ban.banid in (select banid from provider where providerid = ?) ) as banned
        from chatmembers
        left join chatmaster on chatmaster.chatid = chatmembers.chatid
        left join provider on provider.providerid = chatmembers.providerid
        left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = ?
        left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = ?
        where 
        chatmembers.providerid != ? and
        
        chatmembers.chatid=? and (provider.notifications = 'Y' or provider.notifications is null )
        and blocked1.blockee is null and blocked2.blocker is null
        and datediff(curdate(), provider.lastaccess) < 14
        order by chatmembers.chatid desc           
        
        ",array($providerid, $providerid, $providerid, $providerid,$providerid,chatid));
    while($row = pdo_fetch($result)){
    
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
    $result = pdo_query("1","
        select external from roominfo where roomid=? and external='Y'
        ",array($roomid));
    if($row = pdo_fetch($result)){
        return;
    }

    
    pdo_query("1","
        insert into notifyrequest 
        ( requestdate, status, providerid, 
            chatid, encodeshort, encoding, 
            roomid, subtype, postid, shareid, anonymous ) 
            values
        (
          now(),'N',?, ?, ?, ?, 
              ?, ?, ?, ?,? 
        )
    ",array(
          $providerid, $chatid, $encodeshort, $encoding, 
          $roomid, $subtype, $postid, $shareid,$anonymous 
        
    ));
    
    
}


function RoomNotification($providerid, $roomid, $subtype, $shareid, $postid, $anonymous )
{
    
    //Disable Like Notification for now
    if( $subtype == 'L'){
        return;
    }
    
    $soundalert = '0';
    if( intval($roomid)>1  ){
    
        $result = pdo_query("1"," 
            select notifications, soundalert from roominfo where roomid=? 
            ",array($roomid));
        if( $row = pdo_fetch($result)){
        
            if($row['notifications']!='Y'){
            
                return;
            }
            $soundalert = $row['soundalert'];
        }
        
    }
    
    if($subtype=='L'){
        $result = pdo_query("1",
        "
            select provider.providerid,provider.replyemail, provider.verified, provider.providername,
            (select anonymousflag from roominfo where roominfo.roomid = statusroom.roomid ) as anonymousflag
            from statusroom 
            left join provider on statusroom.providerid = provider.providerid
            left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = ?
            left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = ?
            where statusroom.roomid = ?
            and provider.active = 'Y' and
            ( statusroom.providerid=statusroom.owner or statusroom.providerid in
                (select providerid from statuspost where postid = ?)
            )
            and blocked1.blockee is null and blocked2.blocker is null
            and datediff(curdate(), provider.lastaccess) < 14
            
        ",array(
            $providerid, $providerid, $roomid, $postid
        ));
        $notifytype = 'RL';
    } else {
        $result = pdo_query("1",
        "
            select statusroom.providerid, provider.replyemail, provider.providername, 
            (select anonymousflag from roominfo where roominfo.roomid = statusroom.roomid ) as anonymousflag
            from statusroom 
            left join provider on statusroom.providerid = provider.providerid
            left join blocked blocked1 on blocked1.blockee = provider.providerid and blocked1.blocker = ?
            left join blocked blocked2 on blocked2.blocker = provider.providerid and blocked2.blockee = ?
            where statusroom.roomid = ? 
            and statusroom.providerid != ?
            and provider.active = 'Y' and  (provider.notifications = 'Y' or provider.notifications is null )
            and blocked1.blockee is null and blocked2.blocker is null
            and datediff(curdate(), provider.lastaccess) < 90
        ",array(
            $providerid, $providerid, $roomid, $providerid
        ));
        $notifytype = 'RP';
        
    }
    
    
    while( $row = pdo_fetch($result)){
    
        pdo_query("1","
            insert into statusreads 
            (providerid, shareid, postid, xaccode, actiontime, roomid ) 
            select provider.providerid, ?, ?, 'R', now(), ?
            from provider
            where provider.providerid = ? and providerid
            not in ( select providerid from statusreads 
            where roomid = ? and xaccode='R' and statusreads.providerid = provider.providerid )            
            
            ",array($shareid, $postid, $roomid,$row['providerid'], $roomid));
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

    $result = pdo_query("1","
        update notifyrequest set status = 'P' where status = 'N'
    ");
    
    $result = pdo_query("1","
        select requestid,
            requestdate, status, providerid, 
            chatid, encodeshort, encoding, 
            roomid, subtype, postid, shareid, anonymous 
        from notifyrequest 
        where status = 'P' 
        order by requestdate asc
    ");
    while($row = pdo_fetch($result)){
        //Mark it so it doesn't get called again
        pdo_query("1","update notifyrequest set status='Y' where requestid= $row[requestid]");
        
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
        $result = pdo_query("1"," 
            select time_to_sec(timediff( now(), requestdate)) as diff 
            from notifyrequest where status = 'Y' and chatid = ? and requestid!=?
            order by requestdate desc limit 1
            ",array($chatid,$requestid));
        if( $row = pdo_fetch($result)){
            $diff = $row['diff'];
            //LogDebug( "($diff)", "/$chatid Test");
            if($diff < 60){
                return false;
            }
            return true;
        }
    }
    if(intval($roomid) > 0 ){
        $result = pdo_query("1"," 
            select time_to_sec(timediff( now(), requestdate)) as diff 
            from notifyrequest where status = 'Y' and roomid = ?
             and requestid!=?
            order by requestdate desc limit 1
            ",array($roomid,$requestid));
        if( $row = pdo_fetch($result)){
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
        
        pdo_query("1"," 
            insert into notification (
            providerid, notifydate, status, notifytype, notifysubtype,
            name, email, sms, 
            recipientid, mobile, roomid, chatid, payload, payloadsms, 
            encoding, displayed,soundalert, reference, notifyread ) values (
            ?, now(), 'N', ?,null,
            '','','?',
            0, 'N', null, 0, 
            ?, ?, 
            ?, 'N','','',''
            )
        ",array(
           $senderid, $notifytype,$sms,$payload_quoted,$payloadsms_quoted,$encoding_quoted 
        ));
        return;
    }

    
    $soundalert = '0';
    if( intval($roomid)>1 && 
      ( substr($notifytype,0,1)=='R' || substr($notifytype,0,1)=='T') ){
    
        $result = pdo_query("1"," 
            select notifications, soundalert from roominfo where roomid=? 
            ",array($roomid));
        if( $row = pdo_fetch($result)){
        
            if($row['notifications']!='Y' && $notifytype!='T'){
            
                return;
            }
            $soundalert = $row['soundalert'];
        }
        
    }
    
    
    $result = pdo_query("1"," 
        select providerid, mobile, replyemail, verified,
        (select count(*) from notifytokens 
          where provider.providerid = notifytokens.providerid) 
          as tokens 
        from provider
        where providerid = ? and active='Y' and notifications = 'Y'
            ",array($recipientid));
        if( $row = pdo_fetch($result)){
        //Existing Member
        

            $status = 'N';
            $email = $row['replyemail'];
            if(strstr("$email",".account@brax.me")!==false){
                $email = "";
            }
            if( $email =='' && intval($row['tokens'])==0  ){
                $status = 'Y';

            }
            if(intval($row['tokens'])>0){
                //$email = "";
            }
            if($row['verified']=='N'){
                $email = "";
            }

            pdo_query("1"," 
                insert into notification (
                providerid, notifydate, status, notifytype, notifysubtype,
                name, email, sms, 
                recipientid, mobile, roomid, chatid, payload, payloadsms, 
                encoding, displayed,soundalert, reference ) values (
                ?, now(), 'N', ?,?,
                '',?,'',
                $row[providerid], '$row[mobile]', ?, ?, 
                ?, ?, 
                ?, ?,?,?
                )
            ",array(
                $senderid, $notifytype,$subtype_quoted,
                $email,
                $roomid, $chatid, 
                $payload_quoted, $payloadsms_quoted, 
                $encoding_quoted, $status,$soundalert,$reference
            ));
        } else {
        //Invited Member

            $result = pdo_query("1"," 
                select name, email, sms from invites where chatid=$chatid 
                ",array($chatid));
            if( $row = pdo_fetch($result)){

                pdo_query("1"," 
                    insert into notification (
                    providerid, notifydate, status, notifytype, notifysubtype,
                    name, email, sms, 
                    recipientid, mobile, roomid, chatid, payload, payloadsms, 
                    encoding, displayed,soundalert, reference ) values (
                    ?, now(), 'N', ?,?,
                    '$row[name]','$row[email]','$row[sms]',
                    0, 'N', null, ?, 
                    ?, ?, 
                    ?, 'N',?,''
                    )
            ",array(
                $senderid, $notifytype,$subtype_quoted,
                $chatid, 
                $payload_quoted, $payloadsms_quoted, 
                $encoding_quoted, $soundalert

            ));
                
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
        
        pdo_query("1"," 
            insert into notification (
            providerid, notifydate, status, notifytype, notifysubtype,
            name, email, sms, 
            recipientid, mobile, roomid, chatid, payload, payloadsms, 
            encoding, displayed,soundalert, reference, notifyread ) values (
            ?, now(), 'N', ?,null,
            '','',?,
            0, 'N', null, 0, 
            ?, ?, 
            ?, 'N','','',''
            )
        ",array(
            $senderid, $notifytype,
            $sms,
            $payload_quoted, $payloadsms_quoted, 
            $encoding_quoted
        ));
        return;
    }

    
    
    
    $result = pdo_query("1"," 
        select providerid, mobile, replyemail, 
        datediff(curdate(), lastaccess) as lastheredays,
        (select count(*) from notifytokens 
          where provider.providerid = notifytokens.providerid) 
          as tokens 
        from provider
        where providerid = ? and active='Y' and notifications = 'Y'
            ",array($recipientid));
        if( $row = pdo_fetch($result)){
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

            pdo_query("1"," 
                insert into notification (
                providerid, notifydate, status, notifytype, notifysubtype,
                name, email, sms, 
                recipientid, mobile, roomid, chatid, payload, payloadsms, 
                encoding, displayed,soundalert, reference ) values (
                ?, now(), 'N', ?,?,
                '',?,?,
                $row[providerid], '$row[mobile]', ?, ?, 
                ?, ?, 
                ?, ?,?,?
                )
            ", array(
                $senderid,$notifytype,$subtype_quoted,
                $email,$sms,
                $roomid, $chatid, 
                $payload_quoted, $payloadsms_quoted, 
                $encoding_quoted, $status,$soundalert,$reference
                
            ));
        } else {
        //Invited Member

            $result = pdo_query("1"," 
                select name, email, sms from invites where chatid=?
                ",array($chatid));
            if( $row = pdo_fetch($result)){

                pdo_query("1"," 
                    insert into notification (
                    providerid, notifydate, status, notifytype, notifysubtype,
                    name, email, sms, 
                    recipientid, mobile, roomid, chatid, payload, payloadsms, 
                    encoding, displayed,soundalert, reference ) values (
                    ?, now(), 'N', ?,?,
                    '$row[name]','$row[email]','$row[sms]',
                    0, 'N', null, ?, 
                    ?, ?, 
                    ?, 'N',?,''
                    )
                ",array(
                    $senderid, $notifytype,$subtype_quoted,
                    $chatid, 
                    $payload_quoted, $payloadsms_quoted, 
                    $encoding_quoted, $soundalert
                    
                ));

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
