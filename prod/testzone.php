<?php
session_start();
require("validsession.inc.php");
require_once("config.php");
require_once("crypt.inc.php");
require("password.inc.php");
if( $_SESSION['superadmin']!='Y'){
    exit();
}

$date = date('Y_m_d_hisa', time());

$df = disk_free_space("/");

?>
<html>
    <head>
        <title>Test Zone</title>
        <meta charset='utf-8'>
    </head>
<body style='font-family:helvetica;font-size:13px;'>
    testzone.php
    
<?php



?>
</body>
</html>
