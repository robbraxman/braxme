<?php
session_start();
require_once("config.php");

$providerid = mysql_safe_string($_POST[providerid]);
$loginid = mysql_safe_string($_POST[loginid]);

do_mysqli_query("1","update provider set lastactive=now() where providerid=$providerid")
    
?>
