<?php
session_start();
require_once("config.php");

$providerid = mysql_safe_string($_POST['providerid']);

SaveLastFunction($providerid, "","");
    
?>
