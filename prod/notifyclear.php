<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

$providerid = mysql_safe_string($_SESSION['pid']);

do_mysqli_query("1","update notification set notifyread='Y' where recipientid=$providerid and status='Y' and (notifyread is null or notifyread='') ")
    
?>
