<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require ("SmsInterface.inc");

    $providerid = tvalidator("ID",$_POST['providerid']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $roomid = intval(@tvalidator("ID",$_POST['roomid']));
    $inviteemail = @tvalidator("EMAIL",$_POST['inviteemail']);
    $invitesms = @tvalidator("PURIFY",$_POST['invitesms']);
    $invitename = @tvalidator("PURIFY",$_POST['invitename']);
    $invitemsg = @tvalidator("PURIFY",$_POST['invitemsg']);

    $inviteroomname = "";
    $inviteroomnameForSql = "";
    $result = pdo_query("1","
        select roomid, room from statusroom where owner=? and roomid=?
        ",array($providerid,$roomid));
    if( $row = pdo_fetch($result)){
    
        //$inviteroomname='';
        $inviteroom = $row['roomid'];
        $inviteroomname = $row['room'];
        $inviteroomnameForSql = tvalidator("PURIFY",$row['room']);
    }
    
    $result = pdo_query("1","
        select replyemail from provider where providerid=? and active='Y' and replyemail like '%.account@brax.me'
        ",array($providerid));
    if( $row = pdo_fetch($result)){
        EmailNotValid();
        exit();
    
    }
    
    
    $result = pdo_query("1","
        select count(*) as count from statusroom where owner=?
        ",array($providerid));
    $owned=0;
    if( $row = pdo_fetch($result)){
    
        $owned = intval($row['count']);
    }
    
    if( $mode == 'S'){
    
        

        ucwords( $invitename );
        $invitesms = CleanPhone($invitesms);
        
        $inviteid = base64_encode(uniqid("$providerid"));
        $inviteid = str_replace('=','',$inviteid);
        
        $result = pdo_query("1","
            insert into invites (providerid, name, email, sms, contactlist, roomid, invitedate, status, retries, inviteid )
            values ( $providerid, '$invitename', '$inviteemail', '$invitesms', '', $roomid, now(), 'Y', 0, '$inviteid' )
            ");

        
        
        $result = pdo_query("1","
            insert into contacts (providerid, contactname, email, sms, friend, imapbox ) values
            ($providerid, '$invitename', '$inviteemail', '$invitesms', '', null  )
                ");
        
        
        $result = pdo_query("1","
            select providername, replyemail from provider where providerid=?
            ",array($providerid));
        if($row = pdo_fetch($result)){
        
            
        }
        
        $invitemsg = stripslashes($invitemsg);
        
        $invitenameEncode = urlencode($invitename);
        $invitationUrl = "$rootserver/invite/$inviteid";
        
        $result2 = pdo_query("1","
            select providername, providerid, replyemail from provider where replyemail=? and active='Y' limit 1
            ",array($inviteemail));
        $member = false;
        if($row2 = pdo_fetch($result)){
        
            $member = true;
            $message = "
            <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                <h2 style='color:steelblue'>Hi $invitename! You have been added to a private $appname Room!</h2>
                <h3 style='color:firebrick'>$inviteroomname</h3><br>
                <b>Inviter: $row[providername]</b><br>
                $invitemsg
                <br><br><br>
                <a href='$rootserver'>
                Log in to see activity.
                </a>

                <br>
                <br>
                <a href='$rootserver'>
                <img src='$rootserver/img/lock.png' style='height:30px;width:auto' /><br>
                </a>

            </div>
            ";
            

            pdo_query("1","
                insert into statusroom ( roomid, room, owner, providerid, status, createdate, creatorid ) values
                ( ?, ?,$providerid, $row2[providerid],'',now(),? )
                ",array($roomid,$inviteroomnameForSql,$providerid));
            
            
        } else {
        
            $message = "
            <div style='width:600px;background-color:white;color:black;padding:40px;font-family:helvetica'>
                <h2 style='color:steelblue'>Hi $invitename! Join me in $appname.</h2><br>
                <h3 style='color:firebrick'>Room: $inviteroomname</h3><br><br>
                <b>Personal Message from $row[providername]</b><br><br>
                $invitemsg
                <br><br>

                <a href='$invitationUrl'>
                $invitationUrl
                </a>
                <br>
                <br>
                <a href='$rootserver'>
                <img src='$rootserver/img/lock.png' style='height:30px;width:auto' /><br>
                </a>
                

            </div>
            ";
        }
        SendMailV2("0", "$appname Invite from $row[providername]", $message, $message, $row['providername'], $row['replyemail'], $invitename, $inviteemail );
   
        //SendMail("0", "$appname Invite from $row[providername]", "$message", "$message", "$invitename", "$inviteemail" );
        if( $invitesms!='' && !$member ){
        
            $textmessage = "$row[providername] invites you to download $appname from App Store or join here $invitationUrl  ";
            $sessionid = uniqid("",false);
            SmsSendInvite( $invitesms, $providerid, $sessionid, $textmessage );
            
        }
        if( $invitesms!='' && $member ){
        
            $textmessage = "$appname - $row[providername] added you to Room $inviteroomname ";
            $sessionid = uniqid("",false);
            SmsSendInvite( $invitesms, $providerid, $sessionid, $textmessage );
            
        }
        exit();
    }
    

    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img src='../img/arrow-stem-circle-left-128.png' style='position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    if($caller=='room'){
    
        echo "
            <div>
            <center><span class='pagetitle2a'  style='color:black'><b>Invite to a Room</b></span>
            <br><br>
                <div class='divbuttontextonly feed showtop tapped'  style='color:black'
                    id='feed' data-roomid='$roomid' data-caller='$caller'>
                        $braxsocial
                            Room
                </div>
                <br><br>
            </center>
           ";
    } else {
        
        echo "
            <div>
            <center><span class='pagetitle2a' style='color:black'><b>Invite to a Room</b></span>
            <br><br>
                <div class='divbuttontextonly friends showtop tapped'  style='color:black'
                    id='friends' data-roomid='$roomid' data-caller='$caller'>
                        $braxsocial
                            Room Members
                </div>
                <br><br>
            </center>
           ";
        
    }
    
    /*
     * 
                    When you invite a friend to a BraxRoom, all members of the Room
                    will become contacts automatically so communication can begin 
                    immediately both in the room and on chat.
     */
    echo "  <center>
            <table  class='pagetitle3' style='background-color:transparent;border-collapse:collapse;width:300px;margin:auto;color:black'>
               <tr>
               <td class='gridcell' >
                    <div style='margin:auto'>
                    <div class='tipbubblegridstdborder' style='background-color:whitesmoke;color:black'>
                    <span class='pagetitle2a' style='color:black'>
                    Private Invite to $inviteroomname
                    </span>
                    </div>
                    <br>
                    Invitee Name<br>
                    <input class='invitename' type='text' size=35 placeholder='Invitee Name' /><br><br>
                    Email<br>
                    <input class='inviteemail' type='text' size=35  placeholder='Email'/><br><br>
                    or<br><br>
                    Mobile Phone (Text)<br>
                    <input class='invitesms' type='text' size=35  placeholder='Mobile Phone' /><br>
                    <span class='smalltext'>+Country Code if non-US</span>
                    <br>
                    <br>

                    Personal Message in Email Invitation<br>
                    <textarea name=invitemsg class=invitemsg cols=35 rows=5 style='border-size:1px;border-color:gray'></textarea>
                   <br><br>
                    <div class='invitebutton divbuttontext divbutton3_unsel' data-roomid='$roomid'>
                            &nbsp;&nbsp;
                            Send Invitation
                            <img class='icon15' src='../img/arrow-stem-circle-right-128.png' style='position:relative;top:3px' />
                    
                    </div>
                    <input class='invitelink' type='hidden' size=100 /><br>
                    </div>
                    <br><br>
               </td>
               </tr>
          </table>
          </center>
          </div>

         ";
    function CleanPhone( $phone )
    {
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );
        if($phone!='' && $phone[0]!='+') {
            $phone = "+1".$phone;
        }
        
        return $phone;
    }
    
   function SmsSendInvite( $sms, $providerid, $sessionid, $textmessage )
    {
        global $rootserver;
        global $installfolder;

        $message = $textmessage;
        
        if($sms[0]!='+')
            $sms = "+1".$sms;
        
        $si2 = new SmsInterface (false, false);
        $si2->addMessage ( $sms, $message, 0, 0, 169,true);

        if (!$si2->connect ('MaddisonCross002' ,'welcome1', true, false))
            echo "failed. Could not contact server.\n";
        elseif (!$si2->sendMessages ()) {
        
            echo "failed. Could not send message to server.\n";
            if ($si2->getResponseMessage () !== NULL)
                echo "<BR>Reason: " . $si2->getResponseMessage () . "\n";
        } else
            echo "<br><br><div style='padding:40px'><b>SMS Invitation Sent</b></div>";
    }        
    function EmailNotValid()
    {
        echo "<div style='padding:20px'>
                <div class='tipbubble gridstdborder pagetitle2a' style='margin:auto'>
                    In order to send an email invitation, your own email needs to be entered 
                    and validated. Please enter a valid email address in SETTINGS - MY IDENTITY and
                    respond to the verification email that will be sent to you.<br><br>
                    After your email is verified, you may freely use this feature!
                    
                </div>
                <br><br>
                <center>
                <div class='divbuttontext profilebutton mainbutton'>My Identity</div>
                </center>
            </div>
                ";
    }
        
?>