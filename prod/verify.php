<?php
session_start();
require_once("config-pdo.php");
$verificationcode = @tvalidator("PURIFY",$_REQUEST['i']);



$result = pdo_query("1", 
      "select providerid, email from verification where verificationkey='$verificationcode' ",null
       );
if(!$row = pdo_fetch($result))
{
    exit();
}
$verifiedemail = $row['email'];

$result = pdo_query("1", 
      "update provider set verified='Y', verifiedemail=? where providerid in ".
      "(select providerid from verification where type='ACCOUNT' ".
      "and verificationkey= ? ) "
       ,array($verifiedemail,$verificationcode));
$result = pdo_query("1", 
      "update verification set verifieddate = now() 
       where verificationkey= ? and verifieddate is null "
       ,array($verificationcode));
     
require("htmlhead.inc.php");
?>
</head>
<BODY class="buycontainer" style="padding:40px">
    <div class='buysubcontainer' style='width:80%'>
        <a href='<?=$rootserver?>'>
         <img class="viewlogomsg" src="../img/logo.png" style="height:30px">
         </a>
    <div class='banner'>
        <b><?=$appname?> Account Verified</b>
    </div>
        <p><b>Thank You for Verifying Your Account.</b></p>
        <p>We will use this email address in the future to inform you of security issues and change of passwords.
        If you lose control of this email address, we recommend that you set up a new account and
        disable this one. Enjoy using the <?=$appname?>!</p>
</body>
</html>