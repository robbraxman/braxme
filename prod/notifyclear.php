<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

$providerid = tvalidator("ID",$_SESSION['pid']);

pdo_query("1","update provider set homenotified = now()  where providerid=?  ",array($providerid))
    
?>
