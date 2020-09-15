<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

    $providerid = tvalidator("PURIFY","$_SESSION[pid]");

    $age = tvalidator("PURIFY","$_POST[age]");
    
    $_SESSION['age'] = $age;

    $result = pdo_query("1", 
            " update provider set age=? where providerid=? "
            ,array($age,$providerid));
        
