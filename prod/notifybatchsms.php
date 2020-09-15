<?php
session_start();
require_once("config-pdo.php");
require_once("crypt.inc");
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
    //Process RP (Room Notifications only at top of the hour)
    if($notifygroup === '*' || $notifygroup === 'X' || $notifygroup=== '') {
        $notifyfilter = " and notification.notifytype not in ('RP') ";
        $maxruns = 30;
    }
    else {
        $notifyfilter = " and notification.notifytype='$notifygroup' ";
        $maxruns = 1;
    }
    
    
    $time_start = microtime(true);
    for($i=0;$i < $maxruns;$i++)
    {

        
        //temporary for testing
        /*
        if($i  === 0) {
            $notifyfilter = "";
        }
        else {
            $notifyfilter = " and notification.notifytype!='RP' ";
        }
         * 
         */
        
        
        $result = pdo_query("1","
            select distinct notification.recipientid
            from notification
            where notification.status='N' 
            $notifyfilter
                ");
        while( $row = pdo_fetch($result))
        {
            
            NotifyRun($row['recipientid']);
            //Don't Let this routine run past 1 minute
            $time_check = microtime(true);
            if( ($time_check - $time_start) > 55 )
            {
                exit();
            }
        }
        $time_check = microtime(true);
        if( ($time_check - $time_start) > 55 )
        {
            exit();
        }
        
        
        sleep(2);
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
    
    
    
    function NotifyRun( $recipientid )
    {
        $result = pdo_query("1","
            select 
                notification.providerid, notification.notifydate, notification.status, 
                notification.notifytype, notification.email, 
                notification.sms, notification.name, notification.recipientid, 
                notification.notifyid, notification.mobile,
                provider.providername, provider.alias, provider.name2,
                provider.companyname,
                provider.replyemail, notification.roomid, notification.chatid, 
                notification.payload, notification.payloadsms, notification.encoding
            from notification
            left join provider on notification.providerid = provider.providerid
            where notification.status='N' and recipientid=$recipientid
            and (provider.active = 'Y' or notification.providerid=0)
            order by notification.notifytype, notification.roomid, notification.notifydate asc 
                ");
        $email_throttle_limit = 100;
        $count = 0;
        $party = new stdClass();
        $lastnotifytype = '';
        $lastroomid = 0;
        while( $row = pdo_fetch($result))
        {
            //Same Room  Notification so eliminate dups
            if($row['notifytype'] == 'RP' &&
               $lastnotifytype == $row['notifytype'] && $lastroomid == $row['roomid'])
            {
                //Cancel out other notifications
                pdo_query("1","update notification set status='Y' 
                        where notifytype in ('RP') and roomid = $lastroomid and recipientid=$recipientid ");
                continue;
            }
            $party->notifytype = $row['notifytype'];
            $party->providerid = $row['providerid'];
            $party->payload = $row['payload'];
            $party->payloadsms = $row['payloadsms'];
            $party->encoding = $row['encoding'];
            $party->email = $row['email'];
            $party->sms = CleanPhone($row['sms']);
            if($party->sms == '+1') {
                $party->sms = "";
            }
            $party->name = $row['name'];
            $party->mobile = $row['mobile'];
            $party->recipientid = $row['recipientid'];

            $party->providername = $row['providername'];
            $party->name2 = $row['name2'];
            $party->replyemail = $row['replyemail'];
            $party->alias = rtrim($row['alias']);
            $party->companyname = '';
            if($row['companyname']!='') {
                $party->companyname = " - ".$row['companyname'];
            }
            if(($party->notifytype == 'RP' || $party->notifytype == 'RL') && $party->name2!='')
            {
                $party->providername = $party->name2;
            }
            
            if($party->alias!='') {
                $party->providername = $party->alias;
            }
            if($row['providerid']==0 ){
                $party->providername = 'Anonymous';
            }
            $party->notifyid = $row['notifyid'];

            $party->roomid = $row['roomid'];
            $party->chatid = $row['chatid'];
            $party->room = "";
            if(intval($party->roomid)>0 )
            {
                $result2 = pdo_query("1","select room from statusroom where roomid = $party->roomid");
                if($row2 = pdo_fetch($result))
                {
                    $party->room = $row2['room'];
                }
            }

            
            $notify = CreateNotificationMessage( $party );
            
            
            if( NotificationCheck($party->recipientid))
            {
                $notifymethod = '';
                
                $status = Notification($notify->notificationmessage, $party->recipientid, $party->notifytype );
                if($status)
                {
                   $notifymethod = 'M'; 
                }
                
                if(!$status) {
                    $status = SmsNotification($party->providerid, $notify->textmessage, $party->sms, $party->notifytype );
                    if($status)
                    {
                       $notifymethod = 'S'; 
                    }
                }
                
                if(!$status) {
                    //Stop emailing once Email Throttle Limit Reached - try again in next batch
                    if($count < $email_throttle_limit) {
                        $status = EmailNotification( $notify->emailsubject, $notify->emailmessage, $party->email, 
                                $party->notifytype, $party->recipientid, $party->roomid );
                        if($status)
                        {
                           $notifymethod = 'E'; 
                        }
                    }
                    else 
                    {
                        $status = false;
                    }
                }

                //Single Pass Only - Remove regardless of Status
                pdo_query("1","update notification set notifymethod='$notifymethod', status='Y' where notifyid = $party->notifyid");
                //echo "notifyid=$notifyid";
            }
            $lastnotifytype = $row['notifytype'];
            $lastroomid = $row['roomid'];
        }
    }
    
    function NotificationCheck( $recipientid )
    {
        
        $result2 = pdo_query("1","select arn, platform, token from notifytokens where providerid=$recipientid and token!='' and arn='' and status='Y' ");
        while($row2 = pdo_fetch($result))
        {   
            //blank ARN so HOLD OFF
            return false;
        }
        return true;
    }

    function Notification( $message, $recipientid, $notifytype )
    {
        if($message=='') {
            return false;
        }
        if($recipientid == '')
        {
            return false;
        }
        
        if($notifytype == 'EC') //Existing user initiate chat
        {
            //no action
            return true;
        }
        
        //$message = str_replace('\"','"',$message);
        $message = str_replace("\\n","", $message );
        $message = str_replace("\\r","", $message );
        $message = str_replace("'","", $message );
        $message = str_replace('"',"", $message );
        //$message = addslashes($message);
        
        $result = false;
        $success = 0;
        //Users are Mobile - Send 
        $result2 = pdo_query("1","
            select notifytokens.arn, notifytokens.platform, notifytokens.token 
            from notifytokens 
            left join provider on provider.providerid = notifytokens.providerid
            where notifytokens.providerid=$recipientid and notifytokens.arn!='' and notifytokens.status='Y' 
            and provider.active='Y'
            
            ");
        while($row2 = pdo_fetch($result))
        {
            if( $row2['arn']=='')
            {
                //arn pending
                //return 2; //PENDING
            }
            $msgjson = "";
            //echo "$row2[platform]<br>";
            if($row2['platform']=='ios')
            {
                $arr_aps = array( "aps" => array( "alert" => $message, "sound" => "ping.wav", "badge" => 1 ) );
                $msg_aps = addslashes(json_encode($arr_aps));
                $msgjson = "{ \"APNS\" : \"$msg_aps\" }";
                //echo $msgjson;
                $jsonflag = true;        
            }
            if($row2['platform']=='android')
            {
                $arr_gcm = array( 
                    "data" => array( "message" => $message),
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
            $arn = $row2['arn'];                
            try {
                $notifyresult = publishSnsNotification("$arn",$msgjson, $jsonflag);
                if($notifyresult)
                {
                    $success++;
                }
            }
            catch (Exception $err)
            {
                //echo "SNS Error $err<br>";
                //pdo_query("1","delete from notifytokens where arn='$arn' and providerid=$recipientid ");
            }
        }
        if($success > 0 ){
            $result = true;
        }
        return $result;
    }
    
    function SmsNotification( $providerid, $textmessage, $sms, $notifytype )
    {
        global $rootserver;
        global $installfolder;
        
        if($textmessage=='' || $sms == '') {
            return false;
        }

        $message = $textmessage;
        
        if($sms[0]!='+') {
            $sms = "+1".$sms;
        }
        
        $si2 = new SmsInterface (false, false);
        $si2->addMessage ( $sms, $message, 0, 0, 169,true);

        if (!$si2->connect ('MaddisonCross002' ,'welcome1', true, false)) {
            return false;
        }
        elseif (!$si2->sendMessages ()) 
        {
            return false;
        } 
        else {
            pdo_query("1","insert into smslog (providerid, sms, sentdate, source) values ($providerid, '$sms', now(),'$notifytype' )");
            return true;
        }
    }        
    function EmailNotification( $subject, $message, $email, $notifytype, $providerid, $roomid )
    {
        global $installfolder;
        global $rootserver;
        global $prodserver;
        
        if( $message=='' || $email=='') {
            return false;
        }
        if(intval($roomid) > 1 && ($notifytype == 'RP' || $notifytype='RL') )
        {
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
        $status = SendMail("0", "$subject", "$message", "$message", "$name", "$email" );
        if($status)
        {
            if(intval($roomid) > 1 && ($notifytype == 'RP' || $notifytype='RL') )
            {
                pdo_query("1"," 
                    update statusroom set lastemail=now() where 
                    providerid=$providerid and roomid=$roomid
                        ");
            }
        }
        return $status;
    }

    
    
    function CleanPhone( $phone )
    {
        if($phone == "")
            return "";
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
        global $installfolder;
        global $rootserver;
        
            $notify['notificationmessage'] = "";
            $notify['textmessage'] = "";
            $notify['emailsubject'] = "";
            $notify['emailmessage'] = "";
            
            
            $invitationUrl = "https://brax.me/l.php";
            if($party->mobile=='Y') {
                $invitationUrlMobile = "braxme://";
            }
            else {
                $invitationUrlMobile = $invitationUrl;
            }
            //New Chat Invite to Non Member
            if($party->notifytype == 'C' ){
            
                $invitationUrl = "$prodserver/$installfolder/setchat.php?e=$party->email&n=$party->name";

                $notify['emailmessage'] = "
                <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                    <h2 style='color:steelblue'>Hi $party->name. You have an active secure chat 
                    message from $party->providername$party->companyname</h2><br>
                    <br>
                    Please register on the Brax.Me app using this email address to see your secure chat. 
                    <br><br>

                    <b>On your browser</b><br>
                    <a href='$invitationUrl'>
                    $invitationUrl
                    </a>
                    <br>
                    <br>
                        <b>Download the Mobile App from AppStore or Google Play for the best experience.</b>
                        <br><br>
                        <div style='display:inline;background-color:transparent;padding:10px;width:auto;margin:auto'>
                        <a href='http://itunes.com/apps/braxme' style='text-decoration:none'>
                         <img class='appstore' src='../img/appStore.png' style='height:50px' >
                        </a>
                         &nbsp;&nbsp;
                        <a href='https://play.google.com/store/apps/details?id=me.brax.app1' style='text-decoration:none'>
                         <img class='appstore' src='../img/androidplay.png' style='height:50px' >
                        </a>
                        </div>

                        <br>
                        <br>
                    <a href='https://brax.me'>
                    <img src='https://brax.me/img/lock.png' style='height:30px;width:auto' /><br>
                    </a>


                </div>
                ";
                $notify['textmessage'] = "Secure Chat from $party->providername$party->companyname  Download Brax.Me from AppStore and login as $party->email to view";
            }
            //New Chat Invite to Existing Member (EC Existing - Chat)
            if($party->notifytype == 'EC')
            {
                $notify['notificationmessage'] =  "Chat $party->providername$party->companyname";
                $notify['textmessage'] = "Chat $party->providername$party->companyname ";
                $notify['emailsubject'] = "Secure Chat $party->providername$party->companyname ";
                $notify['emailmessage'] = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                        <h2 style='color:steelblue'>Hi $party->name. You have an active secure chat 
                        message from $party->providername$party->companyname</h2><br>
                        <br>
                        Please login to Brax.Me to see your secure conversation.
                        <br><br>

                        <a href='$invitationUrlMobile'>
                        $invitationUrlMobile
                        </a>
                        <br><br>
                        <a href='https://brax.me'>
                        <img src='https://brax.me/img/lock.png' style='height:30px;width:auto' /><br>
                        </a>
                    </div>
                    ";
            }
            //Chat Initial Reply
            if($party->notifytype == 'CN')
            {
                $notify['notificationmessage'] = "Chat Reply $party->providername ";
                $notify['textmessage'] = "Chat Reply $party->providername $invitationUrl";
                $notify['emailsubject'] = "Chat Reply $party->providername ";
                $notify['emailmessage'] = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                        <h2 style='color:steelblue'>Hi $party->name. $party->providername$party->companyname replied to your chat message.</h2><br>
                        <br>
                        Please login to Brax.Me to see your secure conversation.
                        <br><br>

                        <a href='$invitationUrlMobile'>
                        $invitationUrlMobile
                        </a>
                        <br>
                        <br>
                        <a href='https://brax.me'>
                        <img src='https://brax.me/img/lock.png' style='height:30px;width:auto' /><br>
                        </a>
                    </div>
                    ";

            }
            //Chat Poke per Post
            if($party->notifytype == 'CP')
            {
                $notify['notificationmessage'] = $party->providername." (Chat) - ".DecryptChat($party->payload, $party->encoding,"$party->chatid","" );
                if($party->alias!='')
                {
                    $notify['notificationmessage'] = "SecureChat - $party->providername ";
                }
                //$notificationmessage = "SecureChat - $providername ";
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            //Event Reminder
            if($party->notifytype == 'E1' || $party->notifytype == 'E2')
            {
                $notify['notificationmessage'] = base64_decode($party->payload );
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            //Room Poke
            if($party->notifytype == 'RP')
            {
                $notify['notificationmessage'] = "Room Activity: $party->room / $party->providername ";
                $notify['textmessage'] = "";
                //$notify['emailsubject'] = "";
                //$notify['emailmessage'] = "";
                $notify['emailsubject'] = "Room Post";
                $notify['emailmessage'] = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                        <h3 style='color:steelblue'>$party->providername posted to the room $party->room</h3><br>
                        <br>
                        Please login to Brax.Me to view the conversation.
                        <br><br>
                        <a href='https://brax.me'>
                        <img src='https://brax.me/img/lock.png' style='height:30px;width:auto' /><br>
                        </a>
                    </div>
                    ";
            }
            //Room Poke
            if($party->notifytype == 'RL')
            {
                $notify['notificationmessage'] = "Room Like: $party->room / $party->providername ";
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            //Email Notification to Existing Member
            if($party->notifytype == 'EN')
            {
                $notify['notificationmessage'] = "Secure Email from - $party->providername ";
                $notify['textmessage'] = "";
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            
            //Chat Poke per Post
            if($party->notifytype == 'T')
            {
                $notify['notificationmessage'] = $party->providername." - ".DecryptChat($party->payload, $party->encoding,"$party->recipientid","" );
                $notify['textmessage'] = $party->providername." - ".DecryptChat($party->payloadsms, $party->encoding,"$party->recipientid","" );
                $notify['emailsubject'] = "";
                $notify['emailmessage'] = "";
            }
            
            return (object) $notify;
    
    }
    
        
?>