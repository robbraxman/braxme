<?php
require_once("config.php");
$randomid = uniqid();


echo "<!DOCTYPE html>\r\n";
echo "<html>\r\n";
echo "<head>\r\n";
echo "<meta charset='utf-8'>";

echo "<META HTTP-EQUIV='Pragma' CONTENT='private'>";
//echo "<META HTTP-EQUIV='Expires' CONTENT='-1'>";
echo "<meta name='description' content='Secure Communications App for your community - No Eavesdropping'>";
echo "<meta property='og:title' content='Brax.Me - Secure Group Blog and Chat' />";
echo "<meta property='og:url' content='$rootserver/index.php' />";
echo "<meta property='og:image' content='$rootserver/img/bigstock-woman-using-mobile-phone.jpg' />";        
echo "<meta name='rating' content='14 years'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0, maximum-scale=1'>";
echo "<meta name='apple-mobile-web-app-capable' content='yes'>";
echo "<meta name='mobile-web-app-capable' content='yes'>";
echo "<link rel='apple-touch-startup-image' href='$rootserver/img/lock2.png'>";
    
echo "<link rel='stylesheet' href='$rootserver/libs/alertify.js-0.3.10/themes/alertify.core.css' />\r\n";
echo "<link rel='stylesheet' href='$rootserver/libs/alertify.js-0.3.10/themes/alertify.default.css' />\r\n";
echo "<script src='$rootserver/libs/alertify.js-0.3.10/src/alertify.js'></script>\r\n";

echo "<script src='passwordcheck.js'></script>\r\n";
echo "<script src='$rootserver/libs/alertify.js-0.3.10/src/alertify.js'></script>\r\n";

echo "<link rel='stylesheet' href='https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css'>";
echo "<script src='https://code.jquery.com/jquery-1.11.1.min.js'></script>";

echo "<script src='https://code.jquery.com/ui/1.11.1/jquery-ui.js'></script>";

echo "<link rel='icon' href='$rootserver/img/lock2.png'>";
echo "<link id=favicon rel='shortcut icon' href='$rootserver/img/favicon.ico'>";

echo "<link rel='apple-touch-icon' href='$rootserver/img/lock2.png'>";
echo "<link rel='apple-touch-icon-precomposed' href='$rootserver/img/lock2.png'>";
echo "<link rel='apple-touch-startup-image' href='$rootserver/img/lock2.png' />";
echo "<link rel='styleSheet' href='$rootserver/$installfolder/app.css' type='text/css'/>\r\n";
//echo "<link rel='styleSheet' href='$rootserver/$installfolder/app.css?$randomid' type='text/css'/>\r\n";
echo "\r\n";
echo "\r\n";
echo "<script src='$rootserver/libs/jquery-cookie-master/jquery.cookie.js'></script>\r\n";
echo "<script type='text/javascript' src='base64v1_0.js'></script>\r\n";
echo "<script type='text/javascript' src='autolinker.js'></script>\r\n";
echo "<script type='text/javascript' src='analytics.js'></script>\r\n";
echo "<script type='text/javascript' src='$rootserver/libs/jquery-touch-swipe/jquery.touchSwipe.min.js'></script>\r\n";

?>
 
