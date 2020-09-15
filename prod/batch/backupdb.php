<?php
session_start();
require_once("config-pdo.php");


date_default_timezone_set('America/Los_Angeles');
$today = getdate();
$filename = "$_SESSION[backupname]-backup-$today[weekday]-$today[hours].sql";

//Set permission to /sqlbackup to 775

exec( "mysqldump ".
       " -u$_SESSION[sqlusr] -p$_SESSION[sqlpwd]  $_SESSION[database] --result-file=".$filename 
        );

?>