<?php
session_start();
include("config.php");
include("validsession.inc.php");

$share = @mysql_safe_string( $_GET[p] );
$alias = @mysql_safe_string( $_GET[a] );
$open = @mysql_safe_string($_GET[o]);

?>
<html>
    <head>
        <meta name='viewport' content='width=device-width, height=device-height, user-scalable=yes, initial-scale=1,maximum-scale=5'>
    </head>
    <body style='margin:0;padding:0'>
        <img src="<?=$rootserver?>/<?=$installfolder?>/sharedirect.php?a=<?=$alias?>" />
    </body>
</html>