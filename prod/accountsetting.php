<?php 
session_start();
require("validsession.inc.php");
include("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once ("notify.inc.php");
require_once ("chat.inc.php");
require_once("internationalization2.php");

/* This Routine is for performing automated processes after doing termsofuse agree */


$mode = @tvalidator("PURIFY", $_POST['mode'] );
$providerid = @tvalidator("ID",$_SESSION['pid']);
$caller = @tvalidator("PURIFY", $_SESSION['caller'] );


    if($mode == 'AGREE'){
    
        $result = pdo_query("1"," 
              select sponsor, enterprise, handle from provider where providerid = ? and termsofuse is null
                  ",array($providerid));
        if(!$row = pdo_fetch($result)){
            //If Terms of Use already filled then exit
            exit();
        }
        $sponsor = $row['sponsor'];
        $enterprise = $row['enterprise'];
        $handle = $row['handle'];
        
        pdo_query("1","update provider set termsofuse = now() where providerid=? and termsofuse is null ",array($providerid));
        
        if($sponsor == '' || $enterprise!='N'){
            exit();
        }
        //Temporary for testing
        //if(strstr($handle,"brax")!==false){
            AutoChat($sponsor, $providerid);
        //}
    }
    if($mode == 'DISAGREE'){
    
        pdo_query("1","update provider set termsofuse = null where providerid=? ",array($providerid));
    }
    if($mode == 'RESTART' && $caller == 'none'){
    
        SaveLastFunction($providerid,"", 0);
    }
    if($mode == 'SOCIALMEDIAPROMPT'){
        echo "Allow social interaction with all of $appname? Cancel to remain in a private space.";        
        exit();
    }
    if($mode == 'SOCIALMEDIAOK'){
    
        pdo_query("1","update provider set roomdiscovery='Y' where providerid=? ",array($providerid));
    }
    if($mode == 'SOCIALMEDIAENABLE'){
    
        pdo_query("1","update provider set roomdiscovery='Y' where providerid=? ",array($providerid));
    }
    if($mode == 'SOCIALMEDIADISABLE'){
    
        pdo_query("1","update provider set roomdiscovery='N' where providerid=? ",array($providerid));
    }
    if($mode == 'ROOMFEEDOFF')
    {
        pdo_query("1","update provider set roomfeed='N' where providerid=? ",array($providerid));
    }
    if($mode == 'ROOMFEEDON'){
    
        pdo_query("1","update provider set roomfeed='Y' where providerid=? ",array($providerid));
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
        $result = pdo_query("1"," 
                select providername, providerid, handle 
                from provider where handle in 
                (select autochatuser from sponsor where sponsor='?' and autochatuser!='')
                and active='Y'
                ",array($sponsor));
        if(!$row = pdo_fetch($result)){
            return;
        }
        $autochatuserid = $row['providerid'];
        $autochatuserhandle = $row['handle'];
        
        
        $welcome = 'Welcome!';
        $result = pdo_query("1"," 
                select welcome from sponsor where sponsor='?' and welcome!=''
                ",array($sponsor));
        if($row = pdo_fetch($result)){
            $welcome = $row['welcome'];
        }
        
        
        $sponsor = ucfirst($sponsor);
        
        $chatid = EstablishChatSession( $autochatuserid, $autochatuserhandle, "", "Welcome to $sponsor", "", 0, 0, "" );
        if($chatid == false){
            return;
        }
        
        pdo_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastmessage, lastread, lastactive, techsupport, mute, broadcaster)
                values
                ( $chatid, $autochatuserid, 'Y', now(), now(), now(), null, null, null )
            ");
        
        pdo_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastmessage, lastread, lastactive, techsupport, mute, broadcaster)
                values
                ( $chatid, $providerid,     'Y', now(), now(), now(), null, null, null )
            ");

        $message = $welcome;
        $encode = EncryptChat ($message,"$chatid","" );
        $encodeshort = EncryptChat ("Welcome!","$chatid","" );

        $result = pdo_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                values
                ( $chatid, $autochatuserid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
            ");
        $result = pdo_query("1",
            "
            update chatmembers set lastmessage=now(), lastread=now() where providerid= ? and chatid=? and status='Y'
            ",array($autochatuserid,$chatid));
        $result = pdo_query("1",
            "
            update chatmaster set lastmessage=now() where chatid=? 
            ",array($chatid));

        ChatNotificationRequest($autochatuserid, $chatid, $encodeshort, $_SESSION['responseencoding'],'P');
        
    }
