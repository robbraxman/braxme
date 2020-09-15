<?php
session_start();
require_once("config-pdo.php");

$providerid = tvalidator("PURIFY",$_POST[providerid]);
$loginid = tvalidator("PURIFY",$_POST[loginid]);

pdo_query("1","update provider set lastactive=now() where providerid=$providerid")
    
?>
