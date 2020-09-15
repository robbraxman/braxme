<?php
session_start();
require_once("config.php");
require ("SmsInterface.inc");
require ("sendmail.php");
require ("crypt.inc.php");


    $gcm = @mysql_safe_string($_POST['gcm']);
    $apn = @mysql_safe_string($_POST['apn']);
    $mobile = $gcm.$apn;

    if($gcm!='')
    {
        $source="?s=android&gcm=$gcm";
    }
    else
    if($apn!='')
    {
        $source="?s=ios&apn=$apn";
    }
    else {
        $source="?s=web";
    }



    $accountnote = @mysql_safe_string($_POST['accountnote']);

    $result = do_mysqli_query("1", "SELECT active, announcement from service where msglevel='STATUS'  /*test2*/");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        if($row['active']=='N')
        {
            echo "<br><br>$row[announcement]\n<br>";
            $_SESSION['status'] = "N";
            exit();
        }
    }
    

    
    $providerid = @mysql_safe_string("$_POST[providerid]");
    $password = @mysql_safe_string("$_POST[password]");
    $providername = ucwords(stripslashes(@mysql_safe_string($_POST['providername'])));
    $companyname = ucwords(stripslashes(@mysql_safe_string($_POST['companyname'])));
    $invited = @mysql_safe_string("$_POST[invited]");
    $replysms = @mysql_safe_string($_POST['replysms']);
    $replyemail = strtolower(@mysql_safe_string($_POST['replyemail']));
    $dealeremail = strtolower(@mysql_safe_string($_POST['dealeremail']));
    $password = strtolower( $password);
    $invitesource = @mysql_safe_string("$_POST[emailinvite]");
    $msgmasterkey = '';
    
    $providername = ltrim($providername);
    
    
    if( $invited == "H" )
    {
        echo 
        "<br><br>";
    }
    
    if( $invited !== "H" )
    {

        echo 
        "<br><br><br><br>
        <center><img class='viewlogomsg' src='../img/logo.png' style='height:45px'></center><br>
        <table class='smsg'>
        ";
    }
    
    
    $_SESSION['error'] = false;
    if( $providername == "" )
    {
         echo "<br>Missing Subscriber Name\n<br>";
         $_SESSION['error'] = true;
    }
    if( $password == "" )
    {
         echo "<br>Missing Password\n<br>";
         $_SESSION['error'] = true;
    }
    
    if( $providerid == ""  || !is_numeric($providerid))
    {
         echo "<br>Invalid Subscriber ID\n<br>";
         $_SESSION['error'] = true;
    }
    if (!filter_var($replyemail, FILTER_VALIDATE_EMAIL)) 
    {
         echo "<br>Invalid Email Address\n<br>";
         $_SESSION['error'] = true;
    }        
    
    
    if( $_SESSION['error']==true)
    {
        echo "<br><a href='$rootserver/l.php?s=app'>Restart</a>";
        exit();
    }
    
    
    $industry = @mysql_safe_string($_POST['industry']);
    $enterprise = @mysql_safe_string($_POST['enterprise']);
    $avatarurl = @mysql_safe_string($_POST['avatarurl']);
    $handle = @mysql_safe_string($_POST['handle']);
    $roomhandle = @mysql_safe_string($_POST['roomhandle']);
    $roomid = @mysql_safe_string($_POST['roomid']);
    if($industry!='personal'){
        $enterprise = 'Y';
    }
    
    $replysms = CleanPhone( $replysms );
    if( $replysms!='' && $replysms[0]!='+'){
        $replysms = "+1".$replysms;
    }
    
    
    
    
    $lifespan = @mysql_safe_string("$_POST[lifespan]");
    if( $lifespan == '')
        $msglifespan = 864000;
    else
        $msglifespan = $lifespan * 24 * 60 * 60;
        
    
    $serverhost = "$rootserver";
    $loginid = @mysql_safe_string($_POST['loginid']);
    $loginid = strtolower($loginid);
    $autosendkey = 'N';
    $active = 'Y';

    $newmsgurl = '';
    

    if( DuplicateCheck($replyemail, $invited, $enterprise))
    {
        exit();
    }

    $handle = str_replace("@","",$handle);
    $handle = str_replace("'","",$handle);
    $handle = str_replace('"',"",$handle);
    $handle = str_replace('%',"",$handle);
    $handle = "@".strtolower(str_replace(" ","",$handle));
    if($handle!=='@'){
    
        do_mysqli_query("1","insert into handle (handle, email, providerid) values ('$handle', '$replyemail',$providerid) ");
        $result = do_mysqli_query("1","select handle from handle where handle='$handle' ");
        if($row = do_mysqli_fetch("1",$result))
        {
            $handle = $row['handle'];
        }
        else
        {
            echo "
                <span style='font-size:16px'>
                <center>
                ";
            
            echo "<br>Handle $handle is already in use. Pick a different handle\n<br>";
            echo "<br><br><a href='$rootserver/$installfolder/invite.php?apn=$apn&gcm=$gcm'>Redo Sign Up</a>";
            echo "</span></center>";
            exit();
            
        }
        $result = do_mysqli_query("1","select handle from provider where handle='$handle' and active = 'Y' ");
        if($row = do_mysqli_fetch("1",$result))
        {
            echo "
                <span style='font-size:16px'>
                <center>
                ";
            echo "<br>Handle $handle is already in use. Pick a different handle\n<br>";
            echo "<br><br><a href='$rootserver/$installfolder/invite.php?apn=$apn&gcm=$gcm'>Redo Sign Up</a>";
            echo "</span></center>";
            exit();
        }
    }
    if($handle == '@'){
        $handle = '';
    }
    
    
        
    $result = do_mysqli_query("1", 
      " select providerid from provider where providerid=$providerid ");

    if ($row = do_mysqli_fetch("1",$result)) 
    {
        if($row['providerid']==$providerid)
        {
           echo "<br>Duplicate Subscriber ID $providerid. You have already saved this Subscriber.<br>";
           exit();
        }
    }
    
    $smsreceipt = "";
    $dealer = "";
    $contractperiod = "0";
    $contracttype = "Trial";
    $allowtexting = 'Y';
    $invitedemail ="";
    //$verified = 'N';
    
    //Temporary
    $verified = 'N';
    //$invitedemail = $replyemail;
    
    if($invited == 'Y'){
        $invitedemail = $replyemail;
        $verified = 'Y';
    }

    //Invitation - Add Email if Missing so invite can be sent with SMS only
    if($replysms!='+1' && $replysms!='' && $invited=='Y')
    {
        $result = do_mysqli_query("1", 
            "
            update invites set email = '$replyemail' where sms='$replysms'
            and email = '' and status='Y'
            "
          );
    }
    
    //Invitation Code
    if( $roomid !=='')
    {
        $result = do_mysqli_query("1", 
            "
            select roomhandle.handle, 
            statusroom.room, statusroom.owner, statusroom.ownername
            from statusroom
            left join roomhandle on roomhandle.roomid = statusroom.roomid
            left join roominfo on roominfo.roomid = statusroom.roomid
            where statusroom.roomid=$roomid and statusroom.owner=statusroom.providerid
            "
          );
        if($row = do_mysqli_fetch("1",$result))
        {
            do_mysqli_query("1","
                insert into statusroom ( roomid, room, owner, providerid, ownername, status, createdate, creatorid ) values
                ( $roomid, '$row[room]',$row[owner], $providerid, '$row[ownername]','',now(),$providerid )
                ");
        }
        
    }
    //Invitation Code
    if( $roomhandle !=='')
    {
        $result = do_mysqli_query("1", 
            "
            select roomid, 
            (select owner from statusroom where roomhandle.roomid = statusroom.roomid limit 1 ) as owner
            from roomhandle where handle='$roomhandle' 
            "
          );
        if($row = do_mysqli_fetch("1",$result))
        {
            $roomid = $row['roomid'];
            $owner = $row['owner'];
            
            $inviteid = base64_encode(uniqid("$owner"));
            
            $result = do_mysqli_query("1", 
                "
                insert into invites 
                (providerid, name, email, status, invitedate, 
                roomid, contactlist, sms, retries, retrydate, chatid, inviteid )
                values
                ($owner, '$providername','$replyemail','Y', now(),
                $roomid, '','',0, null, null, '$inviteid' )
                "
              );
            $result = do_mysqli_query("1","
                insert into contacts (providerid, contactname, email, friend, imapbox, blocked ) values
                ($owner, '$providername', '$replyemail', '', null, ''  )
            ");
            
        }
        
    }
    
    
    $result = do_mysqli_query("1", 
      "insert into provider " .
      "(providerid, createdate, providername, name2, companyname, " .
      "active, contractperiod, contracttype, dealer, replyemail, loginid, ".
      "serverhost, newmsgurl, allowtexting, msglifespan, avatarurl, enterprise, industry, allowkeydownload, ".
      " allowrandomkey, cookies_recipient, cookies_sender, dealeremail, inactivitytimeout, verified, verifiedemail, proxy,".
      " invitesource, featureemail, notifications, handle ) " .
      "values (" .
      " $providerid, now(), '$providername', '$providername', '$companyname', ".
      " '$active',  ".
      "  $contractperiod,  '$contracttype','$dealer','$replyemail','$loginid', ".
      " '$serverhost', '$newmsgurl', '$allowtexting', $msglifespan, '$avatarurl',".
      " '$enterprise','$industry','Y','N', 'N','Y','$dealeremail', 0, '$verified','$invitedemail','N',"
            . "'$invitesource','$invitesource','Y','$handle' ".
      " )" 
      );

    //Free Trial ******************************
    $msgcountbal = 10;
    //Free Trial ******************************

    

    $result = do_mysqli_query("1", 
      " delete from staff where providerid=$providerid and loginid='$loginid' ");

    $result = do_mysqli_query("1", 
      " insert into staff (providerid, loginid, adminright, emailalert, workgroup, staffname, active, email) values  " .
      " ($providerid, '$loginid', 'Y',  'Y', 'ADMIN', '$providername','Y', '$replyemail')");
    
    if( "$password"!="")
    {
        $pwd_hash = password_hash($password, PASSWORD_DEFAULT);
        $result = do_mysqli_query("1", 
         " 
            update staff set 
            pwd_ver = 3, 
            pwd_hash = '$pwd_hash',
            fails = 0,
            onetimeflag=''
            where providerid='$providerid' and loginid='$loginid' 
            
         ");
    }
    
    //Create encrypted SMS
    do_mysqli_query("1","
        delete from sms where providerid = $providerid
        ");
    
    if($replysms!=''){
        $sms_encrypted = EncryptText($replysms, $providerid);
        do_mysqli_query("1","
            insert into sms (providerid, sms, encoding ) values 
            (
                $providerid, '$sms_encrypted','$_SESSION[responseencoding]'
            )
        ");
    }

    
    if( $enterprise == 'Y')
    {
        $result = do_mysqli_query("1", 
          " 
              insert into msgplan (providerid, planid, datestart, dateend, count, active )
              values ($providerid, 'ALERT1',now(), now(), 1, 'Y')
          ");
        
    }
    
    $errorstate = true;
            
        
    if ($result) 
    {
       //echo "<br>Subscriber info Saved<br>";
        $errorstate = false;

    }
    else
    {
        $result = do_mysqli_query("1", 
          " select providerid from provider where providerid=$providerid ");

        if ($row = do_mysqli_fetch("1",$result)) 
        {
            if($row['providerid']!=$providerid)
               echo "<br>Subscriber not Found or Not Active<br>";
            else
            {
               echo "<br><center>New Subscriber $providerid saved</center><br>";
                $errorstate = false;
            }
        }
    }
    if( $errorstate == false )
    {
        ShowStatus( $providerid, $providername, $replyemail, $replysms, $loginid, $password, $companyname, $mobile, $invited, $enterprise, $handle );

    }

    function DuplicateCheck($replyemail, $invited, $enterprise ){
        global $rootserver;
        global $installfolder;
        global $source;
        
        $loginscript = "login.php";
        
        $result = do_mysqli_query("1",
                "select providerid from provider where replyemail='$replyemail' and active='Y' "
                );
        if( $row = do_mysqli_fetch("1",$result))
        {
            
            if( $invited == 'Y')
            {
                echo "
                <span style='font-size:16px'>
                <center>
                    $replyemail<br>
                 is a duplicate address.<br><br>
                 This email address already<br> 
                 belongs to an existing subscriber.
                 <br><br>
                 <a href='$rootserver/$installfolder/forgotreset.php?pid=$row[providerid]&loginid=admin'>
                 Tap/Click here to get a temporary password.
                 </a><br>
                 Change your password as soon as possible.<br>
                 <br><br>
                 <a href='$rootserver/$installfolder/$loginscript$source'>
                 Tap/Click here to return to Login.
                 </a><br>
                 

                 </center>
                 </span>
                 <script>
                 try {
                 localStorage.pid = '$replyemail';
                 }
                 catch(err)
                 {}
                 </script>
                 ";
                return true;
            }
            else
            {
                echo "
                    <html>
                    <body style='background-color:white;color:black;font-family:Helvetica Nueue, Helvetica, san-serif;font-weight:200'>
                    <center><h2>Sorry!</h2>
                    <p class=margined>This email address is a duplicate account.<br>A new account was not established.<br>
                    You can reset your password using 'Forgot Password' on the Login page.
                    </p>
                    <br><br>
                    <a href='invite.php'>Back to Sign Up</a>
                    </center>
                    </body></html>
                    ";
                return true;
                
            }
        }
        
        return false;
    }

    function ShowStatus( $providerid, $providername, $replyemail, $replysms, $loginid, $password, $companyname, $mobile, $invited, $enterprise, $handle )
    {
        global $rootserver;
        global $installfolder;
        
        
            $loginscript = "l.php";
        
            
            SendSignUpEmail( $providerid, $providername, $replyemail );
        

            $_SESSION['signupproviderid'] = "$replyemail";
            $_SESSION['signuploginid'] = "$loginid";

            $_SESSION['returnurl'] = "<br><a href='$loginscript'>Login to Your Account</a>";
            
            //Signed up from #Handle Invite
            if( $invited == 'H')
            {
                echo "
                    <html>
                    <body style='background-color:gold;color:black;font-family:Helvetica Nueue, Helvetica, san-serif;font-weight:100'>
                    <center><h2>Done!</h2>
                    <p class=margined>You are now signed up.</p>
                    </body></html>
                    ";
            }
            else
            //Standard Signup
            //if( $invited == 'Y')
            {
                $jspassword = EncryptJs($password,"");
                echo "
                    <html>
                    <title></title>
                    <body class='newmsgbody margined'>
                    <br><br><br>
                    <script>
                    localStorage.pid = '$replyemail';
                    localStorage.loginid = '$loginid';
                    localStorage.swt = '$jspassword';
                    if( '$mobile'!='')
                    {
                        localStorage.changepassword = 1;
                    }
                    </script>
                    ";
                if($handle!='@' && $handle!=''){
                echo "
                    <script>
                    localStorage.pid = '$handle';
                    </script>
                    ";
                    
                }

                //Instruction for NON MOBILE USERS
                /*
                if($mobile=='' )
                {
                    echo "";
                    echo "<center><h2>Done!</h2>";
                    echo "<p class=margined>You are now signed up. You can Login to your account below or go to $rootserver.<br>";
                    echo "</p><a href='$rootserver/l.php' style='text-decoration:none'><h2>Login to Your Account</h2></a>"; 
                    echo "<br><br><br>For the best user experience, download the Brax.Me Mobile App (iOS/Android)
                        and use the same login credentials provided here.
                        <br>

                        <div style='display:inline;background-color:transparent;padding:10px;width:auto;margin:auto'>
                        <a href='http://itunes.com/apps/braxme' style='text-decoration:none'>
                         <img class='appstore' src='../img/appStore.png' style='height:50px' >
                        </a>
                         &nbsp;&nbsp;
                        <a href='https://play.google.com/store/apps/details?id=me.brax.app1' style='text-decoration:none'>
                         <img class='appstore' src='../img/androidplay.png' style='height:50px' >
                        </a>
                         </div>
                         </center>
                     ";
                }*/
                //Instruction for NON MOBILE USERS
                //if($mobile!=='')
                {
                    echo "";
                    echo "<center><h2>Done!</h2>";
                    echo "<p class=margined>You are now signed up as $handle<br>You can Login to your account now.<br>";
                    echo "</p><a href='$rootserver/$loginscript' style='font-family:helvetica;text-decoration:none'><h2>Start</h2></a>"; 
                }
                echo "
                    </body>
                    </html>
                ";

            }

        
        
    }
    function SendSignUpEmail( $providerid, $providername, $replyemail )
    {
        global $appname;
        global $rootserver;
        global $installfolder;
        
        $signupverificationkey = uniqid("", true);
        do_mysqli_query("1", 
                "insert into verification (type, providerid, verificationkey, loginid, email, createdate ) values (".
                " 'ACCOUNT', $providerid, '$signupverificationkey', 'admin', '$replyemail', now() ) "
                );
        
        $message = 
                "<html><body>".
                "<br><br><b>Thank You for signing up with $appname.</b><br><br>".
                "One final step: We need to verify your identity and your control of this email address.<br>".
                "If you skip this step, you will not be able to change your password and you may lose access to your account.<br>".
                "PLEASE CLICK LINK BELOW to verify this email address.<br><br>".
                "<a href='$rootserver/$installfolder/verify.php?i=$signupverificationkey'>$rootserver/$installfolder/verify.php?i=$signupverificationkey</a> <br><br>".
                "(Cut and paste link to browser if you cannot click it)<br><br>".
                "<a href='https://brax.me'>".
                "<img src='$rootserver/img/lock.png' style='height:30px; width: auto'>".
                "</a><br><br><br>".
                "</body></html>";
        
        $messagealt = 
                "Thank You for signing up with $appname.\r\n\r\n".
                "One final step: We need to verify your identity and your control of this email address.\r\n".
                "If you skip this step, you will not be able to change your password and you may lose access to your account.\r\n".
                "PLEASE cut and paste the link below to your browser (or click it if you are able)\r\n\r\n".
                "$rootserver/$installfolder/verify.php?i=$signupverificationkey \r\n".
                "(Cut and paste link to browser if you cannot click it)\r\n\r\n".
                "\r\n\r\nhttps://brax.me\r\n\r\n";

        
        SendMail("0", "Thank You for Signing Up!", "$message", "$messagealt", "$providername", "$replyemail" );

        $braxemail = "techsupport01@brax.me";
        SendMail( "0", "Signup", "$message", "$message", "$providername", "$braxemail" );
                
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
