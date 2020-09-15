<?php
session_start();
require_once("config.php");
require ("SmsInterface.inc");
require ("sendmail.php");

if($batchruns!='Y')
    exit();

$category = $argv[1];

    if( $category == '')
    {
        //Exclude Tech Writers
        $result = do_mysqli_query("1","
            select distinct roominfo.room, invites.providerid, invites.name, invites.email, provider.providername, invites.roomid, invites.chatid from invites
            left join provider on provider.providerid = invites.providerid
            left join statusroom on invites.roomid = statusroom.roomid and statusroom.owner = statusroom.providerid
            left join roominfo on invites.roomid = roominfo.roomid
            where invites.email not in (select replyemail from provider where active='Y')
             and invites.status = 'Y'
             and retries < 5 
             and invites.chatid is null
             ");   
    }
    if( $category == 'C')
    {
        //Exclude Tech Writers
        $result = do_mysqli_query("1","
             select distinct invites.providerid, invites.name, invites.email, provider.replyemail, provider.companyname, 
             provider.providername, provider.alias, invites.roomid, invites.chatid from invites
             left join provider on 
                provider.providerid = invites.providerid
                     where invites.email not in (select replyemail from provider where active='Y')
             and roomid = 0 and retries < 20
             and chatid in (select chatid from chatmessage where chatmessage.chatid = invites.chatid )
             ");   
    }
    
     while( $row = do_mysqli_fetch("1",$result))
     {
        $providerid = $row['providerid'];
        $providername = $row['providername'];
        $inviteemail = $row['email'];
        $invitename = $row['name'];
        $replyemail = $row['replyemail'];
        $roomid = $row['roomid'];
        $room = $row['room'];
        $chatid = $row['chatid'];
        $alias = $row['alias'];
        if($alias!='')
            $providername = "$alias";
        $companyname = '';
        if($row['companyname']!='')
        {
            $companyname = " - ".$row['companyname'];
        }
        $invitetype = 'R';
        if(intval($chatid)>0)
            $invitetype = 'C';         
         
        $err = false;
         
        $e = explode("@", $inviteemail);
        if (!checkdnsrr($e[1], 'MX')) {
            echo "<br>$providerid domain $e[1] not valid<br>";
            $err = true;
        }         
        if( filter_var($inviteemail, FILTER_VALIDATE_EMAIL))
        {
            if( $invitetype == 'R')
            {
                do_mysqli_query("1","
                    update invites set retries=retries+1 where providerid=$providerid and
                        email='$inviteemail' and roomid=$roomid
                        ");
            }
            if( $invitetype == 'C')
            {
                do_mysqli_query("1","
                    update invites set retries=retries+1 where providerid=$providerid and
                        email='$inviteemail' and chatid=$chatid
                        ");
            }

           HandleAlert($providerid, $providername, $inviteemail, $invitename, $replyemail, $companyname, $invitetype, $room );
           //echo "OK<br>";
        }
        else 
        {
            echo "$inviteemail failed<br>";
            $err = true;
        }
        if($err == true)
        {
            do_mysqli_query("1","
                update invites set retries=99999 where providerid=$providerid and
                    email='$inviteemail' and roomid=$roomid
                    ");
            
            echo "
                update invites set retries=99999 where providerid=$providerid and
                    email='$inviteemail' and roomid=$roomid
                        ";
            
        }
        
     }
    
    function HandleAlert($providerid, $providername, $inviteemail, $invitename, $replyemail, $companyname, $invitetype, $room )
    {
        global $appname;
        global $rootserver;
        global $installfolder;
        global $app_smtp_email;
            
        $invitenameEncode = urlencode($invitename);
        $invitationUrl = "$rootserver/$installfolder/invite.php?invite=$inviteemail&name=$invitenameEncode";
       
        $result2 = do_mysqli_query("1","
            select * from provider where replyemail='$inviteemail' and active='Y' limit 1
            ");
        
        //User Never Signed Up
        if(!$row2 = do_mysqli_fetch("1",$result2))
        {
        
            if( $invitetype == 'R')
            {
                $message = "
                <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                    <h2 style='color:steelblue'>Hi $invitename. Join $providername$companyname on Brax.Me.</h2>
                    <h3 style='color:firebrick'>It's a place to share without an Internet footprint.</h3><br><br>
                    <b>Personal Message from $providername</b><br><br>
                    $invitemsg
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
                SendMail("0", "Invitation from $providername$companyname", "$message", "$message", "$invitename", "$inviteemail" );
            }
            if( $invitetype == 'C')
            {
                $message = "
                <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
                    <h2 style='color:steelblue'>Hi $invitename. $providername$companyname has an active chat message for you on Brax.Me.</h2>
                    <h3 style='color:firebrick'>Sign in to view your encrypted communication from $providername$companyname - $replyemail.</h3><br><br>
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
                SendMail("0", "$providername$companyname Secure Chat Invite", "$message", "$message", "$invitename", "$inviteemail" );
            }
            
            echo "$app_smtp_email<br>$message<br>";
            
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
        
        return $phone;
    }
    function FormatPhone( $phone )
    {
        $area = substr( $phone, 0, 3);
        $num1 = substr( $phone, 3, 3);
        $num2 = substr( $phone, 6, 4);
        
        if( $area == '')
            return "";
        
        return "(".$area.") ".$num1."-".$num2;
    }


?>