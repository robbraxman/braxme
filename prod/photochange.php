<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("PURIFY",$_SESSION[pid]);
    $filename = tvalidator("PURIFY",$_POST[filename]);
    $xaccode = tvalidator("PURIFY",$_POST[xaccode]);
    $value = tvalidator("PURIFY",$_POST[value]);
    $value2 = tvalidator("PURIFY",$_POST[value2]);
    $value3 = tvalidator("PURIFY",$_POST[value3]);

    if( $xaccode == 'T')
    {
        $result = do_mysqli_query("1",
            "
                update photolib set title='$value', comment='$value2', album='$value3' 
                where providerid= $providerid and filename='$filename'
            ");
    }
    
?>