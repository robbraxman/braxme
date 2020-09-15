<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

$url = @tvalidator("PURIFY",$_GET['u']);
if(substr(strtolower($url),0,4)!='http' ){
    $url = "http://".$url;
}

header("Content-Type: application/octet-stream");
header("Content-Disposition: filename='$url'");

echo file_get_contents("$url");
exit();

