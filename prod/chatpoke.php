<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require ("SmsInterface.inc");

    $providerid = tvalidator("ID",$_POST['providerid']);
    $mode = tvalidator("PURIFY",$_POST[mode]);
    $email = tvalidator("EMAIL",$_POST[email]);
    $name = tvalidator("PURIFY",$_POST[name]);
    
    $result = pdo_query("1","
        select providername, replyemail, alias from
        provider where
        providerid = ?
            ",array($providerid));
    if($row = pdo_fetch($result))
    {
        $providername = $row[providername];
        $replyemail = $row[replyemail];
        $alias = $row[alias];
        //if($alias!='')
        //    $providername = $alias;
    }
    
    
    if($mode == 'C'){
    
        $invitationurl = "$prodserver/$installfolder//login.php?s=app";
    
        $message = "
        <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
            <h2 style='color:steelblue'>Hi $name. You have an active secure chat 
            message from $providername.</h2><br>
            Login to Brax.Me to continue your secure conversation.
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
        SendMail("0", "Secure Chat from $providername", "$message", "$message", "$name", "$email" );
    }
    if($mode == 'I')
    {
        $invitationurl = "$prodserver/$installfolder/invite.php?invite=$email&name=$name&chat=Y";
    
        $message = "
        <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica'>
            <h2 style='color:steelblue'>Hi $name. You have an active secure chat 
            message from $providername.</h2><br>
            <br><br>
            Please register on Brax.Me to see your secure conversation.
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
        SendMail("0", "Secure Chat from $providername", "$message", "$message", "$name", "$email" );
    }
   
    
        
?>