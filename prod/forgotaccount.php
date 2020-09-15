<?php
session_start();
require_once("config.php");

$_SESSION[returnurl]="<a href='login.php'>Login</a>";

$email = mysql_safe_string($_POST['email']);
$name = strtolower(mysql_safe_string( "$_POST[name]"));

    //Validation Checks
    if( $email == "" )
    {
        echo "Invalid Email";
        exit();
    }

    
    $result = do_mysqli_query("1", 
            "SELECT staff.providerid, staff.loginid, staff.adminright, staff.email, ".
            " provider.verifiedemail from staff ".
            "left join provider on staff.providerid = provider.providerid ".
            " where staff.email ='$email' and provider.providername = '$name' ".
            " and staff.adminright = 'Y' ".
            "order by staff.adminright desc  "
            );
    
    
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        if( $row[adminright]!='Y')
        {
            echo "This email address is not the Administrator of the Account.<br>";
            echo "This action is not allowed.";
            exit();
        }
                
                $_SESSION[message] = 
                        "Your $appname Subscriber Account is: $row[providerid].\r\n" .
                        "Your Login ID is: $row[loginid]\r\n";
                
                $to = "$row[email]";
                $subject = "$appname Message";
                $message = "$_SESSION[message]";
                $from = "donotreply@brax.me";
                $headers = "From: '$appname' <$from>\r\n";
                $mailstatus = mail( $to, $subject, $message, $headers);
                if( $mailstatus )
                {
                    echo "<br><h3>Email Sent to $row[email] with Account Info.</h3>";
                        
                }
                else 
                {
                    echo "<br>Email Failed for $row[email].";
                }
                
                if( $row[email]!=$row[verifiedemail] && $row[verifiedemail]!='')
                {
                
                    $_SESSION[message] = 
                            "Account Verification Requested by $row[email]\r\n".
                            "You are being alerted because the email address of the administrator ".
                            "had changed from when the account was established. ".
                            "Your $appname Subscriber Account is: $row[providerid].\r\n" .
                            "Your Login ID is: $row[loginid]\r\n";

                    $to = "$row[verifiedemail]";
                    $subject = "$appname Message";
                    $message = "$_SESSION[message]";
                    $from = "donotreply@brax.me";
                    $headers = "From: '$appname' <$from>\r\n";
                    $mailstatus = mail( $to, $subject, $message, $headers);
                    if( $mailstatus )
                    {
                        echo "<br><h3>Courtesy Alert Email Sent to $row[verifiedemail].</h3>";

                    }
                    else 
                    {
                        echo "<br>Email Failed for $row[email].";
                    }
                    echo   "<br><br><a href=$homepage>Home</a>";
                }
                
    }
    else 
    {
                    echo "Email not found or was not an Administrator";
        
    }

?>