<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$timezonewords = tvalidator("PURIFY",$_GET['timezone']);
$timezonenum = substr( $timezonewords, 3, 3 );
$_SESSION['timezone'] = tvalidator("PURIFY",$GET['timezone']);// - $_SESSION[servertimezone];

?>