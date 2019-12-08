<?php
session_start();
require_once("config.php");
require_once("localsettings/secure/cryptofunc2.inc.php");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
$encoding = GenerateNewEncoding();
BackupKeys();
VerifyKeys();

echo "New Encoding Generated $encoding";


        
