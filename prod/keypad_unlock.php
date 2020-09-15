<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

do_mysqli_query("1","update provider set pinlock = '' where providerid = $_SESSION[pid]");

?>
