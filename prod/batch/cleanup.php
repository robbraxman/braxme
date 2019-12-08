<?php
session_start();
require_once("config.php");


$timestamp = time();


$result = do_mysqli_query("1",

    "insert ignore into leads (name, email, phone, created ) ".
    "select providername, replyemail, replysms, now() from provider where ".
    "timestampdiff( day, createdate, now() ) > 2 and verified!='Y' "
);


$result = do_mysqli_query("1",

    "delete from shares where shareexpire < now() "
);



$result = do_mysqli_query("1",
    "delete from shares where views = 0 and datediff(now(), sharedate ) > 1 "
);


?>