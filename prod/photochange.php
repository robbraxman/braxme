<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = mysql_safe_string($_SESSION[pid]);
    $filename = mysql_safe_string($_POST[filename]);
    $xaccode = mysql_safe_string($_POST[xaccode]);
    $value = mysql_safe_string($_POST[value]);
    $value2 = mysql_safe_string($_POST[value2]);
    $value3 = mysql_safe_string($_POST[value3]);

    if( $xaccode == 'T')
    {
        $result = do_mysqli_query("1",
            "
                update photolib set title='$value', comment='$value2', album='$value3' 
                where providerid= $providerid and filename='$filename'
            ");
    }
    
?>