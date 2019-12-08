<?php
session_start();
require_once("validsession.inc.php");
require_once("config.php");


//$providerid = mysql_safe_string("$_POST[providerid]");
$providerid = @$_SESSION[pid];
$contactid = mysql_safe_string( "$_POST[contactid]" );



    $result = do_mysqli_query("1","
            select providername, handle, replyemail from provider where 
            active='Y' and providerid = $contactid
            ");
    if($row = do_mysqli_fetch("1",$result)){
        do_mysqli_query("1","
        insert ignore into contacts 
        (providerid, handle, contactname, email, sms, targetproviderid, source, createdate, friend, blocked ) values
        ($providerid, '$row[handle]','$row[providername]','','', $contactid, 'C', now(),'Y','' )
        ");
        
    } else {
        exit();
    }
    
        

