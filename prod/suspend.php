<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");


$suspendsms = tvalidator("PURIFY", $_POST[suspendsms]);
$suspendemail = tvalidator("PURIFY", $_POST[suspendemail]);
$providerid = tvalidator("PURIFY", $_POST[providerid]);

    if( $suspendsms == 'is')
    {
        $result = pdo_query("1",
            "select suspendsms from provider where providerid = $providerid ",null
        );
        $row = pdo_fetch($result);
        echo "$row[suspendsms]";
        exit();
    }
    if( $suspendemail == 'is')
    {
        $result = pdo_query("1",
            "select suspendemail from provider where  providerid = $providerid ",null
        );
        $row = pdo_fetch($result);
        echo "$row[suspendemail]";
        exit();
    }


    if( $suspendsms!='' )
    {
        $result = pdo_query("1",
            "update provider set suspendsms='$suspendsms' where providerid = $providerid ",null
        );
        echo "Suspend SMS= $suspendsms";
    }
    if( $suspendemail!='' )
    {
        $result = pdo_query("1",
            "update provider set suspendemail='$suspendemail' where providerid = $providerid ",null
        );
        echo "Suspend Email= $suspendemail";
    }
    
?>