<?php
session_start();
require_once("config.php");



    //Validation Checks
    if( $_POST[email] == ""  )
    {
        echo "Missing Email";
        exit();
    }
    $email = tvalidator("PURIFY", $_POST[email]);
    
    $result = do_mysqli_query("1", 
            "insert ignore into emaillist (email, source) values ('$email', 'MOBILE' ) "
            );
    
                
    $_SESSION[message] = 
        "<html><body>".
        "<b>Thank You downloading the Mobile App.</b><br><br>\r\n\r\n".
        "Due to limitations at Google Play, we are not able to allow registration from the app itself.<br>\r\n".
        "Please visit our home page and learn how to set up a FREE Trial account.<br>\r\n".
        "https://www.braxsecure.com<br><br>\r\n\r\n".
        "<i>Available on iTunes App Store and Google Play</i><br>\r\n".
        "<img src='https://brax.me/img/braxmobile.png' style='height: 100px; width: auto'><br>".
        "</body></html>";

    $to = "$email";
    $subject = "BraxSecure Info";
    $message = "$_SESSION[message]";
    $from = "sales@brax.me";
    $headers = "From: 'Brax.Me' <$from>\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $mailstatus = mail( $to, $subject, $message, $headers);
    if( $mailstatus )
    {
        echo "Email Sent to $email with Instructions for Account Setup.";
    }
    else 
    {
        echo "Email Failed for $email.";
    }
    
    
    $_SESSION[message] = 
        "<html><body>".
        "<b>$email - Requested Info.</b><br><br>\r\n\r\n".
        "<i>Available on iTunes App Store and Google Play</i><br>\r\n".
        "<img src='$rootserver/img/braxmobile.png' style='height: 100px; width: auto'><br>".
        "</body></html>";

    $to = "sales@brax.me";
    $subject = "Brax.Me Email Lead";
    $message = "$_SESSION[message]";
    $from = "sales@brax.me";
    $headers = "From: 'Brax.Me' <$from>\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $mailstatus = mail( $to, $subject, $message, $headers);

?>
