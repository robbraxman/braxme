<?php
//session_start();
require_once("config.php");

$result = do_mysqli_query("1", "select newmsgurl from provider where providerid = $_SESSION[pid] ");
$row = do_mysqli_fetch("1",$result);
$_SESSION['newmsgurl'] = $row['newmsgurl'];

if( $_SESSION['newmsgurl']=='')
    $_SESSION['newmsgurl']='newmsg_v1.0.php';

?>
