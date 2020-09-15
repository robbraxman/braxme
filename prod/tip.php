<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

$providerid = $_SESSION['pid'];
$lasttip = intval($_SESSION['lasttip']);
$devicetype = @tvalidator("PURIFY",$_POST['devicetype']);
$deviceplatform = @tvalidator("PURIFY",$_POST['deviceplatform']);

$nexttip = intval($lasttip)+1;

SaveLastTip( "$nexttip",$providerid);


function SaveLastTip($tip, $providerid)
{
    do_mysqli_query("1","update provider set lasttip=$tip where providerid=$providerid");
}

?>
