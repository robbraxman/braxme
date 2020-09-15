<?php
session_start();
require_once("config.php");
require_once("sendmail.php");

$_SESSION[returnurl]="<a href='login.php'>Login</a>";
$temp = rand ( 100000 , 999999 );
$_SESSION['temporarypassword'] = $temp;

$pid = tvalidator("PURIFY",$_POST['pid']);
$providerid = tvalidator("PURIFY", "$_POST[pid]");
$loginid = tvalidator("PURIFY",$_POST['loginid']);

    //Validation Checks
    if( $pid == ""  || !is_numeric($pid) )
    {
        echo "Invalid Subscriber";
        exit();
    }
    if( $loginid == "" )
    {
        echo "Invalid Login";
        exit();
    }

    
    $result = do_mysqli_query("1", 
            "SELECT email from staff where providerid = $providerid and loginid = '$loginid'  "
            );
    
    if ($row = do_mysqli_fetch("1",$result)) 
    {
            if( $row[email] == '')
            {
                echo "This user has no email address assigned. The Administrator needs to assign it";
                exit();
                
            }
            
            $pwd_hash = password_hash("$_SESSION[temporarypassword]",PASSWORD_DEFAULT);
            $result = do_mysqli_query("1",
                    "update staff set 
                     pwd_ver = 3,
                     pwd_hash = '$pwd_hash',
                     fails = 0,
                     where providerid = $providerid and loginid = '$loginid'
                    "
                );
            
            //echo "password:$_SESSION[temporarypassword]/$row[email]";
                
                $_SESSION[message] = 
                        /*
                        "We're SORRY. Were you not able to Login after signing up?\r\n\r\n ".
                        "We just noticed a glitch in the Sign Up Security which may have ".
                        "interrupted your service. We will extend your FREE TRIAL for another 10 days ".
                        "for bearing with us. Please give it another try.\r\n\r\n".
                        "Best regards,\r\n".
                        "Tech Support\r\n\r\n".
                         * 
                         */
                         
                         
                        "Your $appname Password has been reset.\r\n" .
                        "Your Temporary Password is: $_SESSION[temporarypassword]\r\n".
                        "Once you log in successfully, change your password to maintain security. ";
                
                $to = "$row[email]";
                $subject = "$appname Message";
                $message = "$_SESSION[message]";
                $from = "memberaccounts@brax.me";
                $headers = "From: '$appname' <$from>\r\n";
                
                SendMail("0", "$subject", "$message", "$message", "$to", "$to" );
                
    }
    else {
                    echo "Login ID not Found";
        
    }

?>