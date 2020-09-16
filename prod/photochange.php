<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("PURIFY",$_SESSION[pid]);
    $filename = tvalidator("PURIFY",$_POST[filename]);
    $xaccode = tvalidator("PURIFY",$_POST[xaccode]);
    $value = tvalidator("PURIFY",$_POST[value]);
    $value2 = tvalidator("PURIFY",$_POST[value2]);
    $value3 = tvalidator("PURIFY",$_POST[value3]);

    if( $xaccode == 'T')
    {
        $result = pdo_query("1",
            "
                update photolib set title=?, comment=?, album=? 
                where providerid= ? and filename=?
            ",array($value,$value2,$value3,$providerid,$filename));
    }
    
?>