<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require_once("crypt-pdo.inc.php");
require ("notify.inc.php");
require("chat.inc.php");

    $providerid = @tvalidator("ID",$_POST['providerid']);
    $recipientid = @tvalidator("ID",$_POST['recipientid']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $email = @tvalidator("EMAIL",$_POST['email']);
    $name = @tvalidator("PURIFY",$_POST['name']);
    $sms = @tvalidator("PURIFY",$_POST['sms']);
    //$passkey64 = @tvalidator("PURIFY",$_POST['passkey64']);
    $handle = @tvalidator("PURIFY",$_POST['handle']);
    $techsupport = @tvalidator("PURIFY",$_POST['techsupport']);
    $radiostation = @tvalidator("PURIFY",$_POST['radiostation']);
    $name = ucwords($name);
    $title = @tvalidator("PURIFY",$_POST['title']);
    $passkey = @tvalidator("PURIFY",$_POST['passkey']);
    $lifespan = @tvalidator("PURIFY",$_POST['lifespan']);
    
    //For Full Room Chat
    $roomid = @tvalidator("ID",$_POST['roomid']);
    
    
    $msg = '';
    
    if( $sms!=''){
        $sms = CleanPhone($sms);
    }
    
    /*
     if($handle !== ''){
            $msg .= "&nbsp;&nbsp;Chat1 not available /$handle /$recipientid";
            $alert = 'Y';
            ExitRoutine($msg, $alert, 0,'','');
     }
     * 
     */
    
    
    if("$email"=="" && $handle === '' && $recipientid == "" && $roomid == '' ){
        $msg .= "&nbsp;&nbsp;Missing email address";
        $alert = 'Y';
        ExitRoutine($msg, $alert, 0,'','');
    }

    
    $alert = "";
    $chatid = "null";
    
    /* Get Info on Owner/Inviter */
    /***************************************************/
    /***************************************************/
    /***************************************************/
    /***                  INVITER                      */
    /***************************************************/
    /***************************************************/
    /***************************************************/
    
    $result = pdo_query("1","
        select providername, replyemail, alias, companyname, handle from
        provider where
        providerid = ?
            ",array($providerid));
    if($row = pdo_fetch($result)){
        
        $myname = $row['providername'];
        $myemail = $row['replyemail'];
        $myalias = $row['alias'];
        $myhandle = $row['handle'];
        if($row['companyname']!=''){
            $companyname = " - ".$row['companyname'];
        }
        if($row['alias']!=''){
            $myname = $myalias;
        }
    }


    /***************************************************/
    /***************************************************/
    /***************************************************/
    /***                  INVITEE                      */
    /***************************************************/
    /***************************************************/
    /***************************************************/

    
    
    //Test to make sure you do not chat with yourself
    if( ("$handle"=="$myhandle" && "$handle"!=="") || "$providerid"=="$recipientid"){
        $msg .= "&nbsp;&nbsp;You cannot chat with yourself";
        $alert = 'Y';
        ExitRoutine($msg, $alert, 0, '','');
    }
    if( $recipientid == ''){
        $recipientid = 0;
    }
    

    /*
    if($_SESSION['superadmin']=='Y'){
        
        if( IsBlocked($recipientid, $myhandle, $myemail)){
            $msg .= "&nbsp;&nbsp;Chat not permitted ($recipientid)";
            $alert = 'Y';
            ExitRoutine($msg, $alert, 0,'','');
            
        } else {
            $msg .= "&nbsp;&nbsp;Chat OK ($recipientid)";
            $alert = 'Y';
            ExitRoutine($msg, $alert, 0,'','');
            
        }
    }
     * 
     */
    
    if($roomid == ''){
    
        
        CreateSinglePartyChat( 
            $providerid, $myname, $myhandle, $myemail, 
            $recipientid, $handle, $email,
            $passkey, $title, $techsupport, $lifespan 
            );
        
        
        exit();
    } else {
        
        CreateRoomChat( 
            $providerid, $myname, $myhandle, $myemail, 
            $roomid,
            $passkey, $title, $techsupport, $lifespan, $radiostation );
        exit();
    }

    exit();
    
    
    function ExitRoutine( $msg, $alert, $chatid, $passkey,$passkey64 )
    {
        $arr = array('msg'=> "$msg",
                     'alert'=> "$alert",
                     'chatid'=> "$chatid",
                     'passkey'=> "$passkey",
                     'passkey64'=> "$passkey64"
                    );
        echo json_encode($arr);
        exit();
    }
    
    function CreateSinglePartyChat( 
        $providerid, $myname, $myhandle, $myemail, 
        $recipientid, $handle, $email, 
        $passkey, $title, $techsupport, $lifespan )
    {
        
            $sms = "";
            $passkey64 = "";
        
        
            //Is Invitee an Existing Account?
            $result = pdo_query("1","
                select providername, replyemail, alias, providerid, handle, mobile from
                    provider where active = 'Y' and 
                    ( 
                        providerid = $recipientid or
                        (replyemail='$email' and '$email'!='') or 
                        (handle='$handle' and '$handle'  !='') 
                    )
                    ",null);
            if($row = pdo_fetch($result)){

                


                if( IsBlocked($recipientid, $myhandle, $myemail)){
                    $msg .= "&nbsp;&nbsp;Chat not available";
                    $alert = 'Y';
                    ExitRoutine($msg, $alert, 0,'','');

                }
                if( IsBlocked($providerid, $row['handle'], $row['replyemail'])){
                    $msg .= "&nbsp;&nbsp;You blocked this party";
                    $alert = 'Y';
                    ExitRoutine($msg, $alert, 0,'','');

                }

                /* Invitee is an Existing Account */

                //Search for Duplicate Chat
                $msg = "";
                if( !$chatid = EstablishChatSession($providerid, $row['handle'], $passkey, $title, $techsupport, $lifespan, "","" )){
                    $msg .= "&nbsp;&nbsp;Existing Chat Session Found";
                    $alert = 'Y';
                    ExitRoutine($msg, $alert, 0,'','');
                }
                $mobile = $row['mobile'];
                $recipientid = $row['providerid'];



                AddToContacts(
                        $techsupport, $providerid, $row['providername'], 
                        $row['replyemail'], $row['handle'],
                        $sms, $recipientid, $myname, $myemail, $myhandle  );

                AddChatMembers($chatid, $providerid, $recipientid, $techsupport );
                $alert = '';
                $passkeyNew64 = EncryptE2EPasskey($passkey,$providerid);        

                PassE2EKey($chatid, $passkeyNew64, $providerid, $recipientid);

            } else {
                $recipientid = 0;

                //No E2E for Non Member
                $passkey = '';
                $passkey64 = '';

                if( substr($name,0,1)=='@'){
                    $msg .= "&nbsp;&nbsp;@handle not Found";
                    $alert = 'Y';
                    ExitRoutine($msg, $alert, 0,"","");
                    exit();
                }
                if( $email ==''){
                    $msg .= "&nbsp;&nbsp;You need an email to invite a non-member.";
                    $alert = 'Y';
                    ExitRoutine($msg, $alert, 0,"","");
                    exit();
                }

                //Search for Duplicate Chat
                if( !$chatid = EstablishChatSession($providerid, $handle, $passkey, $title, $techsupport, $lifespan,""  )){
                    $msg .= "&nbsp;&nbsp;Duplicate Chat Session Found";
                    $alert = 'Y';
                    ExitRoutine($msg, $alert, 0,'','');
                }

                if( $email != '' && substr($name,0,1)!='@'){
                    $result = pdo_query("1","
                        insert into contacts (providerid, name, email, sms, status, imapbox,blocked ) values
                        (?, ?, ?, ?, 'Y', null,''  )
                            ",array($providerid,$name,$email,$sms));
                }
                
                $alert = 'C';

                //Delete prior chat invites
                $result = pdo_query("1","
                    delete from invites where chatid is not null and  
                    (( email =? and email!='') or (handle=? and handle!='' ))
                     and providerid=?
                        ",array($email,$handle,$providerid));
                
                $inviteid = base64_encode(uniqid("$providerid"));
                $inviteid = str_replace('=','',$inviteid);

                $result = pdo_query("1","
                    insert into invites (providerid, name, email, handle, sms, contactlist, roomid, chatid, invitedate, status, retries, inviteid ) values
                    (?, ?, ?, ?, ?, '', 0, ?, now(), 'Y', 0, ?  )
                        ",array($providerid,$name,$email,$handle,$sms,$chatid,$inviteid));
                $mobile = "N";

                $message = "Let me know when you see this!";
                $encode = EncryptChat ($message,"$chatid","" );


                $result = pdo_query("1",
                    "
                        insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                        values
                        ( ?, ?, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
                    ",array($chatid,$providerid));


                $result = pdo_query("1",
                    "
                    update chatmembers set lastmessage=now(), lastread=now() where providerid= ? and chatid=? and status='Y'
                    ",array($providerid,$chatid));        
                $result = pdo_query("1",
                    "
                    update chatmaster set lastmessage=now() where chatid=? and status='Y'
                    ",array($chatid));        

            }
            
            //New Chat Invite - Manual
            $notifytype = 'EC';
            if($alert == 'C' && $mode == ''){
                $notifytype = 'C';
            }

            
            GenerateNotification( 
                $providerid, 
                $recipientid, 
                $notifytype, null, 
                null, $chatid, 
                null, null,
                null,'','' );
              
             

            $msg = "";
 
    
        ExitRoutine($msg, $alert, $chatid,$passkey, $passkey64);
        exit();
        
    }
    
    
    function CreateRoomChat( 
        $providerid, $myname, $myhandle, $myemail, 
        $roomid,
        $passkey, $title, $techsupport, $lifespan, $radiostation )
    {
        
            $passkey64 = "";
            $alert = '';
            $chatid = "";
        
            $result = pdo_query("1","
                select * from chatmaster where roomid = ? and owner = ? and 
                status='Y' and roomid in (select roomid from roominfo where radiostation = 'Q' and chatmaster.roomid = roominfo.roomid )
                and chatmaster.roomid is not null
                    ",array($roomid,$providerid));
            if($row = pdo_fetch($result)){
                $msg .= "&nbsp;&nbsp;Duplicate quiz session";
                $alert = 'Y';
                ExitRoutine($msg, $alert, 0,"","");
                return false;
                
            }
            
        
            //Is Invitee an Existing Account?
            $result = pdo_query("1","
                select provider.providername, provider.replyemail, provider.alias, provider.providerid, provider.handle,
                    roominfo.radiostation
                    from provider 
                    left join statusroom on statusroom.providerid = provider.providerid
                    left join roominfo on roominfo.roomid = statusroom.roomid
                    where 
                    provider.active = 'Y' and
                    statusroom.roomid = ?
                    ",array($roomid));
            $i = 0;
            while($row = pdo_fetch($result)){

                if($radiostation==''){
                    $radiostation = $row['radiostation'];
                }
                
                if($i == 0){
                    //Search for Duplicate Chat
                    if( !$chatid = EstablishChatSession($providerid, $row['handle'], $passkey, $title, $techsupport, $lifespan, $roomid, $radiostation )){
                        $msg .= "&nbsp;&nbsp;Existing Chat Session Found";
                        $alert = 'Y';
                        ExitRoutine($msg, $alert, 0,'','');
                    }
                }


                if( IsBlocked($row['providerid'], $myhandle, $myemail)){
                    continue;
                }
                if( IsBlocked($providerid, $row['handle'], $row['replyemail'])){
                    continue;
                }

                /* Invitee is an Existing Account */

                $recipientid = $row['providerid'];


                AddChatMembers($chatid, $providerid, $recipientid, $techsupport );
                
                $passkeyNew64 = EncryptE2EPasskey($passkey,$providerid);        
                PassE2EKey($chatid, $passkeyNew64, $providerid, $recipientid);
                
                $i++;

            }
        $message = "Chat Session Established";
        if($radiostation == 'Q'){
            $message = "Quiz Session Started";
        }
        $encode = EncryptChat ($message,"$chatid","" );


        $result = pdo_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                values
                ( ?, ?, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
            ",array($chatid,$providerid));
        $result = pdo_query("1",
            "
            update chatmembers set lastmessage=now(), lastread=now() where providerid= ? and chatid=? and status='Y'
            ",array($providerid,$chatid));        
        $result = pdo_query("1",
            "
            update chatmaster set lastmessage=now() where chatid=? and status='Y'
            ",array($chatid));        
        if($radiostation == 'Q'){
            $result = pdo_query("1",
                "
                update chatmaster set radiostation='Q' where chatid = ? and status='Y'
                ",array($chatid));        
            
        }

    
        ExitRoutine("", $alert, $chatid,$passkey, $passkey64);
        exit();
        
    }
    

        
    
?>