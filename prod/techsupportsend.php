<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("sendmail.php");

require("htmlhead.inc.php");   


function StatusMessage( $status, $level )
{
    //Level 1 OK
    //Level 2 Warning
    //Level 3 Severe - DIE
    //Level 4 Suspend Exit
    $truelevel = $level;
    if( $level == 4)
        $truelevel = 3;
    echo "<div class='statusmessage$truelevel'>";
    echo "$status";
    echo "</div>";
    if( $level == 3 )
        exit();
}



?>
<title>Send Message</title>
</head>
<body class="newmsgbody">
    <div class='statustitle'>Message Status</div>
        
<?php



    //$_SESSION[encoding] = "HASH";
    //$_SESSION[encoding] = "BASE64";
    //$_SESSION[encoding] = "PLAIN";
    $_SESSION[status] = "Y";

    $errorstate=false;
      if( $_POST[recipientname] == "" )
       {
            StatusMessage( "No Recipient Name", 3);
            //$errorstate = true;
       }
        
      if( $_POST[recipientemail] == "" )
       {
            StatusMessage( "No Recipient Email", 3);
            //$errorstate = true;
       }
       if( $errorstate == true)
       {
            ReturnToMessageEntry();
            //session_unset();
            //session_destroy();
            exit();
       }
       
        if( $_POST[recipientemail]!='' )
        {
            $recipientemail = stripslashes(tvalidator("EMAIL",$_POST[recipientemail]));
            $recipientname = stripslashes(tvalidator("PURIFY",$_POST[recipientname]));
            $message = stripslashes(tvalidator("PURIFY",$_POST[message]));


            $to = "techsupport01@brax.me";
            $subject = "Tech Support Question";
            $message = "Subscriber: $recipientname $recipientemail<br><br>$message";
            $from = "alerts@brax.me";
            //$headers = "From: '$recipientname' <$recipientemail>\r\n";
            //$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            //$mailstatus = mail( $to, $subject, $message, $headers);
            $mailstatus = SendMail("0", "$subject", "$message", "$message", "Tech Support", "$to" );
            if( $mailstatus )
                StatusMessage( "Email sent to Tech Support", 1);
            else 
            {
                StatusMessage( "Email to Tech Support FAILED", 4);
            }
            

            $to = "$recipientemail";
            $subject = "Your Tech Support Question";
            $message2 = "<html><body>We have recieved your Inquiry! You are very important to us. We will respond to you as soon as possible.<br><br><b>BraxSecure Tech Support Team</b></body></html>";
            $from = "alerts@brax.me";
            $headers = "From: 'Tech Support' <techsupport@brax.me>\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            //$mailstatus = mail( $to, $subject, $message2, $headers);
            
            $mailstatus = SendMail("0", "$subject", "$message2", "$message2", "$recipientname", "$recipientemail" );

        }

    
    
    ReturnToMessageEntry();

    //session_unset();
    //session_destroy();

?>

<?php

function ReturnToMessageEntry()
{
        //echo "<br><a href='https://www.braxsecure.com' >Home</a>";
    
}


?>

<?php require("htmlfoot.inc");?>