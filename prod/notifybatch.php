<?php
session_start();
set_time_limit ( 60 );
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("notify.inc.php");
require_once("sendmail.php");
require ("SmsInterface.inc");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
require ("aws.php");

    if($batchruns !='Y') {
        exit();
    }
    
    $notifygroup = '';
    try {
        if(sizeof($argv)>1) {
            $notifygroup =  $argv[1];
        }
    }
    catch( Exception $e) {
    }
    
    /*
    //Process RP (Room Notifications only at top of the hour)
    if($notifygroup === '*' || $notifygroup === 'X' || $notifygroup=== '') {
        $notifyfilter = "";// and notification.notifytype not in ('RP') ";
        $maxruns = 30;
    }
    else {
        $notifyfilter = " and notification.notifytype='$notifygroup' ";
        $maxruns = 1;
    }
    */
    try {

        $maxruns = 30;
        $time_start = microtime(true);
        for($i=0;$i < $maxruns;$i++){


            $result = pdo_query("1","
                select distinct notification.recipientid
                from notification
                where notification.status='N' 
                order by recipientid
                    ");
            while( $row = pdo_fetch($result)){

                NotifyRun($row['recipientid'] );
                //Don't Let this routine run past 1 minute
                $time_check = microtime(true);
                if( ($time_check - $time_start) > 55 ){

                    exit();
                }
            }

            sleep(3);
        }
    }
    catch( Exception $e) {
        error_log("NotifyBatch Crashed", 0);        
        exit();
    }
    /******************************************
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     */
    
    
    
    function NotifyRun( $recipientid  )
    {
        global $time_start;
        
        $result = pdo_query("1","
            select 
                notification.providerid, notification.notifydate, notification.status, 
                notification.notifytype, notification.notifysubtype,
                notification.email, notification.sms as notifysms,
                (select sms from sms where sms.providerid = notification.recipientid  ) as smsencrypted,
                (select encoding from sms where sms.providerid = notification.recipientid  ) as smsencoding,
                (select notificationflags from provider where provider.providerid = notification.recipientid ) as notificationflags,
                (select 'Y' from followers where followers.providerid = notification.providerid and followers.followerid = notification.recipientid ) as followed,
                notification.name, 
                notification.recipientid, 
                notification.notifyid, notification.mobile, notification.soundalert,
                convert(provider.providername using UTF8) as providername, 
                provider.alias, provider.name2,
                provider.companyname, provider.verified,
                (select notifications from provider where 
                    provider.providerid = notification.recipientid) as notifications,                
                provider.replyemail, notification.roomid, notification.chatid, 
                notification.payload, notification.payloadsms, notification.encoding
            from notification
            left join provider on notification.providerid = provider.providerid
            where notification.status='N' and recipientid=$recipientid
            and (provider.active = 'Y' or notification.providerid=0 or notification.providerid=1)
            order by notification.notifytype, notification.roomid, notification.notifydate asc 
                ");
        $email_throttle_limit = 100;
        $count = 0;
        $party = new stdClass();
        $lastnotifytype = '';
        $lastroomid = 0;
        while( $row = pdo_fetch($result)){
            
            $time_check = microtime(true);
            if( ($time_check - $time_start) > 55 ){
            
                exit();
            }
            
            
            //Same Room  Notification so eliminate dups
            if($row['notifytype'] == 'RP' &&
               $lastnotifytype == 'RP' && 
               $lastroomid == $row['roomid']){
                
                //Cancel out other notifications
                pdo_query("1","update notification set status='Y' 
                        where notifytype in ('RP') and 
                        roomid = $lastroomid and 
                        recipientid=$recipientid and status='N' "
                        );
                continue;
                 
            }
            //if Followed Only - ignore non followed
            if(strstr($row['notificationflags'],"F")!==false && $row['followed']!=='Y'){
                pdo_query("1","update notification set notifymethod='F', status='Y' where notifyid = $row[notifyid]");
                continue;
            }

            $party->followed = $row['followed'];
            $party->notifytype = $row['notifytype'];
            $party->notifysubtype = $row['notifysubtype'];
            $party->notificationsenabled = $row['notifications'];
            $party->notificationflags = $row['notificationflags'];
            $party->providerid = $row['providerid'];
            $party->payload = $row['payload'];
            $party->payloadsms = $row['payloadsms'];
            $party->encoding = $row['encoding'];
            $party->email = $row['email'];
            $party->sms = CleanPhone( DecryptText($row['smsencrypted'], $row['smsencoding'],$row['providerid']) );
            //LogDebug("$row[smsencoding]","$party->sms");
            if( $row['notifysms']!=''){
                $party->sms = $row['notifysms'];
            }
            if($party->sms == '+1') {
                $party->sms = "";
            }
            $party->name = $row['name'];
            $party->mobile = $row['mobile'];
            $party->recipientid = $row['recipientid'];

            $party->providername = utf8_decode($row['providername']);
            $party->verified = $row['verified'];
            $party->name2 = $row['name2'];
            $party->replyemail = $row['replyemail'];
            $party->alias = rtrim($row['alias']);
            $party->companyname = '';
            if($row['companyname']!='') {
                $party->companyname = " - ".$row['companyname'];
            }
            if(($party->notifytype == 'RP' || $party->notifytype == 'RL') && $party->name2!=''){
                
                $party->providername = $party->name2;
            }
            
            if($party->alias!='') {
                $party->providername = $party->alias;
            }
            if($row['providerid']==0 ){
                $party->providername = 'Anonymous';
            }
            if($row['providerid']==1 ){
                $party->providername = 'Tech';
            }
            $party->providername = utf8_decode($party->providername);
            $party->notifyid = $row['notifyid'];

            $party->roomid = $row['roomid'];
            $party->chatid = $row['chatid'];
            $party->chatowner = false;
            $party->inviteid = "$party->providerid-$party->chatid";
            if($party->chatid > 0 ){
                
                $result2 = pdo_query("1","
                    select owner from chatmaster where chatid=$party->chatid
                    and owner = $party->recipientid
                    ");
                if($row2 = pdo_fetch($result)){
                    
                    $party->chatowner = true;
                }
                $result2 = pdo_query("1","
                    select inviteid from invites where chatid=$party->chatid
                    and providerid = $party->providerid
                    ");
                if($row2 = pdo_fetch($result)){
                    
                    $party->inviteid = $row2['inviteid'];
                }
                
            }
            
            
            
            
            $party->room = "";
            //$party->soundalert = '0';
            $party->soundalert = $row['soundalert'];
            if($row['notifysubtype']=='LV'){
                $party->soundalert = '2';
            }
            
            if(intval($party->roomid)>0 ){
                
                $result2 = pdo_query("1","
                    select 
                    roominfo.room, roominfo.soundalert, roominfo.anonymousflag
                    from statusroom 
                    left join roominfo on statusroom.roomid = roominfo.roomid
                    where statusroom.roomid = $party->roomid
                    and statusroom.owner = statusroom.providerid
                    ");
                if($row2 = pdo_fetch($result)){
                    
                    $party->room = $row2['room'];
                    $party->anonymous = $row2['anonymousflag'];
                    if($row2['anonymousflag']=='Y'){
                        $party->providername = 'Anonymous';
                        $party->providerid = 0;
                    }
                }
            }

            
            $notify = CreateNotificationMessage( $party );
            
            $notifymethod = '';
            $status = false;
            
            //Check for Blank ARN or New Users - Hold off for later if so
            if( NotificationCheck($party )){

                //if($party->notifytype=='CI'){
                //    SmsNotification( $party->providerid, "BraxSecureNet ".$notify->notificationmessage, $party->sms, $party->notifytype, $party->recipientid );
                //}
                
                $status = Notification($notify->notificationmessage, $party->providerid, $party->recipientid, $party->notifytype, $party->notifysubtype, $party->soundalert, $party->replyemail, $party->notificationflags );
                if($status){
                    
                    $notifymethod = 'N'; 
                    
                    //If notification Success Send Email anyway if they don't respond in chat
                    EmailNotification( $notify->emailsubject, $notify->emailmessage, $party, false );
                }
            }
                
            if(!$status) {
                $status = SmsNotification($party->providerid, $notify->textmessage, $party->sms, $party->notifytype, $party->recipientid );
                if($status){

                   $notifymethod = 'T'; 
                    //If notification Success Send Email anyway if they don't respond in chat
                   EmailNotification( $notify->emailsubject, $notify->emailmessage, $party, false );
                }
            }
                
            if(!$status) {
                $notifymethod = "?";
                $status = EmailNotification( $notify->emailsubject, $notify->emailmessage, $party, true );
                if($status){

                   $notifymethod = 'E'; 
                }
            }

            //Single Pass Only - Remove regardless of Status
            pdo_query("1","update notification set notifymethod='$notifymethod', status='Y' where notifyid = $party->notifyid");
            //echo "notifyid=$notifyid";
            
            $lastnotifytype = $row['notifytype'];
            $lastroomid = $row['roomid'];
        }
    }
    
    function NotificationCheck( $party)
    {
        if( intval($party->recipientid) == 0){
            //Continue Loop
            return true;
        }
        //This is a new invite (non-user)
        if( intval($party->notifytype) == 'C' || intval($party->notifytype) == 'TXT' ){
            //Continue Loop
            return true;
        }
        
        $result2 = pdo_query("1","select arn, platform, token from notifytokens where providerid=$party->recipientid and token!='' and arn='' and status='Y' ");
        while($row2 = pdo_fetch($result))
        {   
            //blank ARN so HOLD OFF
            return false;
        }
        return true;
    }

    function Notification( $message, $providerid, $recipientid, $notifytype, $notifysubtype, $soundalert, $replyemail, $notificationflags )
    {
        if($message=='') {
            return false;
        }
        if( intval($recipientid) == 0 ){
            return false;
        }
        
        if($notifytype == 'EC' || $notifytype == 'CM') {//Existing user initiate chat
            //no action
            return false;
        }

        
        //Disabled Notifications by Type
        if($notifytype == 'CP' && $notifysubtype == 'LV' && strstr($notificationflags,"L")!==false){
            pdo_query("1","update alertrefresh set lastnotified = null where providerid = $recipientid ");
            return true;
        }
        if($notifytype == 'CP' && $notifysubtype != 'LV' && strstr($notificationflags,"C")!==false){
            pdo_query("1","update alertrefresh set lastnotified = null where providerid = $recipientid ");
            return true;
        }
        if(($notifytype == 'RP' || $notifytype == 'TK')  && strstr($notificationflags,"R")!==false){
            pdo_query("1","update alertrefresh set lastnotified = null where providerid = $recipientid ");
            return true;
        }
        
        if(($notifytype == 'CI' )  && strstr($notificationflags,"S")!==false){
            pdo_query("1","update alertrefresh set lastnotified = null where providerid = $recipientid ");
            return true;
        }
        
        
        $soundandroid = "default";
        
        if(intval($soundalert)==1 ){
            $sound = "www/ebs.caf";       
            $soundandroid = "default";
        } else
        if(intval($soundalert)==0){
            $sound = "www/cork.wav";       
            $soundandroid = "default";
        } else {
            $sound = "www/tinybell.wav";            
            $soundandroid = "default";
        } 
        if(strstr($notificationflags,"S")!==false){
            $sound = "";            
            $soundandroid = "";
        }
        
        //Set Limits for Others -- No limit to notification to self
        $result = pdo_query("1","
            select timestampdiff( SECOND, notifydate, now() ) as diff, displayed from 
            notification where recipientid = $recipientid and providerid = $providerid
            and notifytype='$notifytype' and recipientid!=providerid
            order by notifydate desc limit 1
                ");
        if($row = pdo_fetch($result)){
            
            $timelimit = 60 * 5; //5 minutes
            
            /*
             * This is to not irritate user
             * Room and Live Notifications Only
             * Chat has no limit
             * 
             */
            
            //If same notification type in last 5 minutes and hasn't been seen, don't repeat
            /*
             * User is actively checking app so keep sending notifications but 
             * bunch up in 5 minute groups
             */
            if($notifytype!='CP' && $notifytype!='CI'){
                if( intval($row['diff'])< $timelimit && $row['displayed']=='Y'){
                    return true;
                }
            }
            //If same notification type in last 60 minutes and hasn't been seen, don't repeat
            /*
             * User hasn't gone to app so let's not barrage with notifications
             * limit to 1 per hour at the most
             */
            if($notifytype!='CP' && $notifytype!='CI'){
                if( intval($row['diff'])< $timelimit*12*4 && $row['displayed']=='N'){
                    return true;
                }
            }
            
        }
        
        //$message = str_replace('\"','"',$message);
        //$message = html_entity_decode($message);
        //$message = addslashes($message);
        
        $result = false;
        $success = 0;
        //Users are Mobile - Send 
        $result2 = pdo_query("1","
            select notifytokens.arn, notifytokens.platform, notifytokens.token, 
            provider.notificationflags 
            from notifytokens 
            left join provider on provider.providerid = notifytokens.providerid
            where notifytokens.providerid=$recipientid and notifytokens.arn!='' and notifytokens.status in ('Y','E') 
            and provider.active='Y'
            
            ");
        while($row2 = pdo_fetch($result)){
            
            if( $row2['arn']==''){
                //arn pending
                //return 2; //PENDING
            }
            //if( strstr($row2['notificationflags'],"S") !== false) {       
            //    $sound = '';
            //    $soundandroid = 'cork.wav';
            //}
            
            $msgjson = "";
            //echo "$row2[platform]<br>";
            if($row2['platform']=='ios'){
                
                $arr_aps = array( "aps" => array( "alert" => $message, "sound" => "$sound", "badge" => 0     ) );
                $msg_aps = addslashes(json_encode($arr_aps));
                $msgjson = "{ \"APNS\" : \"$msg_aps\" }";
                //echo $msgjson;
                $jsonflag = true;        
            }
            if($row2['platform']=='android' || $row2['platform']=='chrome'){
                
                $arr_gcm = array( 
                    "data" => array( 
                       "message" => $message, 
                       "sound" => "$soundandroid"
                    ),
                    "time_to_live" => 32400
                    );
                    //"collapse_key" => "chat");
                $msg_gcm = addslashes(json_encode($arr_gcm));
                $msgjson = "{ \"GCM\" : \"$msg_gcm\" }";
                $jsonflag = true;        
                /*
                {
                    "GCM":"{ \"data\":
                                {\"message\":\"Brax.Me Notification\"}
                            ,\"time_to_live\": 3600
                            ,\"collapse_key\":\"chat\"
                          }"
                }                
                 * 
                 */
            }
            
            //Trigger Refresh
            pdo_query("1","update alertrefresh set lastnotified = null where providerid = $recipientid  and lastnotified is not null");
            
            /*
             * Issue with Chrome Notifications for Future:
             * Is single token to chrome? What if multiple chromes?
             * Also note that it is easy to disable notification for app in Chrome so sometimes forgotten
             */
            $arn = $row2['arn'];                
            try {
                //Reset Endpoint to ENABLED
                setSnsEndpointAttributes( $arn, "" );
            }
            catch (Exception $err)
            {
                //echo "SNS Error $err<br>";
                //pdo_query("1","delete from notifytokens where arn='$arn' and providerid=$recipientid ");
            }
            
            try {
                $notifyresult = publishSnsNotification("$arn",$msgjson, $jsonflag);
                if($notifyresult){
                    $success++;
                    pdo_query("1","update notifytokens set status='Y', error='OK' where arn='$arn' and providerid=$recipientid ");
                }
            }
            catch (Exception $err) {
                
                echo "SNS Error $err<br>";
                $errsql = tvalidator("PURIFY","{$err->getMessage()}");
                pdo_query("1","update notifytokens set status='E', error='$errsql' where arn='$arn' and providerid=$recipientid ");
            }
        }
        if($success > 0 ){
            $result = true;
        }
        return $result;
    }
    
    function SmsNotification( $providerid, $textmessage, $sms, $notifytype, $recipientid )
    {
        global $rootserver;
        global $installfolder;
        global $appname;
        //Exclude Invalid Phone Numbers
        if($textmessage=='' || $sms == '') {
            return false;
        }

        //If US Phone Number not 10 digit - error
        //Send to US SMS Only for now
        if(strlen($sms)!=10 && $sms[0]!='+' ){
            return false;
        }
        if(strlen($sms)!=12 && $sms[0]='+' && $sms[1]='1' ){
            return false;
        }
        
        $timelimit = 3600*24;
        if($notifytype == 'CP')
        {
            $timelimit = 3600;
        }
        if(intval($recipientid) > 0)
        {
            $result = pdo_query("1","
                select timestampdiff( SECOND, sentdate, now() ) as diff from 
                smslog where recipientid = $recipientid and providerid = $providerid
                and source='$notifytype'
                order by sentdate desc limit 1
                    ");
            if($row = pdo_fetch($result))
            {
                //Do not send further texts if the same alert occurred within the last hour
                if( intval($row['diff'])< $timelimit)
                {
                    return true;
                }

            }
        }
        $senderid = str_replace(".","",$appname);
        publishSMSNotification( "$senderid", $textmessage, $sms );
        
        pdo_query("1","insert into smslog (providerid, recipientid, sms, sentdate, source) values ($providerid, $recipientid, '$sms', now(),'$notifytype' )");
        return true;
    }        
    function EmailNotification( $subject, $message, $party, $mainsend )
    {
        $email = $party->email;
        $notifytype = $party->notifytype;
        $providerid = $party->recipientid;
        $roomid = $party->roomid;
        //$mainsend =  $party->soundalert;        
        
        
        if( $message=='' || $email=='') {
            return false;
        }
        //Non-Subscribers have verified = ''
        //Verification will not be required for chat invite for now
        if( $party->verified == 'N' && $notifytype!='C'){
            //return false;
        }
        //No Email on Room Notifications
        if(!$mainsend && substr($notifytype,0,1)=='R'){
            return true;
        }
        if(intval($roomid) > 1 && ($notifytype == 'RP' || $notifytype='RL') ){
        
            $result = pdo_query("1"," 
                select *
                from statusroom where
                providerid=$providerid and roomid=$roomid
                and lastemail is not null and
                timestampdiff(HOUR, lastemail, now() )< 4
            ");
            if($row = pdo_fetch($result))
            {
                //Do not re-email -- too frequent
                return true;
            }
        }
        if($notifytype == 'CP' ){
        
            //No Email notifications to the owner - not necessary
            if($party->chatowner){
                return true;
            }
            
            /* Any chat notifications in the last 4 hours
             * unseen, if so email would be already sent so
             * don't keep resending. If new chat, no notifications
             * will be found in the last 4 hours so this will be false
             */
            $result = pdo_query("1"," 
                select *
                from notification where
                recipientid=$providerid and 
                notifytype ='CP' and 
                displayed = 'N' and status='Y' and
                timestampdiff(HOUR, notifydate, now() )< 4
            ");
            if($row = pdo_fetch($result)){
            
                //Do not re-email -- too frequent
                return true;
            }
            /* This one checks only for unseen notifications
             * meaning user did not open the app. If they did
             * open the app in the last hour, let's not do emai
             * email is only for those that don't open
             */
            $result = pdo_query("1"," 
                select *
                from notification where
                recipientid=$providerid and recipientid!=0 and 
                notifytype ='CP' and 
                displayed = 'Y' and status='Y' and
                timestampdiff(MINUTE, notifydate, now() )< 60
            ");
            if($row = pdo_fetch($result)){
            
                
                //Do not re-email -- too frequent
                return true;
            }
        }
        if($notifytype == "C"){
            //New Chat Invite to a non-member 
            //Show sender information for trust
            $status = SendMailV2("0", $subject, $message, $message, $party->providername, $party->replyemail, $party->name, $email );
        } else {
            $status = SendMail("0", "$subject", "$message", "$message", "$party->name", "$email" );
        }
        if($status)
        {
            if(intval($roomid) > 1 && ($notifytype == 'RP' || $notifytype='RL') ){
            
                pdo_query("1"," 
                    update statusroom set lastemail=now() where 
                    providerid=$providerid and roomid=$roomid
                        ");
            }
        }
        //SendMail("0", "$subject", "$message", "$message", "$party->name", "rob@bytz.io" );
        
        return $status;
    }

    
    
    function CleanPhone( $phone )
    {
        if($phone == ""){
            return "";
        }
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );
        if($phone[0]!='+') {
            $phone = "+1".$phone;
        }
        
        if($phone == "+1") {
            $phone = "";
        }
        
        return $phone;
    }
    
    function CreateNotificationMessage( $party )
    {
            global $rootserver;
            global $installfolder;
            global $appname;
            global $prodserver;
            global $startupphp;
            
            $notify['notificationmessage'] = "";
            $notify['textmessage'] = "";
            $notify['emailsubject'] = "";
            $notify['emailmessage'] = "";
            
            //$prodserver = "https://brax.me";
            
            $invitationUrl = "$prodserver/$startupphp";
            if($party->mobile=='Y') {
                $invitationUrlMobile = "braxme://";
            }
            else {
                $invitationUrlMobile = $invitationUrl;
            }
            //New Chat Invite to Non Member
            if($party->notifytype == 'C' ){
            
                $invitationUrl = "$prodserver/invite/$party->inviteid";

                $notify['emailsubject'] = "$party->providername$party->companyname $party->replyemail  - Secure Message Pending";
                $notify['emailmessage'] = "
                <div style='width:600px;background-color:white;color:black;padding:40px;font-family:helvetica, Arial, san-serif;font-size:14px'>
                    <h2 style='color:steelblue'>Hi $party->name. You have a pending secure message 
                    from $party->providername$party->companyname  $party->replyemail.</h2>
                    <br>
                    Your message party uses this platform to protect privacy. Only the sender and receiver 
                    can view a conversation.
                    <br><br>
                    <b>Please sign up on the $appname app using 
                    the link below (free) to view your message.</b>
                    <br><br>
                    <a href='$invitationUrl'>
                    $invitationUrl
                    </a>
                    <br>
                    <br>
                        <b>For the best experience, download the free mobile apps from AppStore or Google Play and use that after sign up.</b>
                        <br><br>
                        <div style='display:inline;background-color:transparent;padding:10px;width:auto;margin:auto'>
                        <a href='http://itunes.com/apps/braxme' style='text-decoration:none'>
                         <img src='$prodserver/img/appStore.png' height=50 style='width:auto' >
                        </a>
                         &nbsp;&nbsp;
                        <a href='https://play.google.com/store/apps/details?id=me.brax.app1' style='text-decoration:none'>
                         <img src='$prodserver/img/androidplay.png' height=50 style='width:auto' >
                        </a>
                        </div>

                        <br>
                        <br>
                    <a href='$prodserver'>
                    <img src='$prodserver/img/lock.png' height=30 style='width:auto' />
                    </a>
                    <br>
                    Secure Messaging Platform / HIPAA Compliant
                    <br>


                </div>
                ";
                $notify['textmessage'] = "$appname message from $party->providername$party->companyname - Invite Link - $invitationUrl";
            }
            //New Chat Invite to Existing Member (EC Existing - Chat)
            if($party->notifytype == 'EC' && $party->notificationsenabled != 'N'){
            
                $notify['notificationmessage'] =  "Chat $party->providername$party->companyname";
                $notify['textmessage'] = "";//Chat $party->providername$party->companyname ";
                /*
                $notify['emailsubject'] = "New Secure Chat - $party->providername$party->companyname ";
                $notify['emailmessage'] = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica, Arial, san-serif;font-size:14px'>
                        <h2 style='color:steelblue'>Hi $party->name. You have a new secure chat 
                        message from $party->providername$party->companyname</h2><br>
                        Please login to $appname to view your conversation.
                        <br><br>

                        <a href='$invitationUrlMobile'>
                        $invitationUrlMobile
                        </a>
                        <br><br>
                        <a href='$prodserver'>
                        <img src='$prodserver/img/lock.png' height=30 style='width:auto' /><br>
                        </a>
                    </div>
                    ";
                 * 
                 */
            }
            //Chat Initial Reply
            if($party->notifytype == 'CN' && $party->notificationsenabled != 'N'){
            
                $notify['notificationmessage'] = "Chat Reply $party->providername ";
                $notify['textmessage'] = "Chat Reply $party->providername $invitationUrl";
                $notify['emailsubject'] = "Chat Reply $party->providername ";
                $notify['emailmessage'] = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                        <h2 style='color:steelblue'>Hi $party->name. $party->providername$party->companyname replied to your chat message.</h2><br>
                        <br>
                        Please login to $appname to see your secure conversation.
                        <br><br>

                        <a href='$invitationUrlMobile'>
                        $invitationUrlMobile
                        </a>
                        <br>
                        <br>
                        <a href='$prodserver'>
                        <img src='$prodserver/img/lock.png' height=30 style='width:auto' /><br>
                        </a>
                    </div>
                    ";

            }
            //Chat Poke per Post
            if($party->notifytype == 'CP' && $party->notificationsenabled != 'N' ){
            
                $notify['notificationmessage'] = $party->providername." (Chat) - ".DecryptChat($party->payload, $party->encoding,"$party->chatid","" );
            }
            //Chat Poke per Post
            if($party->notifytype == 'CF' && $party->notificationsenabled != 'N' ){
            
                $notify['notificationmessage'] = $party->providername." followed you";
            }
            if($party->notifytype == 'CS' && $party->notificationsenabled != 'N' ){
            
                $notify['notificationmessage'] = "Store Sale";
            }
            if($party->notifytype == 'CI'  ){
            
                $notify['notificationmessage'] = $party->payload;
            }
            if($party->notifytype == 'CR' && $party->notificationsenabled != 'N' ){
            
                $notify['notificationmessage'] = "BytzVPN Message";
            }
            //Chat Poke per Post to EMAIL
            if($party->notifytype == 'CM'  ){
                $temp = rand ( 100000 , 999999 );

                $notify['emailsubject'] = "$appname SecureChat - $party->providername ";
                $notify['emailmessage'] = "
                    <div style='width:600px;background-color:white;color:black;padding:40px;font-family:helvetica'>
                        <h3 style='color:steelblue'>$party->providername has new a message.</h3><br>
                        Please view your secure conversation
                        <a href='$rootserver/c/$temp$party->chatid'>
                            here                        
                        </a>.
                        <br><br><br>
                        <a href='$rootserver'>
                        <img src='$rootserver/img/lock.png' height=30 style='width:auto' /><br>
                        </a>
                        Secure Messaging/HIPAA Compliant
                    </div>
                    ";
                 
                 
            }
            //Event Reminder
            if(($party->notifytype == 'E1' || $party->notifytype == 'E2') && $party->notificationsenabled != 'N'){
            
                $notify['notificationmessage'] = base64_decode($party->payload );
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            //Room Poke
            if($party->notifytype == 'RP' && $party->notificationsenabled != 'N'){
            
                $notify['notificationmessage'] = "Room Activity: $party->room / $party->providername ";
            }
            //Room Poke
            if($party->notifytype == 'RL'  && $party->notificationsenabled != 'N' ){
            
                $notify['notificationmessage'] = "Room Like: $party->room / $party->providername ";
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            
            //Token Donate
            if($party->notifytype == 'RP' && $party->notifysubtype=='TK'  && $party->notificationsenabled != 'N' ){
            
                $notify['notificationmessage'] = "$party->payload from $party->providername ";
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            
            //Email Notification to Existing Member
            if($party->notifytype == 'EN'  && $party->notificationsenabled != 'N' ){
            
                $notify['notificationmessage'] = "Secure Email from - $party->providername ";
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            
            //Chat Poke per Post
            if($party->notifytype == 'T'){
            
                $payload = DecryptChat($party->payload, $party->encoding,"$party->recipientid");
                $notify['notificationmessage'] = "";
                $highalert = "";
                if($party->soundalert =='1'){
                    $highalert = "HIGH ALERT ";
                }
                if( $party->recipientid != 0 && $party->recipientid!='' ){
                    $notify['notificationmessage'] = $highalert.$party->providername." - ".DecryptChat($party->payload, $party->encoding,"$party->recipientid","" );
                }
                $notify['textmessage'] = $highalert.$party->providername." - ".DecryptChat($party->payloadsms, $party->encoding,"$party->recipientid","" );
                $notify['emailsubject'] = $highalert."Message from $party->providername";
                $notify['emailmessage'] = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica, Arial, san-serif;font-size:14px'>
                        <h3 style='color:steelblue'>$highalert Message from $party->providername / $party->room</h3><br>
                        $payload
                        <br>
                        <br>
                        Please login to $appname to view additional messages. 
                        <br><br>
                        <a href='$prodserver'>
                        <img src='$prodserver/img/lock.png' style='height:30px;width:auto' /><br>
                        </a>
                    </div>
                    ";
            }
            
            //Chat Poke per Post
            if($party->notifytype == 'TXT'){
            
                $payload = DecryptChat($party->payload, $party->encoding,"$party->recipientid");
                $notify['notificationmessage'] = "";
                if( $party->recipientid != 0 && $party->recipientid!='' ){
                    $notify['notificationmessage'] = $party->providername." - ".DecryptChat($party->payload, $party->encoding,"$party->recipientid","" );
                }
                $notify['textmessage'] = $highalert.$party->providername." - ".DecryptChat($party->payloadsms, $party->encoding,"$party->recipientid","" );
            }            
            
            if( $notify['notificationmessage']!=''){
                $notify['notificationmessage'] = str_replace("\\n","", $notify['notificationmessage'] );
                $notify['notificationmessage'] = str_replace("\\r","", $notify['notificationmessage'] );
                $notify['notificationmessage'] = str_replace("'","", $notify['notificationmessage'] );
                $notify['notificationmessage'] = str_replace('"',"", $notify['notificationmessage'] );
                $notify['notificationmessage'] = addslashes($notify['notificationmessage']);
                $notify['notificationmessage'] = removeEmoji($notify['notificationmessage']);
                $notify['notificationmessage'] = utf8_encode(html_entity_decode($notify['notificationmessage']));    
            }
            
            return (object) $notify;
    
    }
    function removeEmoji($text) {
        
        return preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1FFFF}][\x{FE00}-\x{FEFF}]?/u', '', $text);

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F300}-\x{1FFFF}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);


    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);

    return $clean_text;
}    
        
?>