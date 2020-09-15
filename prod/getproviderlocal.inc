<?php
//session_start();
require_once("config-pdo.php");

$result = pdo_query("1", "select newmsgurl from provider where providerid = $_SESSION[pid] ");
$row = pdo_fetch($result);
$_SESSION['newmsgurl'] = $row['newmsgurl'];

if( $_SESSION['newmsgurl']=='')
    $_SESSION['newmsgurl']='newmsg_v1.0.php';

?>
