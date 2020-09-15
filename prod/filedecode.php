<?php
//session_start();
//require("validsession.inc.php");
require_once("config.php");
require_once("crypt.inc");

    $url = @$_GET['u'];
    if($url == ''){
        exit();
    }
    $ext = @$_GET['e'];
    if($ext == ''){
        exit();
    }

    //if(substr(strtolower($url),0,4)!='http' ){
    //    $url = "http://".$url;
    //}
    

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: filename='file.$ext'");
    
   
    
    StreamDecode("$url");
