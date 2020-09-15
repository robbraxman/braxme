<?php
session_start();
require("validsession.inc.php");
require("config.php");
$timezonewords = mysql_safe_string($_GET['timezone']);
$timezonenum = substr( $timezonewords, 3, 3 );
$_SESSION['timezone'] = mysql_safe_string($GET['timezone']);// - $_SESSION[servertimezone];

?>