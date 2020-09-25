<?php
session_start();
require_once("config-pdo.php");
require ("password.inc.php");
//require("htmlhead.inc.php");   

$sessionid = tvalidator("PURIFY", $_POST[sessionid]);
$party = tvalidator("PURIFY", $_POST[party]);

$result = pdo_query("1", 
      "delete from msgto where sessionid = ? and party=? ",array($sessionid,$party)
);
$result = pdo_query("1", 
        //Message is no longer used by any
      "delete from msgmain where sessionid not in (select sessionid from msgto where msgto.sessionid = msgmain.sessionid ) and sessionid ='$sessionid' ",null
);


//echo "$row[providerid]";,
echo "<div class=statusmessage1>Message $sessionid Deleted</div>";
//echo "<br><a href='javascript:window.close()' >Close</a>";
     
?>