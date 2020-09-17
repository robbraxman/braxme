<?php
require_once("config-pdo.php");
require_once("phpmailer/PHPMailerAutoload.php");
    
    function SendMailNotification($defaultsmtp, $msgtitle, $message, $messagealt, $sendername, $senderemail, $recipientname, $recipientemail )
    {
        global $app_smtp_host;
        global $app_smtp_port;
        global $app_smtp_username;
        global $app_smtp_secure;
        global $app_smtp_password;
        global $app_smtp_email;
        global $app_smtp_mailname;
        global $appname;
        
        if(strstr($recipientemail, ".account@brax.me")!==false){
            return true;
        }
        
        $mail  = new PHPMailer();
    
        $mail->IsSMTP(); // telling the class to use SMTP
        
        $mail->Host       = "$app_smtp_host";
        $mail->Port       = $app_smtp_port;
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->Username   = $app_smtp_username;
        $mail->Password   = $app_smtp_password;
        $mail->SMTPSecure = $app_smtp_secure;
        $mail->SetFrom("$app_smtp_email","$appname Notification");        
        
        
        $mail->CharSet = 'UTF-8';        
        $mail->AddReplyTo("$senderemail","$sendername");


        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                                   // 1 = errors and messages
                                                   // 2 = messages only
        $mail->Subject    = "$msgtitle";
        $mail->AltBody    = strip_tags($messagealt) ;
        $mail->Body = $message;
        //$mail->MsgHTML    = $message;
        $mail->isHTML( true );
        
        //echo "<br>$message<br>";
        //echo "<br>ALT<br>$messagealt<br>";
        
        //echo "$recipientemail***$recipientname";

        $mail->AddAddress("$recipientemail", "$recipientname" );
        $mail->AddCC("$senderemail", "$sendername" );




        if(!$mail->Send()) {
          echo "<br>Mailer Error: " . $mail->ErrorInfo;
          return false;
        } else {
          //echo "<br>Message sent successfully<br>";
          return true;
        }
    
    }    
    
    function SendMailV2($defaultsmtp, $msgtitle, $message, $messagealt, $sendername, $senderemail, $recipientname, $recipientemail )
    {
        global $app_smtp_host;
        global $app_smtp_port;
        global $app_smtp_username;
        global $app_smtp_secure;
        global $app_smtp_password;
        global $app_smtp_email;
        global $app_smtp_mailname;
        
        if(strstr($recipientemail, ".account@brax.me")!==false){
            return true;
        }
        
        $mail  = new PHPMailer();
    
        $mail->IsSMTP(); // telling the class to use SMTP
        
        if( $defaultsmtp == false)
        {
            $mail->Host       = "$app_smtp_host";
            $mail->Port       = $app_smtp_port;
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Username   = $app_smtp_username;
            $mail->Password   = $app_smtp_password;
            $mail->SMTPSecure = $app_smtp_secure;
            $mail->SetFrom("$app_smtp_email","$app_smtp_mailname");        
        }
        else   
        {
            $mail->Host       = $_SESSION[smtp_host];
            $mail->Port       = $_SESSION[smtp_port];
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Username   = $_SESSION[smtp_username];
            $mail->Password   = $_SESSION[smtp_password];
            $mail->SMTPSecure = "ssl";
            $mail->SetFrom( $_SESSION[smtp_email], $_SESSION[smtp_mailname] );
     
        }
        
        
        $mail->CharSet = 'UTF-8';        
        $mail->AddReplyTo("$senderemail","$sendername");


        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                                   // 1 = errors and messages
                                                   // 2 = messages only
        $mail->Subject    = "$msgtitle";
        $mail->AltBody    = strip_tags($messagealt) ;
        $mail->Body = $message;
        //$mail->MsgHTML    = $message;
        $mail->isHTML( true );
        
        //echo "<br>$message<br>";
        //echo "<br>ALT<br>$messagealt<br>";
        
        //echo "$recipientemail***$recipientname";

        $mail->AddAddress("$recipientemail", "$recipientname" );
        $mail->AddCC("$senderemail", "$sendername" );




        if(!$mail->Send()) {
          echo "<br>Mailer Error: " . $mail->ErrorInfo;
          return false;
        } else {
          //echo "<br>Message sent successfully<br>";
          return true;
        }
    
    }    
    
    function SendMail($defaultsmtp, $msgtitle, $message, $messagealt, $recipientname, $recipientemail )
    {
        global $app_smtp_host;
        global $app_smtp_port;
        global $app_smtp_username;
        global $app_smtp_secure;
        global $app_smtp_password;
        global $app_smtp_email;
        global $app_smtp_mailname;
        
        if(strstr($recipientemail, ".account@brax.me")!==false){
            return true;
        }
        
        $mail  = new PHPMailer();
    
        $mail->IsSMTP(); // telling the class to use SMTP
        
        if( $defaultsmtp == false)
        {
            $mail->Host       = "$app_smtp_host";
            $mail->Port       = $app_smtp_port;
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Username   = $app_smtp_username;
            $mail->Password   = $app_smtp_password;
            $mail->SMTPSecure = $app_smtp_secure;
            $mail->SetFrom("$app_smtp_email","$app_smtp_mailname");        
        }
        else   
        {
            $mail->Host       = $_SESSION[smtp_host];
            $mail->Port       = $_SESSION[smtp_port];
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Username   = $_SESSION[smtp_username];
            $mail->Password   = $_SESSION[smtp_password];
            $mail->SMTPSecure = "ssl";
            $mail->SetFrom( $_SESSION[smtp_email], $_SESSION[smtp_mailname] );
     
        }
        
        
        $mail->CharSet = 'UTF-8';        
        //$mail->AddReplyTo("sales@braxsecure.com","BraxSecure Accounts");


        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                                   // 1 = errors and messages
                                                   // 2 = messages only
        $mail->Subject    = "$msgtitle";
        $mail->AltBody    = strip_tags($messagealt) ;
        $mail->Body = $message;
        //$mail->MsgHTML    = $message;
        $mail->isHTML( true );
        
        //echo "<br>$message<br>";
        //echo "<br>ALT<br>$messagealt<br>";
        
        //echo "$recipientemail***$recipientname";

        $mail->AddAddress("$recipientemail", "$recipientname" );




        if(!$mail->Send()) {
          echo "<br>Mailer Error: " . $mail->ErrorInfo;
          return false;
        } else {
          //echo "<br>Message sent successfully<br>";
          return true;
        }
    
    }    
    
    function EmailHandler
            (
            $sendername, $senderaddress, 
            $messageurl,
            $messageurl_mobile,
            $recipientname, $recipientemail, $from, $defaultsmtp,
            $msgtitle
            )
    {
        global $rootserver;
        global $homepage;
        global $installfolder;
        global $rootserver;
    
        
        $message = 
                "<html>".
                "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />".
                "<body>".
                "<table style='width: 500px; border-color: black; border: 1px; background-color: white; color: black;margin:20px'><tr><td>".
                "<a href=#espanol>Español</a><br>".
                "<div style='font-size: 15px; padding-top: 10px; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; background-color: steelblue; color: white;'><b>$providername</b> sent you a secure message.</div>" .
                "<img src='$_SESSION[avatarurl]' style='height: 150px; width: auto'><br>".
                "<b>$sendername\n$senderaddress</b><br>\n\n" .
                "<div style='font-size: 12px; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px; background-color: steelblue; color: white;'>".
                "</div>".
                "<b>Open your Secure Message on a</b><br><br>\n".
                "<a href='$messageurl' style='text-decoration:none' target=_blank >" .
                "<div style='display:inline;background-color:firebrick;color:white;padding:10px;border-radius:20px;'>Personal Computer</div>".
                "</a>&nbsp;&nbsp;\n".
                //"<button style='padding: 10px;'>Personal Computer</button></a>&nbsp;&nbsp;\n".
                "<a href='$messageurl_mobile' style='text-decoration:none' target=_blank >" .
                "<div style='display:inline;background-color:firebrick;color:white;padding:10px;border-radius:20px;'>Mobile Phone</div>".
                //"<button style='padding: 10px;'>Mobile Phone</button></a>".
                "</a>\n\n<br><br>" .
                "Having a problem with the buttons above? To read this message, try clicking this link instead or pasting the URL to your browser<br>".
                "<a href='$messageurl' target=_blank >$messageurl</a>".
                "<br><br>\n\n".
                "This message may contain information subject to HIPAA and Financial Privacy Laws\n" .
                "or deemed private by the sender. This type of message can only be viewed on a secure\n" .
                "Internet connection.".

                "<br><br>\n\nPlease contact the sender through alternative means to verify the legitimacy of this \r\n" .
                "secure message if you wish.".

                "<br><br>\n\n".
                "Please do not Reply to this Message. This email address is not monitored. You will be able to reply \n".
                "when you view the messages above. Please add sender to your address book so it does not go to your \n". 
                "spam folder. Thank you.<br><br>\n\n".
                "<div style='font-size: 12px; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px; background-color: steelblue; color: white;'>".
                "</div><a name=espanol></a>".


                 "<a href='$messageurl&lang=spanish'>Ver Mensaje Seguro</a>".

                "<br><br>\n\n".
                "<i>".
                "Este mensaje puede contener información sometida a la ley HIPAA y las leyes de privacidad \n".
                "financiera o considerada privada por el remitente. Este tipo de mensaje sólo puede ser visto \n".
                "utilizando una conexión segura a Internet. ".

                "<br><br>\n\n".
                "Póngase en contacto con el emisor a través de medios alternativos para verificar la legitimidad \n".
                "de este mensaje seguro, si así lo desea.".                

                "<br><br>\n\n".
                "Por favor, no responda a este mensaje. Usted será capaz de responder cuando ve el mensaje anterior. \n".
                "Por favor, añada remitente a la libreta de direcciones para que no se te suba a la carpeta de spam. \nGracias. \n\n".               
                "</i>".


                "<br><br>".
                "<div style='font-size: 12px; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; background-color: steelblue; color: white;'>".
                "Sent from <b>$appname Secure Messaging App</b> \n".
                "<i>Available on iTunes App Store and Google Play</i><br>\n".
                "</div>".
                "$homepage<br>".
                "<img src='$rootserver/img/lock.png' style='height: 20px; width: auto'><br>".
                "</td></tr></table>".
                "</body></html>";
        
        
        if( $defaultsmtp == 1)
        {
                $message =
                "<html>".
                "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />".
                "<body>".
                "<table style='width: 500px; border-color: black; border: 1px; background-color: white; color: black;margin:20px'><tr><td>".
                "<div style='font-size: 15px; padding-top: 10px; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; background-color: steelblue; color: white;'>You have a secure message.</div>" .
                "<img src='$_SESSION[avatarurl]' style='height: 150px; width: auto'><br>".
                "<b>$sendername\n$senderaddress</b><br>" .
                "<div style='font-size: 12px; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px; background-color: steelblue; color: white;'>".
                "</div>".
                "<b>Open your Secure Message on a</b><br><br>".
                "<a href='$messageurl' style='text-decoration:none' target=_blank >" .
                "<div style='display:inline;background-color:firebrick;color:white;padding:10px;border-radius:20px;'>Personal Computer</div>".
                "</a>&nbsp;&nbsp;".
                //"<button style='padding: 10px;'>Personal Computer</button></a>&nbsp;&nbsp;\n".
                "<a href='$messageurl_mobile' style='text-decoration:none' target=_blank >" .
                "<div style='display:inline;background-color:firebrick;color:white;padding:10px;border-radius:20px;'>Mobile Phone</div>".
                //"<button style='padding: 10px;'>Mobile Phone</button></a>".
                "</a>\n\n<br><br>" .
                "Having a problem with the buttons above? To read this message, try clicking this link instead or pasting the URL to your browser<br>".
                "<a href='$messageurl' target=_blank >$messageurl</a>".
                "<br><br>".
                "This message may contain information subject to HIPAA and Financial Privacy Laws" .
                "or deemed private by the sender. This type of message can only be viewed on a secure " .
                "internet connection.".


                "<br><br>".
                "Please do not Reply to this message directly. The reply will not be secure. You will be able to reply ".
                "when you view the message above. Thank you.<br><br>".

                "<div style='font-size: 12px; padding-top: 2px; padding-bottom: 2px; padding-left: 10px; padding-right: 10px; background-color: steelblue; color: white;'>".
                "</div>".

                "<br><br>".
                "<div style='font-size: 12px; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; background-color: steelblue; color: white;'>".
                "Sent from <b>$appname Secure Messaging App</b> ".
                "<i>Available on iTunes App Store and Google Play</i><br>".
                "</div>".
                "$homepage<br>".
                "<img src='$rootserver/img/lock.png' style='height: 20px; width: auto'><br>".
                "</td></tr></table>".
                "</body></html>";
                    
        }

        
        
        
        $subject = "=?utf-8?q?$msgtitle?=";                        
        $headers = "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $sendername <$from>\r\n";
        
        $messagealt = strip_tags($message);
        
        return  SendMail($defaultsmtp, $msgtitle, $message, $messagealt, $recipientname, $recipientemail );
        
        
        $mailstatus = mail( $recipientemail, $subject, $message, $headers);
        
        if( $mailstatus )
            return true;
        else 
        {
            return false;
        }

    }
    
    
    
?>