<?php
if(!isset($_SESSION['validsession'])){
    require_once("config-pdo.php");
    $ip2 = '';
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip2 = tvalidator("PURIFY","$_SERVER[HTTP_X_FORWARDED_FOR]");
    }
    $ip = tvalidator("PURIFY",$_SERVER['REMOTE_ADDR']);
    pdo_query("1"," 
        INSERT INTO attacker (ip, ip2, accessdate, accesscount ) values
        (?,?,now(), 0 )
            
        ",array($ip,$ip2));
    pdo_query("1"," 
        UPDATE attacker  set accesscount = accesscount+1, accessdate=now() where 
        ip=? and ip2=?
        ",array($ip,$ip2));
    include("../error404.php");
    //header('This is not the page you are looking for', true, 404);
    exit();
}