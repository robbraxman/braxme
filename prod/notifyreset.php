<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

$providerid = tvalidator("PURIFY",$_SESSION['pid']);

    $result = do_mysqli_query("1",
        "
        update alertrefresh set lastnotified = null where providerid=$providerid and deviceid = '$_SESSION[deviceid]'
        ");
    
?>
