<?php
session_start();
require_once("config.php");
require ("SmsInterface.inc");
require ("htmlhead.inc.php");
require ("crypt.inc.php");
?>
        <title>Save Profile</title>
    </head>
    <body class="appbody">
        <script>$('body').animate({ scrollTop: $(this).offset().top - 800 }, 'fast');</script>       
        <br><br>

        <img class="viewlogomobile" src="../img/lock.png" style='height:20px'><br>
        <table class="smsg">
            

<?php

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

    $_SESSION['error'] = false;
    if( $_POST['providername'] == "" )
    {
        echo "<br>Missing Subscriber Name\n<br>";
        $_SESSION['error'] = true;
    }
    /*
    if( $_POST[companyname] == "" )
    {
        echo "<br>Missing Company Name\n<br>";
        $_SESSION[error] = true;
    }
     * 
     */
    if( $_POST['providerid'] == ""  || !is_numeric($_POST['providerid']))
    {
        echo "<br>Invalid Subscriber ID\n<br>";
        $_SESSION['error'] = true;
    }
    if (!filter_var($_POST['replyemail'], FILTER_VALIDATE_EMAIL)) 
    {
         echo "<br>Invalid Reply Email\n<br>";
         $_SESSION['error'] = true;
    }        
    
    if( $_POST['replysms'] == "" || strlen($_POST['replysms'])<10  )
    {
         //echo "<br>Invalid Reply SMS Phone\n<br>";
         //$_SESSION[error] = true;
    }

    if( $_SESSION['error']==true)
    {
       echo "<br>Click on BACK to correct\n<br>";
       exit();
    }

       

    //echo "$_POST[accountnote]";
    //$accountnote = mysqli_escape_string( $link, $_POST[accountnote]);
    //$accountnote = mysql_escape_string( $_POST[accountnote]);
    $accountnote = @tvalidator("PURIFY",$_POST['accountnote']);

    $result = do_mysqli_query("1", "SELECT active, announcement from service where msglevel='STATUS' /*test1*/");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        if($row['active']=='N')
        {
            echo "<br><br>$row[announcement]\n<br>";
            $_SESSION['status'] = "N";
            exit();
        }
    
    }
    
    //$stdSmsMsg = mysql_escape_string($_POST[stdsmsmsg]);
    
    $providerid = @tvalidator("PURIFY","$_SESSION[pid]");
    $loginid = @tvalidator("PURIFY",$_SESSION['loginid']);
    $replysms = @tvalidator("PURIFY","$_POST[replysms]");
    $replyemail = @tvalidator("PURIFY",strtolower("$_POST[replyemail]"));
    $notifications = @tvalidator("PURIFY","$_POST[notifications]");
    //if($notifications!=='Y'){
    //    $notifications = 'N';
    //}
    
    //Check for Change in REPLY EMAIL - Important!
    $result = do_mysqli_query("1", 
            "select replyemail, verified, handle from provider where providerid=$providerid "
            );
    $row = do_mysqli_fetch("1",$result);
    $orig_replyemail = $row['replyemail'];
    $orig_handle = $row['handle'];
    $verified = $row['verified'];
    if( $replyemail != $orig_replyemail && $verified == 'Y' )
    {
        $verified='R';
        $signupverificationkey = uniqid("", true);
    
        $result = do_mysqli_query("1", 
                "insert into verification (type, providerid, verificationkey, loginid, email, createdate ) values (".
                " 'ACCOUNT', $providerid, '$signupverificationkey', '$loginid', '$replyemail', now() ) "
                );
    }
    
    $active = 'Y';
    $terminateaccount = @tvalidator("PURIFY",$_POST['terminateaccount']) ;
    if($terminateaccount=='Y') {
        $active='N';
        $verified = 'Y';
    }
    
    $msgmasterkey = @tvalidator("PURIFY",$_POST['msgmasterkey']);

    $companyname = @tvalidator("PURIFY","$_POST[companyname]");
    $providername = @tvalidator("PURIFY",ucwords("$_POST[providername]"));
    $name2 = @tvalidator("PURIFY",ucwords("$_POST[name2]"));
    $handle = @tvalidator("PURIFY",("$_POST[handle]"));
    
    $providername = ltrim($providername);
    $name2 = ltrim($name2);
    
    $handle = str_replace("@","",$handle);
    $handle = str_replace("'","",$handle);
    $handle = str_replace('"',"",$handle);
    $handle = str_replace(',',"",$handle);
    $handle = "@".strtolower(str_replace(" ","",$handle));
    
    
    do_mysqli_query("1","delete from handle where email ='$replyemail' ");
    if( $handle!='@' && $active=='Y') //Check for Unique Handle
    {
        do_mysqli_query("1","insert into handle (handle, email, providerid) values ('$handle', '$replyemail',$providerid) ");
        $result = do_mysqli_query("1","select handle from handle where handle='$handle' ");
        if($row = do_mysqli_fetch("1",$result))
        {
            $handle = $row['handle'];
        }
        else
        {
            echo "<br>@Handle is already in use. Pick a different handle\n<br>";
            $_SESSION['error'] = true;
            exit();
            
        }
    }
    if($handle == '@') {
        $handle = '';
    }
    
    
    $defaultsmtp = @tvalidator("PURIFY",$_POST[defaultsmtp]);

    $avatarurl = @tvalidator("PURIFY",$_POST['avatarurl']);
    $industry = @tvalidator("PURIFY",$_POST['industry']);
    $sponsor = @tvalidator("PURIFY",$_POST['sponsor']);
    $sponsorhold = explode("/", $sponsor);
    $sponsor = $sponsorhold[0];
    $enterprise = '';
    
    if( count($sponsorhold)>1){
        if($sponsorhold[1]=='Y'){
            $enterprise = 'Y';
        }
        if($sponsorhold[1]=='N'){
            $enterprise = 'N';
        }
    }
    
    $allowkeydownload = @tvalidator("PURIFY",$_POST['allowkeydownload']);
    if( $allowkeydownload=='')
        $allowkeydownload='Y';
    $allowrandomkey = @tvalidator("PURIFY",$_POST['allowrandomkey']);
    if( $allowrandomkey=='')
        $allowrandomkey='N';
    $afterreadlifespan = @tvalidator("PURIFY",$_POST['afterreadlifespan']);
    if( $afterreadlifespan == '')
    {
        $afterreadlifespan = 0;
    }
    $cookies_recipient = @tvalidator("PURIFY",$_POST['cookies_recipient']);
    if( $cookies_recipient != 'Y')
        $cookies_recipient = 'N';
    
    $cookies_sender = @tvalidator("PURIFY",$_POST['cookies_sender']);
    if( $cookies_sender != 'Y')
        $cookies_sender = 'N';
    
    $enable_email = @tvalidator("PURIFY",$_POST['enable_email']);
    if( $enable_email != 'Y')
        $enable_email = 'N';
    
    
    $inactivitytimeout = @tvalidator("PURIFY",$_POST['inactivitytimeout']);
    if( $inactivitytimeout == "")
        $inactivitytimeout = 0;
    $inactivitytimeout = intval($inactivitytimeout) *60;
    $autosendkey = @tvalidator("PURIFY",$_POST['autosendkey']);
    $msglifespan = @tvalidator("PURIFY",$_POST['msglifespan']);
    if( $msglifespan == '')
    {
        $lifespan = @tvalidator("PURIFY",$_POST['lifespan']);
        if( $lifespan == '')
            $msglifespan = 864000;
        else
            $msglifespan = $lifespan * 24 * 60 * 60;
    }
    $imagedisplayseconds = @tvalidator("PURIFY",$_POST['imagedisplayseconds']);
    if( $imagedisplayseconds == '')
        $imagedisplayseconds = 0;
    
    $alias = @tvalidator("PURIFY",$_POST['alias']);
    
    //$contactphone1 = $contactphone1;
    //$contactphonec = $contactphonec;
    //$phoneoffice = $phoneoffice;
    $replysms = CleanPhone( $replysms );
    if( $replysms!='' && $replysms[0]!='+'  ){
        $replysms = "+1".$replysms;
    }
        
    
    $age = intval(@tvalidator("PURIFY",$_POST['age']));
    
    
    $vpnallowed = @tvalidator("PURIFY",$_POST['vpn_allowed']) ;
    $portal = @tvalidator("PURIFY","") ;
    if( $portal != 'Y')
        $portal = 'N';
    if( $defaultsmtp != '1')
        $defaultsmtp = '0';
        
        $result = do_mysqli_query("1", 
          " update provider " .
          " set verified='$verified', providername= '$providername', ".
          " name2='$name2', companyname= '$companyname', handle='$handle', ".
          " replyemail='$replyemail',msglifespan = $msglifespan, alias = '$alias', " .
          " industry='$industry', afterreadlifespan=$afterreadlifespan, allowkeydownload='$allowkeydownload', ".
          " allowrandomkey='$allowrandomkey', cookies_recipient='$cookies_recipient', cookies_sender='$cookies_sender', ".
          " inactivitytimeout = $inactivitytimeout, imagedisplayseconds = $imagedisplayseconds, ".
          " defaultsmtp=$defaultsmtp, active='$active', age=$age, featureemail='$enable_email', notifications='$notifications',sponsor='$sponsor' ".      
          " where providerid=$providerid "
          );
        
        //Create encrypted SMS
        do_mysqli_query("1","
            delete from sms where providerid = $providerid
            ");

        if($replysms!=''){
            $sms_encrypted = EncryptText($replysms, $providerid);
            //$sms_decrypted = DecryptText($sms_encrypted, $_SESSION['responseencoding'], $providerid);
            do_mysqli_query("1","
                insert into sms (providerid, sms, encoding ) values 
                (
                    $providerid, '$sms_encrypted','$_SESSION[responseencoding]'
                )
            ");
        }
        
        
        $result = do_mysqli_query("1",
            "
            update contacts set handle ='$handle' where handle='$orig_handle' and '$orig_handle'!=''
            "
        );

        $result = do_mysqli_query("1", 
            " update staff set staffname='$providername' where loginid='admin' and providerid=$providerid " 
          );
        
    //Account Termination Cleanup
    if($active == 'N'){
        do_mysqli_query("1","delete from invites where email='$replyemail' and chatid is not null ");
    }
    if($enterprise != ''){
        do_mysqli_query("1","update provider set enterprise = '$enterprise' where providerid = $providerid ");
    }
    
    $errorstate = true;
            
        
    if ($result) 
    {
        if( $terminateaccount == 'Y'){
            echo "<br>Account will be closed upon logout.<br>";
            
        } else {
            echo "<br>Subscriber info Saved<br>";
        }
    }
    
    //Reverify Email
    if( $verified == 'R')
    {
        $message = 
                "<html><body>".
                "<b>$appname Security Alert</b><br><br>\r\n\r\n".
                "You have modified your 'Reply Email'<br><br>\r\n\r\n".
                "We need to re-verify your identity.<br>\r\nPLEASE CLICK LINK BELOW to validate this email address.<br>\r\n".
                "$rootserver/$installfolder/verify.php?i=$signupverificationkey<br>\r\n".
                "(Cut and paste link to browser if you cannot click it)<br><br>\r\n\r\n".
                "<img src='$rootserver/img/lock.png' style='height: 100px; width: auto'><br>".
                "<b>Subscriber Info</b><br>\r\n".
                "Subscriber ID: $providerid<br>\r\n".
                "Login ID: $loginid<br>\r\n".
                "Account Name: $providername<br>\r\n";
                        
        
        $message .= 
        
                "Reply Text Phone: $replyphone<br>\r\n".
                "Reply Email: $replyemail<br><br>\r\n\r\n".
                "AFTER validating your email with the link above you may log in to the system.".
                "<br><br>\r\n\r\n";


                $message .=
                "$rootserver<br>".
                "</body></html>";
                
        
        $to = "$replyemail";
        $subject = "$appname Email Re-Verification";
        $from = "donotreply@brax.me";
        $headers = "From: 'Brax.Me' <$from>\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $mailstatus = mail( $to, $subject, $message, $headers);
        if( $mailstatus )
        {
            echo "<br><h3>You changed your 'Reply Email'. We sent a verification Email to<br>".
                 "$replyemail.<br>Please respond to it to verify your identity.</h3><br>";
            $result = do_mysqli_query("1", "update staff set email='$replyemail' where loginid='$loginid' and providerid=$providerid ");
        }
        else 
        {
            echo "<br><h3>You changed your 'Reply Email'.</h3><br>";
            echo "Verification Email sent to $replyemail failed. Your Reply Email was restored to the original.";
            $result = do_mysqli_query("1", "update provider set verified='Y', replyemail = '$orig_replyemail' where providerid=$providerid ");
        }

        
        $message = 
                "<html><body>".
                "<b>$appname Security Alert!</b><br><br>\r\n\r\n".
                "The email address associated with $providername has been changed.<br><br>\r\n\r\n".
                "If this is authorized, no further action is necessary. If this is not authorized, please login to your $appname ".
                "account, change your password, and restore your original email address. Your account has been breached.<br><br>\r\n\r\n".
                "<img src='$rootserver/img/lock.png' style='height: 100px; width: auto'><br>".
                "<b>Subscriber Info</b><br>\r\n".
                "Subscriber ID: $providerid<br>\r\n".
                "Login ID: $loginid<br>\r\n".
                "Account Name: $providername<br>\r\n";
        
        
        $to = "$orig_replyemail";
        $subject = "$appname Security Warning -Email Changed";
        $from = "donotreply@brax.me";
        $headers = "From: 'Brax.Me' <$from>\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $mailstatus = mail( $to, $subject, $message, $headers);
        if( $mailstatus )
        {
        }
        
        
    }
    
    
    

    echo "<br>$_SESSION[returnurl]";
?>
        </table>
    </body>
</html>



