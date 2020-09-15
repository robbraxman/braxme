<?php
session_start();
require_once("config.php");
require_once("crypt.inc");
require_once("sendmail.php");
require ("SmsInterface.inc");
$_SERVER['DOCUMENT_ROOT']='/var/www';
require ("aws.php");

    if($batchruns !='Y')
        exit();
    
    $sleep = 0;
    try {
        if(sizeof($argv)>1)
            $sleep = intval($argument1 = $argv[1]);
    }
    catch( Exception $e)
    {
        
    }

    if( $sleep > 0) {
        sleep($sleep);
    }
    

    $result = do_mysqli_query("1","
        select 
            notification.providerid, notification.notifydate, notification.status, 
            notification.notifytype, notification.email, 
            notification.sms, notification.name, notification.recipientid, 
            notification.notifyid, notification.mobile,
            provider.providername, provider.alias, provider.companyname,
            provider.replyemail, notification.roomid,
            notification.payload,notification.encoding
        from notification
        left join provider on notification.providerid = provider.providerid
        where notification.status='N'
        order by notification.notifydate asc 
            ");
    $email_throttle_limit = 100;
    $count = 0;
    while( $row = do_mysqli_fetch("1",$result))
    {
        
        $providerid = $row['providerid'];
        $payload = $row['payload'];
        $encoding = $row['encoding'];
        $email = $row['email'];
        $sms = CleanPhone($row['sms']);
        if($sms == '+1') {
            $sms = "";
        }
        $name = $row['name'];
        $mobile = $row['mobile'];
        $notifytype = $row['notifytype'];
        $recipientid = $row['recipientid'];

        $providername = $row['providername'];
        $replyemail = $row['replyemail'];
        $alias = rtrim($row['alias']);
        if($row['companyname']!='') {
            $companyname = " - ".$row['companyname'];
        }
        if($alias!='') {
            $providername = $alias;
        }
        $notifyid = $row['notifyid'];

        $roomid = $row['roomid'];
        $room = "";
        if(intval($roomid)>0 )
        {
            $result2 = do_mysqli_query("1","select room from statusroom where roomid = $roomid");
            if($row2 = do_mysqli_fetch("1",$result2))
            {
                $room = $row2['room'];
            }
        }
        
        //C = New User User Chat
        if($notifytype == 'C' ){
        
            $invitationUrl = "$rootserver/$installfolder/setchat.php?e=$email&n=$name";

            $message = "
            <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                <h2 style='color:steelblue'>Hi $name. You have an active secure chat 
                message from $providername$companyname</h2><br>
                <br>
                Please register on Brax.Me to see your secure conversation.
                <br><br>

                <a href='$invitationUrl'>
                $invitationUrl
                </a>
                <br>
                <br>
                <a href='https://brax.me'>
                <img src='https://brax.me/img/lock.png' style='height:30px;width:auto' /><br>
                </a>


            </div>
            ";
            if( $sms!='' && $sms!='+1')
            {
                $textmessage = "Secure Chat from $providername$companyname  $invitationUrl ";
                $sessionid = uniqid("",false);
                $status = SmsNotification( $textmessage, $sms );

            }
            SendMail("0", "Secure Chat from $providername$companyname", "$message", "$message", "$name", "$email" );

            do_mysqli_query("1","update notification set status='Y' where notifyid = $notifyid");

        }
        else 
        /* 
         *  H A N D L I N G     F O R
         *  E X I S T I NG     U S E R S
         */
        {
            $notificationmessage = "";
            $textmessage = "";
            $emailsubject = "";
            $emailmessage = "";
            
            
            $invitationUrl = "https://brax.me/l.php";
            if($mobile=='Y') {
                $invitationUrlMobile = "braxme://";
            }
            else {
                $invitationUrlMobile = $invitationUrl;
            }

            //Determine the Message
            if($notifytype == 'EC')
            {
                $notificationmessage = "";
                $textmessage = "Chat $providername$companyname ";
                $emailsubject = "Secure Chat $providername$companyname ";
                $emailmessage = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                        <h2 style='color:steelblue'>Hi $name. You have an active secure chat 
                        message from $providername$companyname</h2><br>
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
            //Chat Initial Reply
            if($notifytype == 'CN')
            {
                $notificationmessage = "Chat Reply $providername ";
                $textmessage = "Chat Reply $providername $invitationUrl";
                $emailsubject = "Chat Reply $providername ";
                $emailmessage = "
                    <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                        <h2 style='color:steelblue'>Hi $name. $providername$companyname replied to your chat message.</h2><br>
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
            //Chat Poke
            if($notifytype == 'CP')
            {
                $notificationmessage = $providername." - ".DecryptEmail($payload, $encoding,"" );
                if($alias!='')
                {
                    $notificationmessage = "SecureChat - $providername ";
                }
                //$notificationmessage = "SecureChat - $providername ";
                $textmessage = "";
                $emailsubject = "";
                $emailmessage = "";
            }
            //Room Poke
            if($notifytype == 'RP')
            {
                $notificationmessage = "Activity in Room: $room ";
                $textmessage = "";
                $emailsubject = "";
                $emailmessage = "";
            }

            $status = Notification($notificationmessage, $recipientid);
            if(!$status) {
                $status = SmsNotification($textmessage, $sms);
            }
            if(!$status) {
                //Stop emailing once Email Throttle Limit Reached - try again in next batch
                if($count < $email_throttle_limit) {
                    $status = EmailNotification( $emailsubject, $emailmessage, $email );
                }
                else 
                {
                    $status = false;
                }
            }
            
            //Single Pass Only - Remove regardless of Status
            do_mysqli_query("1","update notification set status='Y' where notifyid = $notifyid");
            //echo "notifyid=$notifyid";

        }


    }

    function Notification( $message, $recipientid )
    {
        if($message=='') {
            return false;
        }
        if($recipientid == '')
        {
            return false;
        }
        //$message = str_replace('\"','"',$message);
        $message = str_replace("\\n","", $message );
        $message = str_replace("\\r","", $message );
        $message = str_replace("'","", $message );
        $message = str_replace('"',"", $message );
        //$message = addslashes($message);
        
        $result = false;
        //Users are Mobile - Send 
        $result2 = do_mysqli_query("1","select arn, platform from notifytokens where providerid=$recipientid and arn!='' and status='Y' ");
        while($row2 = do_mysqli_fetch("1",$result2))
        {
            $msgjson = "";
            //echo "$row2[platform]<br>";
            if($row2['platform']=='ios')
            {
                $arr_aps = array( "aps" => array( "alert" => $message, "sound" => "ping.wav") );
                $msg_aps = addslashes(json_encode($arr_aps));
                $msgjson = "{ \"APNS\" : \"$msg_aps\" }";
                //echo $msgjson;
                $jsonflag = true;        
            }
            if($row2['platform']=='android')
            {
                $arr_aps = array( "data" => array( "message" => $message ) );
                $msg_aps = addslashes(json_encode($arr_aps));
                $msgjson = "{ \"GCM\" : \"$msg_aps\" }";
                $jsonflag = true;        
            }
            $arn = $row2['arn'];                
            try {
                publishSnsNotification("$arn",$msgjson, $jsonflag);
            }
            catch (Exception $err)
            {
                //echo "SNS Error $err<br>";
                //do_mysqli_query("1","delete from notifytokens where arn='$arn' and providerid=$recipientid ");
            }
            $result = true;
        }
        return $result;
    }
    function SmsNotification( $textmessage, $sms )
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

        if (!$si2->connect (testaccount ,welcome1, true, false)) {
            return false;
        }
        elseif (!$si2->sendMessages ()) 
        {
            return false;
        } 
        else {
            return true;
        }
    }        
    function EmailNotification( $subject, $message, $email )
    {
        if( $message=='' || $email=='') {
            return false;
        }
        $status = SendMail("0", "$subject", "$message", "$message", "$name", "$email" );
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
    
        
?>