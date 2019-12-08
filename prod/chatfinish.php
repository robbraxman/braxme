<?php
session_start();
require_once("config.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = mysql_safe_string($_POST['providerid']);
    $chatid = mysql_safe_string($_POST['chatid']);
    $archive = mysql_safe_string($_POST['archive']);

    SaveLastFunction($providerid,"", "");
    
    if( $chatid == ""){
        exit();
    }
    //echo "Deleting $chatid";

    if( $chatid == 1217 ){
        exit();
    }
    
    if( $archive !== 'Y')
    {
        $result = do_mysqli_query("1",
            "
            delete from chatmembers where chatid=$chatid 
            ");

        $result = do_mysqli_query("1",
            "
            delete from chatmessage where chatid=$chatid 
            ");

        $result = do_mysqli_query("1",
            "
            delete from chatmaster where chatid=$chatid 
            ");
    }
    if( $archive === 'Y')
    {
        $result = do_mysqli_query("1",
            "
            update chatmessage set status='N' where chatid=$chatid 
            ");
        $result = do_mysqli_query("1",
            "
            update chatmaster set status='N' where chatid=$chatid 
            ");

    }
?>