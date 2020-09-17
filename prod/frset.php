<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require_once("notifyfunc.php");

    $_SESSION['returnurl']="<a href='login.php'>Login</a>";
    $temp = rand ( 100000 , 999999 );
    $_SESSION['temporarypassword'] = $temp;

    $providerid = @tvalidator("ID", "$_REQUEST[pid]");
    $loginid = @tvalidator("PURIFY", "$_REQUEST[l]");
    $sig = @tvalidator("PURIFY", "$_REQUEST[s]");
    $origproviderid = $providerid;
    $ip = tvalidator("PURIFY",$_SERVER['REMOTE_ADDR']);

    $handle = "";
    $replyemail = "";
    $verified = "";
    //if( strpos( (string) $providerid,"@")!==false ){
    
        $result = pdo_query("1", 
           "select providerid, verified, replyemail, handle from provider "
                . "where (providerid=? or replyemail = ? "
                . "or handle=?) and active='Y'  ",
                array($providerid,$providerid,$providerid)
          );
        
        if ($row = pdo_fetch($result)) {
        
            $providerid = $row['providerid'];
            $verified = $row['verified'];
            $handle = $row['handle'];
            $replyemail = $row['replyemail'];
        }
    //}
    
    if($handle!='' && $handle!='@'){
        $origproviderid = $handle;
    } else {
        $origproviderid = $replyemail;
    }
    
    //Validation Checks
    if( $providerid == ""  || !is_numeric($providerid))
    {
        echo "Invalid Subscriber";
        exit();
    }
    if( $loginid == "" )
    {
        $loginid = "admin";
    }

    
    $result = pdo_query("1","
            SELECT staff.email, provider.verified 
            from staff 
            left join provider on staff.providerid = provider.providerid
            where staff.providerid = ? and staff.loginid = ?  
            ",array($providerid,$logind));
    

    if ($row = pdo_fetch($result)) {
    
            if( $row['email'] == ''){
            
                echo "This user has no email address assigned. The Administrator needs to assign it";
                exit();
                
            }
            $verified = $row['verified'];
            $pwd_hash = password_hash("$_SESSION[temporarypassword]", PASSWORD_DEFAULT);
            
            $result = pdo_query("1",
                    "
                        update staff set 
                        pwd_ver = 3,
                        pwd_hash = ?,
                        fails=0,
                        onetimeflag='Y'
                        where providerid= ? and loginid = ?
                    ",array($pwd_hash, $providerid,$loginid)
                );
            pdo_query("1", 
                "insert into forgotlog (email,loginid, createdate, status, ip, temppassword) values 
                 (?,?, now(), 'Y',?,'$_SESSION[temporarypassword]'
                     ) 
                ",array($providerid,$loginid,$ip)
              );
            
            //$result = pdo_query("1",
            //        "update forgotlog  set temppassword='$_SESSION[temporarypassword]' where email='$row[email]' and createdate >= date_add(date(now()),INTERVAL -1 DAY)  "
            //    );
            
            //echo "password:$_SESSION[temporarypassword]/$row[email]";
                
                $_SESSION['message'] = 
                        /*
                        "We're SORRY. Were you not able to Login after signing up?\r\n\r\n ".
                        "We just noticed a glitch in the Sign Up Security which may have ".
                        "interrupted your service. We will extend your FREE TRIAL for another 10 days ".
                        "for bearing with us. Please give it another try.\r\n\r\n".
                        "Best regards,\r\n".
                        "Tech Support\r\n\r\n".
                         * 
                         */
                         
                         
                        "Your $appname Password has been changed.\r\n<br>" .
                        "Your One-Time-Use Password is: $_SESSION[temporarypassword]\r\n<br>".
                        "Once you log in, please change your password immediately. ";
                
                $to = "$row[email]";
                $subject = "$appname Message";
                $message = "$_SESSION[message]";
                $from = "donotreply@brax.me";
                $headers = "From: '$appname' <$from>\r\n";
                
                
                
                if($sig==''){
                    
                    echo " 
                    <br><br>
                    <center>
                    <img src='../img/logo.png' style='height:40px' /><br>
                    <h2>Your One-Time-Use Password is: $_SESSION[temporarypassword]
                    </h2>
                    <p>Once you log in, change your password immediately.
                    </p>
                    <script>
                    localStorage.pid = '$origproviderid';
                    localStorage.removeItem('swt');
                    </script>
                    <a href='$rootserver/$installfolder/login.php'>Login</a>
                     ";
                } else {
                    echo "
                    <!DOCTYPE html>    
                    <html>
                    <head>
                    <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=0, minumum-scale=1, maximum-scale=1'>
                    <meta name='apple-mobile-web-app-capable' content='yes'>
                    </head>
                    <body style='font-family:helvetica;padding:20px'>
                    <br><br>
                    <center>
                    <img src='../img/logo.png' style='height:40px' /><br>
                    <h2>
                    Your One-Time-Use Password is: $_SESSION[temporarypassword]
                    </h2>
                    <p>
                    </p>
                    <p>Restart the app and change your password immediately.
                    </p>
                    </body>
                     ";
                }
                    
                
    }
    else {
                    echo "Login ID not Found";
        
    }

?>