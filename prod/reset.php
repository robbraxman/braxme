<?php
session_start();
require_once("config.php");

$providerid = tvalidator("PURIFY",$_POST['providerid']);

SaveLastFunction($providerid, "","");
    
?>
