<?php
require_once("config.php");
$randomid = uniqid();

echo "<!DOCTYPE html>\r\n";
echo "<html>\r\n";
echo "<head>\r\n";
echo "<meta charset='utf-8'>";
echo "<meta name='description' content='Building Private Communities'>";
echo "<meta property='og:title' content='Brax.Me - Building Private Communities' />";
echo "<meta property='og:url' content='$rootserver/index.php' />";
echo "<meta property='og:image' content='$rootserver/img/bigstock-woman-using-mobile-phone.jpg' />";        
echo "<meta name='viewport' content='width=device-width, height='device-height user-scalable=no initial-scale=1'>";
echo "<meta name='rating' content='14 years'>";



//echo "<META HTTP-EQUIV='Pragma' CONTENT='no-cache'>";
echo "<META HTTP-EQUIV='Expires' CONTENT='-1'>";
echo "<link rel='styleSheet' href='$rootserver/$installfolder/app.css?$randomid' type='text/css'/>\r\n";

echo "<link rel='apple-touch-startup-image' href='$rootserver/img/logo-b1a.png'>";
echo "<link rel='icon' href='$rootserver/img/logo-b1a.png'>";
echo "<link id=favicon rel='shortcut icon' href='$rootserver/img/logo-b1a.ico'>";
echo "<link rel='apple-touch-icon' href='$rootserver/img/logo-b1a.png'>";
echo "<link rel='apple-touch-icon-precomposed' href='$rootserver/img/logo-b1a.png'>";
echo "<link rel='apple-touch-startup-image' href='$rootserver/img/logo-b1a.png' />";



echo "\r\n";
echo "\r\n";
echo "<link rel='stylesheet' href='$rootserver/libs/alertify.js-0.3.10/themes/alertify.core.css' />\r\n";
echo "<link rel='stylesheet' href='$rootserver/libs/alertify.js-0.3.10/themes/alertify.default.css' />\r\n";
echo "<script src='$rootserver/libs/alertify.js-0.3.10/src/alertify.js'></script>\r\n";

echo "<link rel='stylesheet' href='$rootserver/libs/jquery-1.11.1/jquery-ui.css'>";
echo "<script src='$rootserver/libs/jquery-1.11.1/jquery.min.js' ></script>";
echo "<script src='$rootserver/libs/jquery-1.11.1/jquery-ui.js' ></script>";


echo "<script src='$rootserver/libs/ckeditor/ckeditor.js?$randomid'></script>";
echo "<script src='$rootserver/libs/ckeditor/adapters/jquery.js?$randomid'></script>";

echo "<script type='text/javascript' src='$rootserver/libs/fastclick/fastclick.js'></script>\r\n";

echo "<script src='$rootserver/libs/jquery-cookie-master/jquery.cookie.js'></script>\r\n";
echo "<script src='passwordcheck.js?$randomid'></script>\r\n";
echo "<script type='text/javascript' src='$rootserver/libs/jquery-touch-swipe/jquery.touchSwipe.min.js'></script>\r\n";

echo "\r\n";
echo "\r\n";
echo "</head>\r\n";
?>
