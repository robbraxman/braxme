<?php
session_start();
require_once("config.php");
require ("SmsInterface.inc");

require_once("htmlhead.inc");
require_once("crypt.inc");

$sessionid = tvalidator("PURIFY","$_GET[sid]");
$language = tvalidator("PURIFY","$_GET[lang]");
$party = tvalidator("PURIFY","$_GET[party]");

require_once("language.inc");

?>
<script>
        $(document).ready( function() {
        });
</script>
<title></title>
</head>
    <body class="newmsgbody">
        <img class="viewlogomobile" src="../img/braxsecure.png">
            

<?php

        

    $result = do_mysqli_query("1", "SELECT active, announcement from service where msglevel='STATUS' ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        if($row[active]=='N')
        {
            echo "<br><br>$row[announcement]\n<br>";
            $_SESSION[status] = "N";
            exit();
        }
    
    }
    
    
    $result = do_mysqli_query("1", 
            "SELECT msgto.responsehash, msgto.encoding2, provider.providername, provider.providerid, msgto.recipientsms, msgto.recipientemail, msgto.recipientname, " .
            "msgmain.replysms, msgmain.replyemail, msgmain.sessionthread, msgmain.patientname, msgmain.patientmrno, msgto.recipientid, provider.allowkeydownload  " .
            "from msgto ".
            "left join msgmain on msgmain.sessionid = msgto.sessionid ".
            "left join provider on msgmain.providerid = provider.providerid " .
            "where msgmain.sessionid='$sessionid' and provider.active='Y' and msgto.party = $party" );
    
    //echo "Original Session ID=$_POST[sessionid]  ResponseText='$_POST[responsetext]'<br>";
    
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        $_SESSION[StdSmsMsg] = "";
        
       
        $SendtoPhone = $row[recipientsms];
        $ReplytoPhone =  $row[recipientsms];
        
        
        $result = do_mysqli_query("1", 
          "update provider set " .
          "msgcountin=msgcountin+1, " .
          "msgcountlife=msgcountlife+1 " .
          " where providerid=$row[providerid] " );

        $endingID = substr("$sessionid", -3,3);
       
        //echo "<br>SessionID=$_GET[sid]<br>";
        //echo "<br>EndingID=$endingID<br>";

       //         "Verification Key [ID ...$endingID]: Message from $row[providername] needs a key.\n\nYour Key is: $responsetext";
        $responsedecrypt = DecryptResponse( $row[responsehash], "$row[encoding2]", "$row[providerid]", "$row[recipientemail]" );

        //$responsedecrypt = base64_decode( $row[responsehash] );
        //$responsedecrypt = $encryptor->decrypt( $responsedecrypt, "$row[providerid]");

            $_SESSION[message] = "$keyrequestlong: $responsedecrypt\r\n\r\n".
                    "$messageid: ...$endingID\r\n";

            if( strlen($row[recipientsms]) > 5)
            {
                if( $row[allowkeydownload]!='N')
                {

                    $si = new SmsInterface (false, false);
                    $si->addMessage ( $row[recipientsms], $_SESSION["message"]);

                    if (!$si->connect (testaccount ,welcome1, true, false))
                        echo "failed. Could not contact server.\n";
                    elseif (!$si->sendMessages ()) {
                        echo "failed. Could not send message to server.\n";
                        if ($si->getResponseMessage () !== NULL)
                            echo "<BR>Reason: " . $si->getResponseMessage () . "\n";
                    } else
                        echo "<br>$getkeytextsuccess $row[recipientsms].\n";
                }
                else
                    echo "<br>$getkeytextfail<br>\n";
            }
            if( $row[recipientemail]!='' && $row[replyemail]!='')
            {
                if( $row[allowkeydownload]!='N')
                {
                    $_SESSION[message] = "$keyrequestlong: $responsedecrypt\r\n\r\n".
                            "$messageid: ...$endingID\r\n";

                    $to = "$row[recipientemail]";
                    $subject = "$keyrequestfrom $row[providername]";
                    $message = "$_SESSION[message]";
                    $from = "donotreply@brax.me";
                    $headers = "From: $row[providername] <donotreply@brax.me>";
                    mail($to,$subject,$message,$headers);
                    echo "<br>$getkeytextsuccess $row[recipientemail]\n";
                }
                else
                    echo "<br>$getkeytextfail<br>\n";
            }
            
        }


    //session_unset();
    //session_destroy();

?>
    </body>
    <script>                
<?php    echo "$.removeCookie('$row[providerid]', {path: '/'} );"; ?>
    </script>
</html>


