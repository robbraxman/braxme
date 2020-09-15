<?php
require_once("config.php");
require_once("sysdown.php");
require_once("crypt.inc.php");
require_once("aws.php");
require_once("SmsInterface.inc");

    
    function LoginNotification( $message, $arn, $platform )
    {
        
        $sound = "www/ping.caf";            
        
        
        if( $arn=='')
        {
            //arn pending
            //return 2; //PENDING
            return false;
        }
        $msgjson = "";
        if($platform =='ios')
        {
            $arr_aps = array( "aps" => array( "alert" => $message, "sound" => "$sound" ) );
            $msg_aps = addslashes(json_encode($arr_aps));
            $msgjson = "{ \"APNS\" : \"$msg_aps\" }";
            //echo $msgjson;
            $jsonflag = true;        
        }
        if($platform=='android' || $platform=='chrome')
        {
            $arr_gcm = array( 
                "data" => array( "message" => $message),
                "sound" => "ping",
                "time_to_live" => 32400
                );
                //"collapse_key" => "chat");
            $msg_gcm = addslashes(json_encode($arr_gcm));
            $msgjson = "{ \"GCM\" : \"$msg_gcm\" }";
            $jsonflag = true;        
        }
            
        $notifyresult = publishSnsNotification("$arn",$msgjson, $jsonflag);
        return true;
    }
        
    function SmsNotification( $message, $sms )
    {
        global $rootserver;
        global $installfolder;
        
        //Exclude Invalid Phone Numbers
        
        //If US Phone Number not 10 digit - error
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
        if($sms == '') {
            return false;
        }

        
        if($sms[0]!='+') {
            $sms = "+1".$sms;
        }
        
        $si2 = new SmsInterface (false, false);
        $si2->addMessage ( $sms, $message, 0, 0, 169,true);

        if (!$si2->connect ('testaccount' ,'welcome1', true, false)) {
            return false;
        }
        elseif (!$si2->sendMessages ()) 
        {
            return false;
        } 
        else 
        {
            return true;
        }
    }        