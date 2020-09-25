<?php
require_once("config-pdo.php");
require_once("sendmail.php");
require_once("SmsInterface.inc");
require_once("crypt-pdo.inc.php");
require_once("notify.inc.php");

    function IsNotExistingChat($providerid, $recipientid )
    {
        $result = pdo_query("1","
            select * from chatmaster where chatid in
            (select chatid from chatmembers where chatmembers.providerid=?
                and chatmaster.chatid = chatmembers.chatid )
            and chatid in
            (select chatid from chatmembers where chatmembers.providerid=?
                and chatmaster.chatid = chatmembers.chatid )
			and (select count(*) from chatmessage where chatmaster.chatid = chatmessage.chatid) > 0
                ",array($providerid,$providerid));
        if($row = pdo_fetch($result)){
            return false;
        }
        return true;
    }
    
    
    
    function AddContact($providerid, $name, $email, $handle, $sms, $targetproviderid, $mode )
    {
        $status = 'N';
        if($mode == 'me'){
            $result2 = pdo_query("1","
                select * from contacts where providerid=? and targetproviderid is not null and
                (   (email=?  and
                        email in 
                        (select replyemail from provider where replyemail=? and ?!='' and active='Y')
                    )
                    or
                    (handle=? and
                        handle in 
                        (select handle from provider where handle=? and ?!='' and active='Y')
                    )
                )
            ",array($providerid,$email,$email,$email,$handle,$handle,$handle));


            if($row2 = pdo_fetch($result2)){
                return false;
            } else {

                $status = 'Y';

            }
        } else {
            $status = "Y";
            
        }
        if($status == 'Y'){
            if($handle!=''){
                $email = '';
            }
            $result2 = pdo_query("1","
                delete from contacts where providerid = ? and 
                (
                    (email = ? and ?!='') or
                    (handle = ? and ?!='') 
                )
            ",array($providerid,$email,$email,$handle,$handle));

            $result2 = pdo_query("1","
                insert into contacts (providerid, targetproviderid, contactname, handle, email, sms, friend, imapbox, blocked, createdate, source ) values
                (?, ?,?, ?,?, ?, 'Y', null,'', now(),'C'  )
            ",array($providerid,$targetproviderid,$name,$handle,$email,$sms));
        }
        
    }
    
    function CleanPhone( $phone )
    {
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );
        if($phone[0]!='+')
            $phone = "+1".$phone;
        
        return $phone;
    }
    
    function SmsSendInvite( $sms, $providerid, $sessionid, $textmessage )
    {
        global $rootserver;
        global $installfolder;

        $message = $textmessage;
        
        if($sms[0]!='+'){
            $sms = "+1".$sms;
        }
        
        $si2 = new SmsInterface (false, false);
        $si2->addMessage ( $sms, $message, 0, 0, 169,true);

        if (!$si2->connect (MaddisonCross002 ,welcome1, true, false)){
            return false;
        }
        elseif (!$si2->sendMessages ()) {
            return false;
        } else
            return true;
    }        
    
    function EstablishChatSession( $providerid, $handle, $passkey, $title, $techsupport, $lifespan, $roomid, $radiostation )
    {
     
        if($techsupport == 'Y' ){
        
            $result = pdo_query("1",
                "
                select * from chatmaster where
                status = 'Y' and
                exists
                (select * from chatmembers where chatmaster.chatid =chatmembers.chatid
                and chatmembers.providerid = ?
                )
                and
                exists
                (select * from chatmembers where chatmaster.chatid =chatmembers.chatid
                and techsupport ='Y'
                and chatmembers.providerid = 
                        (
                        select providerid from provider where handle=? limit 1
                    )
                )
                and
                (select count(*) from chatmessage where chatid=chatmaster.chatid) > 0
                order by chatid desc 
                ",array($providerid,$handle));
            if($row=pdo_fetch($result))
            {
                //Duplicate!
                return false;
            }
        }
        
        if(intval($lifespan)<=0 ){
            $lifespan = "0";
        }
        //convert to seconds
        $lifespan = $lifespan * 60;
        $encoding = '';
        $chatid = '';
        
        if($roomid == ''){
            $roomid = "null";
        }
        
        
        
        
        /* Create Chat Session - Owner */
        pdo_query("1",
            "
                insert into chatmaster ( 
                owner, created, status, archive, keyhash, title, encoding, lifespan, roomid, radiostation )
                values
                ( ?, now(), 'Y',
                (select archivechat from provider where providerid=?),
                    '','',?, ?, ?, ?  );
            ",array($providerid,$providerid,$encoding,$lifespan,$roomid,$radiostation));
        
        
        

        /* Get Chat ID of New Chat Session */
        $result = pdo_query("1",
            "
                select chatid from chatmaster 
                where owner = ? and status='Y' 
                order by chatid desc limit 1;
            ",array($providerid));

        if( $row = pdo_fetch($result)){
            
            //Hash
            $chatid = $row['chatid'];
            $streamid = "";
            if($radiostation!=''){
                $streamhash = substr(hash("sha1", $chatid),0,8);
                $streamid = "chat$streamhash";
                pdo_query("1",
                    "
                        update chatmaster set streamid=? where chatid=? 
                    ",array($streamid, $chatid));
            }
            
            //Hash
            $hash = '';
            if($passkey!=''){
                
                $hash = hash('sha256',"$passkey$chatid");
                
                pdo_query("1",
                    "
                        update chatmaster set keyhash='$hash' where chatid=$chatid 
                    ",null);
            }
            
            //Encrypted Title
            if($title != ''){
                $encoding = $_SESSION['responseencoding'];
                $titleencrypted =  EncryptText( $title, "$chatid" );
                pdo_query("1",
                    "
                        update chatmaster set title=?, encoding=? where chatid=? 
                    ",array($titleencrypted,$encoding,$chatid));
            }

            pdo_query("1",
                "
                    insert into chatmembers 
                    ( chatid, providerid, status, lastactive, lastmessage, lastread ) 
                    values
                    ( ?, ?, 'Y', now(), now(), now() );
                ",array($chatid,$providerid)
            );
            

            if(intval($roomid) > 0){
                
            
                /* Create Chat Spawned */
                pdo_query("1",
                "
                    insert into chatspawned ( chatid, roomid, providerid, createdate )
                    values ( $chatid, $roomid, $providerid, now() )
                ",array($chatid,$roomid,$providerid));
            }
            
            
        }
        return $chatid;
    }
    
    function AddToContacts($techsupport, $providerid, $providername, $replyemail, $handle, $sms, $recipientid, $myname, $myemail, $myhandle )
    {
        //Add only if not Techsupport Chat
        if($techsupport!='Y'){
            //Add Party to My contact list
            if(!AddContact($providerid, $providername, $replyemail, $handle, $sms, $recipientid, "me")) {
                //not added to contact list
            }

            //Add me to contact list of Party
            if(!AddContact($recipientid, $myname, $myemail, $myhandle, "", $providerid,"other")) {
                //not added to contact list
            }
        }
        
    }
    function AddChatMembers($chatid, $providerid, $recipientid, $techsupport )
    {

        /*
        if($_SESSION['superadmin']=='Y'){
            $msg .= "&nbsp;&nbsp;$chatid $recipientid";
            $alert = 'Y';
            ExitRoutine($msg, $alert, 0,'','');
        }
         * 
         */
        
        $result = pdo_query("1","
                insert into chatmembers ( chatid, providerid, status, lastactive, techsupport ) 
                values
                ( ?, ?, 'Y', now(),? );
        ",array($chatid,$recipientid,$techsupport));
        
        if($techsupport == 'Y'){
            
            $message = 
                    "Please ask your question and a tech support staff member will respond ".
                    "to you when one is available. You may leave the chat session and ".
                    "your mobile device will get a notification when you receive a response. ".
                    " Thank you for your patience. ";
            $encode = EncryptChat ($message,"$chatid","" );


            $result = pdo_query("1",
                "
                    insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                    values
                    ( ?, ?, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
                ",array($chatid,$recipientid));
            
        }
    }
    function IsBlocked( $providerid, $handle, $email)
    {
        $result = pdo_query("1",
            "
                select * from contacts where ( (handle = ? and ?!='') or (email = ? and ?!=''))
                and providerid = ? and blocked = 'Y'
            ",array($handle,$handle,$email,$email,$providerid));
        if($row = pdo_fetch($result)){
            return true;
            
        }
        return false;
    }
    
    

    
?>