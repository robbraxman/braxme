<?php
if(!isset($_SESSION['validsession'])){
    require_once("config.php");
    $ip2 = '';
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip2 = mysql_safe_string("$_SERVER[HTTP_X_FORWARDED_FOR]");
    }
    $ip = mysql_safe_string($_SERVER['REMOTE_ADDR']);
    do_mysqli_query("1"," 
        INSERT INTO attacker (ip, ip2, accessdate, accesscount ) values
        ('$ip','$ip2',now(), 0 )
            
        ");
    do_mysqli_query("1"," 
        UPDATE attacker  set accesscount = accesscount+1, accessdate=now() where 
        ip='$ip' and ip2='$ip2'
        ");
    include("../error404.php");
    //header('This is not the page you are looking for', true, 404);
    exit();
}