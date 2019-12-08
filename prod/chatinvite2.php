<?php
session_start();
require_once("config.php");

    $providerid = mysql_safe_string($_POST[providerid]);
    $mode = mysql_safe_string($_POST[mode]);
    $email = mysql_safe_string($_POST[email]);
    $name = mysql_safe_string($_POST[name]);
    $sms = mysql_safe_string($_POST[sms]);
    $name = ucwords($name);
    
    if( $sms!='')
        $sms = CleanPhone($sms);
    
    if("$email"=="")
    {
        $msg .= "&nbsp;&nbsp;Missing Email Address";
        $alert = 'Y';
        ExitRoutine($msg, $alert, 0);
    }
    
    
    
    
    /* Get Info on Owner/Inviter */
    $result = do_mysqli_query("1","
        select providername, replyemail, alias, companyname from
        provider where
        providerid = $providerid
            ");
    if($row = do_mysqli_fetch("1",$result))
    {
        $providername = $row[providername];
        $replyemail = $row[replyemail];
        $alias = $row[alias];
        if($row[companyname]!='')
            $companyname = " - ".$row[companyname];
        if($alias!='')
            $providername = $alias;
    }
    
    {
        $msg .= "&nbsp;&nbsp;Chatid $chatid/$providerid";
        $alert = 'Y';
        ExitRoutine($msg, $alert, 0);
    }    
    $chatid = EstablishChatSession($providerid);
    
    
    //Is Invitee an Existing Account?
    $result = do_mysqli_query("1","
        select providerid, providername, replyemail, alias, providerid, mobile from
        provider where
        replyemail='$email' and active='Y'
            ");
    if($row = do_mysqli_fetch("1",$result))
    {
        
        $mobile = $row[mobile];
        $recipientid = $row[providerid];

        $result2 = do_mysqli_query("1","
            select * from contacts where providerid=$providerid and email='$email' and
            email in (select replyemail from provider where replyemail='$email' and active='Y')
                ");
        if($row2 = do_mysqli_fetch("1",$result2))
        {
            $msg .= "&nbsp;&nbsp;$name is already on your contact list.";
            $alert = 'Y';
        }
        else 
        {
            if( $email != '')
            {
                $result2 = do_mysqli_query("1","
                    insert into contacts (providerid, name, email, sms, status, imapbox ) values
                    ($providerid, '$name', '$email', '$sms', 'Y', null  )
                        ");
            }


            $msg .= "&nbsp;&nbsp;$name has an account. Added to your contact list";
            $alert = '';
        }
        $result = do_mysqli_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastactive ) 
                values
                ( $chatid, $row[providerid], 'Y', now() );
        ");
    }
    else
    {
        if( $email != '')
        {
            $result = do_mysqli_query("1","
                insert into contacts (providerid, name, email, sms, status, imapbox ) values
                ($providerid, '$name', '$email', '$sms', 'Y', null  )
                    ");
        }
        $alert = 'C';

        //Delete prior chat invites
        $result = do_mysqli_query("1","
            delete from invites where chatid is not null and email ='$email' and providerid='$providerid'
                ");

        $inviteid = base64_encode(uniqid("$providerid"));

        $result = do_mysqli_query("1","
            insert into invites (providerid, name, email, sms, contactlist, roomid, chatid, invitedate, status, retries, inviteid ) values
            ($providerid, '$name', '$email', '$sms', '', 0, $chatid, now(), 'Y', 0, '$inviteid'  )
                ");
        $mobile = "N";

    }


    $msg .= "
            <br>
            <span class='pagetitle2' style='color:black'>&nbsp;$name Invited to Chat</span><br><br>
            <center> <span class='pagetitle' style='white-space:nowrap'>
            </span></center><br>
            ";
    
    //exit();
    
    /*
    //New Chat Invite - Manual
    if($alert == 'C' )
    {
        $notifytype = 'C';
        do_mysqli_query("1"," 
            insert into notifications (
            providerid, notifydate, status, notifytype,
            name, email, sms, 
            recipientid, mobile ) values (
            $providerid, now(), 'N', '$notifytype',
            '$name','$email','$sms',
            $recipientid, '$mobile' 
            )
                 ");
    }
    else
    {
        $notifytype = 'EC';
        do_mysqli_query("1"," 
            insert into notifications (
            providerid, notifydate, status, notifytype,
            name, email, sms, 
            recipientid, mobile ) values (
            $providerid, now(), 'N', '$notifytype',
            '$name','$email','$sms',
            $recipientid, '$mobile' 
            )
                 ");
    }
   */
    ExitRoutine($msg, $alert, $chatid);
    exit();
    
    
    
    function ExitRoutine( $msg, $alert, $chatid)
    {
        $arr = array('msg'=> "$msg",
                     'alert'=> "$alert",
                     'chatid'=> "$chatid"
                    );
        echo json_encode($arr);
        exit();
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
    
    function EstablishChatSession( $providerid )
    {
        /* Create Chat Session - Owner */
        $result = do_mysqli_query("1",
            "
                insert into chatmaster ( owner, created, status )
                values
                ( $providerid, now(), 'Y' );
            ");

        /* Get Chat ID of New Chat Session */
        $result = do_mysqli_query("1",
            "
                select chatid from chatmaster where owner = $providerid and status='Y' order by created desc
            ");

        if( $row = do_mysqli_fetch("1",$result))
        {
            $chatid = $row[chatid];
        }

        //Create New Chat Session Member (Owner)
        $result = do_mysqli_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastactive ) 
                values
                ( $chatid, $providerid, 'Y', now() );
        ");
        return $chatid;
    }
        
?>