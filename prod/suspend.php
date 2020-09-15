<?php
session_start();
require("validsession.inc.php");
require_once("config.php");


$suspendsms = mysql_safe_string( $_POST[suspendsms]);
$suspendemail = mysql_safe_string( $_POST[suspendemail]);
$providerid = mysql_safe_string( $_POST[providerid]);

    if( $suspendsms == 'is')
    {
        $result = do_mysqli_query("1",
            "select suspendsms from provider where providerid = $providerid "
        );
        $row = do_mysqli_fetch("1",$result);
        echo "$row[suspendsms]";
        exit();
    }
    if( $suspendemail == 'is')
    {
        $result = do_mysqli_query("1",
            "select suspendemail from provider where  providerid = $providerid "
        );
        $row = do_mysqli_fetch("1",$result);
        echo "$row[suspendemail]";
        exit();
    }


    if( $suspendsms!='' )
    {
        $result = do_mysqli_query("1",
            "update provider set suspendsms='$suspendsms' where providerid = $providerid "
        );
        echo "Suspend SMS= $suspendsms";
    }
    if( $suspendemail!='' )
    {
        $result = do_mysqli_query("1",
            "update provider set suspendemail='$suspendemail' where providerid = $providerid "
        );
        echo "Suspend Email= $suspendemail";
    }
    
?>