<?php 
session_start();
require("validsession.inc.php");
include("config.php");
require_once("crypt.inc.php");
require_once ("notify.inc.php");
require_once ("chat.inc.php");
require_once("internationalization2.php");

/* This Routine is for performing automated processes after doing termsofuse agree */


$mode = @mysql_safe_string( $_POST['mode'] );
$providerid = @mysql_safe_string( $_SESSION['pid'] );
$caller = @mysql_safe_string( $_SESSION['caller'] );


    if($mode == 'AGREE'){
    
        $result = do_mysqli_query("1"," 
              select sponsor, enterprise, handle from provider where providerid = $providerid and termsofuse is null
                  ");
        if(!$row = do_mysqli_fetch("1",$result)){
            //If Terms of Use already filled then exit
            exit();
        }
        $sponsor = $row['sponsor'];
        $enterprise = $row['enterprise'];
        $handle = $row['handle'];
        
        do_mysqli_query("1","update provider set termsofuse = now() where providerid=$providerid and termsofuse is null ");
        
        if($sponsor == '' || $enterprise!='N'){
            exit();
        }
        //Temporary for testing
        //if(strstr($handle,"brax")!==false){
            AutoChat($sponsor, $providerid);
        //}
    }
    if($mode == 'DISAGREE'){
    
        do_mysqli_query("1","update provider set termsofuse = null where providerid=$providerid ");
    }
    if($mode == 'RESTART' && $caller == 'none'){
    
        SaveLastFunction($providerid,"", 0);
    }
    if($mode == 'SOCIALMEDIAPROMPT'){
        echo "Allow social interaction with all of $appname? Cancel to remain in a private space.";        
        exit();
    }
    if($mode == 'SOCIALMEDIAOK'){
    
        do_mysqli_query("1","update provider set roomdiscovery='Y' where providerid=$providerid ");
    }
    if($mode == 'SOCIALMEDIAENABLE'){
    
        do_mysqli_query("1","update provider set roomdiscovery='Y' where providerid=$providerid ");
    }
    if($mode == 'SOCIALMEDIADISABLE'){
    
        do_mysqli_query("1","update provider set roomdiscovery='N' where providerid=$providerid ");
    }
    if($mode == 'ROOMFEEDOFF')
    {
        do_mysqli_query("1","update provider set roomfeed='N' where providerid=$providerid ");
    }
    if($mode == 'ROOMFEEDON'){
    
        do_mysqli_query("1","update provider set roomfeed='Y' where providerid=$providerid ");
    }


    function AutoChat($sponsor, $providerid )
    {
        return;
        
        //Cancelled here. Now handled at Room Join
        //
        //
        //
        //
        //Check if AUTOCHATUSER is populated for Sponsor
        $result = do_mysqli_query("1"," 
                select providername, providerid, handle 
                from provider where handle in 
                (select autochatuser from sponsor where sponsor='$sponsor' and autochatuser!='')
                and active='Y'
                ");
        if(!$row = do_mysqli_fetch("1",$result)){
            return;
        }
        $autochatuserid = $row['providerid'];
        $autochatuserhandle = $row['handle'];
        
        
        $welcome = 'Welcome!';
        $result = do_mysqli_query("1"," 
                select welcome from sponsor where sponsor='$sponsor' and welcome!=''
                ");
        if($row = do_mysqli_fetch("1",$result)){
            $welcome = $row['welcome'];
        }
        
        
        $sponsor = ucfirst($sponsor);
        
        $chatid = EstablishChatSession( $autochatuserid, $autochatuserhandle, "", "Welcome to $sponsor", "", 0, 0, "" );
        if($chatid == false){
            return;
        }
        
        do_mysqli_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastmessage, lastread, lastactive, techsupport, mute, broadcaster)
                values
                ( $chatid, $autochatuserid, 'Y', now(), now(), now(), null, null, null )
            ");
        
        do_mysqli_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastmessage, lastread, lastactive, techsupport, mute, broadcaster)
                values
                ( $chatid, $providerid,     'Y', now(), now(), now(), null, null, null )
            ");

        $message = $welcome;
        $encode = EncryptChat ($message,"$chatid","" );
        $encodeshort = EncryptChat ("Welcome!","$chatid","" );

        $result = do_mysqli_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                values
                ( $chatid, $autochatuserid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
            ");
        $result = do_mysqli_query("1",
            "
            update chatmembers set lastmessage=now(), lastread=now() where providerid= $autochatuserid and chatid=$chatid and status='Y'
            ");
        $result = do_mysqli_query("1",
            "
            update chatmaster set lastmessage=now() where chatid=$chatid 
            ");

        ChatNotificationRequest($autochatuserid, $chatid, $encodeshort, $_SESSION['responseencoding'],'P');
        
    }
