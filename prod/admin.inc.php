<?php
require_once("config-pdo.php");

    if($_SESSION['admin']!='Y'){
    
            echo "<html><title>Login Message</title>";
            echo "<head><link rel='styleSheet' href='$rootserver/$installfolder/local.css' type='text/css'></head>";
            echo "<body class='appbody'>";
            echo "<br>Login ID $_SESSION[loginid] does not have Administrator Rights $_SESSION[admin] $_SESSION[pid]<br>";
            echo "$_SESSION[returnurl]";
            echo "<br>";
            echo "</body></html>";
            echo "</body></html>";
            exit();
    } 

?>

