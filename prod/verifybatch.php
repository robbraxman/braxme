<?php
session_start();
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("sendmail.php");
require ("../SmsInterface.inc");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
require ("aws.php");

    if($batchruns !='Y') {
        exit();
    }
    
    NotifyRun();

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
    
    
    
    function NotifyRun()
    {
        $result = pdo_query("1","
            select provider.providerid, verification.verifieddate,
            provider.providername, provider.replyemail, provider.replysms,
            provider.name2, provider.alias, provider.companyname,
            verification.verificationkey
            from verification
            left join provider on verification.providerid = provider.providerid
            where verifieddate is null and provider.active='Y'
            
                ");
        $email_throttle_limit = 1000;
        $count = 0;
        $party = new stdClass();
        while( $row = pdo_fetch($result))
        {
            $party->providerid = $row['providerid'];
            $party->replyemail = $row['replyemail'];
            $party->sms = CleanPhone($row['replysms']);
            if($party->sms == '+1') {
                $party->sms = "";
            }
            $party->providername = $row['providername'];
            $party->verificationkey = $row['verificationkey'];
            $party->name2 = $row['name2'];
            $party->alias = rtrim($row['alias']);
            $party->companyname = '';
            if($row['companyname']!='') {
                $party->companyname = " - ".$row['companyname'];
            }

            
            $notify = CreateNotificationMessage( $party );
            
            
            if( NotificationCheck($party->providerid))
            {
                $notifymethod = '';
                
                $status = Notification($notify->notificationmessage, $party->providerid );
                
                if(!$status) {
                    $status = SmsNotification($notify->textmessage, $party->sms);
                }
            }
                
            //Stop emailing once Email Throttle Limit Reached - try again in next batch
            if($count < $email_throttle_limit) {
                $status = EmailNotification( $notify->emailsubject, $notify->emailmessage, $party->replyemail, $party->providername );
            }
            $count++;
        }
    }
    
    function NotificationCheck( $recipientid )
    {
        
        $result2 = pdo_query("1","select arn, platform, token from notifytokens where providerid=? and token!='' and arn='' and status='Y' ",array($recipientid));
        while($row2 = pdo_fetch($result))
        {   
            //blank ARN so HOLD OFF
            return false;
        }
        return true;
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
        $success = 0;
        //Users are Mobile - Send 
        $result2 = pdo_query("1","
            select notifytokens.arn, notifytokens.platform, notifytokens.token 
            from notifytokens 
            left join provider on provider.providerid = notifytokens.providerid
            where notifytokens.providerid=? and notifytokens.arn!='' and notifytokens.status='Y' 
            and provider.providerid = $admintestaccount
            and provider.active='Y'
            
            ",array($recipientid));
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

        if (!$si2->connect ('MaddisonCross002' ,'welcome1', true, false)) {
            return false;
        }
        elseif (!$si2->sendMessages ()) 
        {
            return false;
        } 
        else {
            pdo_query("1","insert into smslog (providerid, sms, sentdate. source) values ( 0, ?, now(),'V' )",array($sms));
            return true;
        }
    }        
    function EmailNotification( $subject, $message, $email, $name  )
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
    
    function CreateNotificationMessage( $party )
    {
            global $appname;
            global $rootserver;
            global $installfolder;
            
            $notify['notificationmessage'] = "";
            $notify['textmessage'] = "";
            $notify['emailsubject'] = "";
            $notify['emailmessage'] = "";
            

            $notify['notificationmessage'] =  "Please respond to the account verification email from $appname.";
            $notify['textmessage'] = "Please respond to the account verification email from $appname";
            $notify['emailsubject'] = "Account Verification";
            $notify['emailmessage'] = 
                "<html><body>
                <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                    <h2 style='color:steelblue'>Hi $party->providername. Please verify your account email.</h2>
                    <br><br><b>Thank you for signing up with $appname.</b><br><br>
                    We need to confirm your identity for your own security.<br>
                    PLEASE CLICK LINK BELOW to verify this email address.<br><br>
                    <a href='$rootserver/$installfolder/verify.php?i=$party->verificationkey'>$rootserver/$installfolder/verify.php?i=$party->verificationkey</a><br><br>
                    (Cut and paste link to browser if you cannot click it)<br><br>
                    <a href='https://brax.me'>
                    <img src='$rootserver/img/lock.png' style='height:30px; width: auto'>
                    </a><br><br><br>
                </div>
                </body></html>
                ";
                

            return (object) $notify;
    
    }
    
        
?>