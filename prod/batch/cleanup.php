<?php
session_start();
require_once("config-pdo.php");


$timestamp = time();


$result = pdo_query("1",

    "insert ignore into leads (name, email, phone, created ) ".
    "select providername, replyemail, replysms, now() from provider where ".
    "timestampdiff( day, createdate, now() ) > 2 and verified!='Y' "
);


$result = pdo_query("1",

    "delete from shares where shareexpire < now() "
);



$result = pdo_query("1",
    "delete from shares where views = 0 and datediff(now(), sharedate ) > 1 "
);


?>