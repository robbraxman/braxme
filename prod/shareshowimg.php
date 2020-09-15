<?php
session_start();
include("config-pdo.php");

$share = @tvalidator("PURIFY", $_GET[p] );
$alias = @tvalidator("PURIFY", $_GET[a] );
$open = @tvalidator("PURIFY",$_GET[o]);

?>
<html>
    <head>
        <meta name='viewport' content='width=device-width, height=device-height, user-scalable=yes, initial-scale=1,maximum-scale=5'>
    </head>
    <body style='margin:0;padding:0'>
        <img src="<?=$rootserver?>/<?=$installfolder?>/sharedirect.php?a=<?=$alias?>" />
    </body>
</html>