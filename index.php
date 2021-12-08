<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();    
/* INSTALLFOLDER IS HARDCODED HERE */
require_once("prod/config.php");
$s = '';
$gcm = '';
$apn = '';
$uuid = '';
$l = '';
$e = '';
$a = "";
$h = "";
$store = "";
$v = "";
$action = "";

$l = @mysql_safe_string($_GET['l']);
$e = @mysql_safe_string($_GET['e']);
$s = @mysql_safe_string($_GET['s']);
$gcm = @mysql_safe_string($_GET['gcm']);
$apn = @mysql_safe_string($_GET['apn']);
$a = @mysql_safe_string($_GET['a']);
$h = @mysql_safe_string($_GET['h']);
$v = @mysql_safe_string($_GET['v']);
$store = @mysql_safe_string($_GET['store']);
$uuid = "";
if($a=='logout'){

    $action="<script>localStorage.pid = '';</script>";
}


if($s==''){

    $source="?s=nonmobile&h=$h";
    
} else {
    
    if($v == ''){
        $v = "000";
    }
    if($gcm!=''){
        $source="?s=$s&gcm=$gcm&h=$h&store=$store&v=";
    } else  
    if($apn!=''){
        $source="?s=$s&apn=$apn&h=$h&store=$store&v=";
    } else {
        $source="?s=$s&h=$h&store=$store&v=";
    }
    
}
$login = "login.php".$source;

?>
<html>
  <head>
    <title>Brax.Me</title>
    <META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
    <META HTTP-EQUIV='Expires' CONTENT='-1'>
    <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=0, maximum-scale=1'>
    <link rel='icon' href='https://brax.me/img/favicon.ico' type='image/x-icon'>
    <link rel='shortcut icon' href='https://brax.me/img/favicon.ico' type='image/x-icon'>
    <link rel='apple-touch-icon' href='https://brax.me/img/lock2.png'>
    <link rel='stylesheet' href='$rootserver/libs/jquery-1.11.1/jquery-ui.css'>
    <script src='<?=$rootserver?>/libs/jquery-1.11.1/jquery.min.js' ></script>
    <script src='<?=$rootserver?>/libs/jquery-1.11.1/jquery-ui.js' ></script>
    <meta name="description" content="Brax.Me - Private Communities">     
    <?=$action?>
  </head>
  <body style='background-color:whitesmoke;color:black'>
      Loading...
      <!--
      <a href=''>Start </a>
      -->
    <script>
        var rootserver1 = '<?=$rootserver?>';
        var login = '<?=$source?>';
        
        if('<?=$v?>'!=='000' && '<?=$v?>'!==''){
            localStorage.mobileversion = '<?=$v?>';
            //alert(localStorage.mobileversion);
        }
        vlocation =  rootserver1+"/prod/login.php"+login+localStorage.mobileversion;
        //alert(vlocation);
        window.location = vlocation;
    </script>
  </body>
</html>
