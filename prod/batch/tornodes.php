<?php
session_start();
set_time_limit ( 30 );
require_once("config.php");

$tornodes = file_get_contents("https://check.torproject.org/exit-addresses");
$torexits = file_put_contents("/var/tmp/torexitnodes",$tornodes);
 

?>