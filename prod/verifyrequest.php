<?php
session_start();
require_once("config-pdo.php");
require ("sendmail.php");

$providerid = @tvalidator("ID",$_SESSION['pid']);
if($providerid == ''){
    exit();
}
    $result = pdo_query("1","
                select replyemail, providername from provider where 
                providerid = ? and 
                active='Y' ",array($providerid));
    
    if ($row = pdo_fetch($result)) {
        SendSignUpEmail( $providerid, $row['providername'], $row['replyemail'] );
        echo "Sent Email";
    }
    exit();
    
    function SendSignUpEmail( $providerid, $providername, $replyemail )
    {
        global $appname;
        global $rootserver;
        global $installfolder;
        
        $signupverificationkey = uniqid("", true);
        pdo_query("1", 
                "insert into verification (type, providerid, verificationkey, loginid, email, createdate ) values (".
                " 'ACCOUNT', ?, ?, 'admin', ?, now() ) "
                ,array($providerid,$signupverificationkey,$replyemail));
        
        $message = 
                "<html><body>".
                "<br><br><b>Thank You for using $appname.</b><br><br>".
                "We need to verify your identity and your control of this email address.<br>".
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

        
        SendMail("0", "Brax.Me Verification", "$message", "$messagealt", "$providername", "$replyemail" );
                
    }
