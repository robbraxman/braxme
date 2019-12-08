<?php
session_start();
require_once("config.php");
require_once("sendmail.php");
require_once("crypt.inc.php");
require ("notify.inc.php");
require("chat.inc.php");

    $providerid = @mysql_safe_string($_POST['providerid']);
    $recipientid = @mysql_safe_string($_POST['recipientid']);
    $mode = @mysql_safe_string($_POST['mode']);
    $email = @mysql_safe_string($_POST['email']);
    $name = @mysql_safe_string($_POST['name']);
    $sms = @mysql_safe_string($_POST['sms']);
    //$passkey64 = @mysql_safe_string($_POST['passkey64']);
    $handle = @mysql_safe_string($_POST['handle']);
    $techsupport = @mysql_safe_string($_POST['techsupport']);
    $radiostation = @mysql_safe_string($_POST['radiostation']);
    $name = ucwords($name);
    $title = @mysql_safe_string($_POST['title']);
    $passkey = @mysql_safe_string($_POST['passkey']);
    $lifespan = @mysql_safe_string($_POST['lifespan']);
    
    //For Full Room Chat
    $roomid = @mysql_safe_string($_POST['roomid']);
    
    
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
    
    $result = do_mysqli_query("1","
        select providername, replyemail, alias, companyname, handle from
        provider where
        providerid = $providerid
            ");
    if($row = do_mysqli_fetch("1",$result)){
        
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
            $result = do_mysqli_query("1","
                select providername, replyemail, alias, providerid, handle, mobile from
                    provider where active = 'Y' and 
                    ( 
                        providerid = $recipientid or
                        (replyemail='$email' and '$email'!='') or 
                        (handle='$handle' and '$handle'  !='') 
                    )
                    ");
            if($row = do_mysqli_fetch("1",$result)){

                


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
                    $result = do_mysqli_query("1","
                        insert into contacts (providerid, name, email, sms, status, imapbox,blocked ) values
                        ($providerid, '$name', '$email', '$sms', 'Y', null,''  )
                            ");
                }
                
                $alert = 'C';

                //Delete prior chat invites
                $result = do_mysqli_query("1","
                    delete from invites where chatid is not null and  
                    (( email ='$email' and email!='') or (handle='$handle' and handle!='' ))
                     and providerid='$providerid'
                        ");
                
                $inviteid = base64_encode(uniqid("$providerid"));
                $inviteid = str_replace('=','',$inviteid);

                $result = do_mysqli_query("1","
                    insert into invites (providerid, name, email, handle, sms, contactlist, roomid, chatid, invitedate, status, retries, inviteid ) values
                    ($providerid, '$name', '$email', '$handle', '$sms', '', 0, $chatid, now(), 'Y', 0, '$inviteid'  )
                        ");
                $mobile = "N";

                $message = "Let me know when you see this!";
                $encode = EncryptChat ($message,"$chatid","" );


                $result = do_mysqli_query("1",
                    "
                        insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                        values
                        ( $chatid, $providerid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
                    ");


                $result = do_mysqli_query("1",
                    "
                    update chatmembers set lastmessage=now(), lastread=now() where providerid= $providerid and chatid=$chatid and status='Y'
                    ");        
                $result = do_mysqli_query("1",
                    "
                    update chatmaster set lastmessage=now() where chatid=$chatid and status='Y'
                    ");        

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
        
            $result = do_mysqli_query("1","
                select * from chatmaster where roomid = $roomid and owner = $providerid and 
                status='Y' and roomid in (select roomid from roominfo where radiostation = 'Q' and chatmaster.roomid = roominfo.roomid )
                and chatmaster.roomid is not null
                    ");
            if($row = do_mysqli_fetch("1",$result)){
                $msg .= "&nbsp;&nbsp;Duplicate quiz session";
                $alert = 'Y';
                ExitRoutine($msg, $alert, 0,"","");
                return false;
                
            }
            
        
            //Is Invitee an Existing Account?
            $result = do_mysqli_query("1","
                select provider.providername, provider.replyemail, provider.alias, provider.providerid, provider.handle,
                    roominfo.radiostation
                    from provider 
                    left join statusroom on statusroom.providerid = provider.providerid
                    left join roominfo on roominfo.roomid = statusroom.roomid
                    where 
                    provider.active = 'Y' and
                    statusroom.roomid = $roomid
                    ");
            $i = 0;
            while($row = do_mysqli_fetch("1",$result)){

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


        $result = do_mysqli_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                values
                ( $chatid, $providerid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
            ");
        $result = do_mysqli_query("1",
            "
            update chatmembers set lastmessage=now(), lastread=now() where providerid= $providerid and chatid=$chatid and status='Y'
            ");        
        $result = do_mysqli_query("1",
            "
            update chatmaster set lastmessage=now() where chatid=$chatid and status='Y'
            ");        
        if($radiostation == 'Q'){
            $result = do_mysqli_query("1",
                "
                update chatmaster set radiostation='Q' where chatid = $chatid and status='Y'
                ");        
            
        }

    
        ExitRoutine("", $alert, $chatid,$passkey, $passkey64);
        exit();
        
    }
    

        
    
?>