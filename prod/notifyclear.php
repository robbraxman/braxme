<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

$providerid = tvalidator("ID",$_SESSION['pid']);

pdo_query("1","update notification set notifyread='Y' where recipientid=? and status='Y' and (notifyread is null or notifyread='') ",array($providerid))
    
?>
