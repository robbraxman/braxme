<?php
session_start();
require_once("config.php");

$providerid = tvalidator("PURIFY",$_POST[providerid]);
$loginid = tvalidator("PURIFY",$_POST[loginid]);

do_mysqli_query("1","update provider set lastactive=now() where providerid=$providerid")
    
?>
