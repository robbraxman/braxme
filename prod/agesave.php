<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

    $providerid = mysql_safe_string("$_SESSION[pid]");

    $age = mysql_safe_string("$_POST[age]");
    
    $_SESSION['age'] = $age;

    $result = do_mysqli_query("1", 
            " update provider set age=$age where providerid=$providerid "
            );
        
