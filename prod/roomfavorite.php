<?php 
session_start();
require("validsession.inc.php");
include("config.php");

/* This Routine is for performing automated processes after doing termsofuse agree */


$mode = @mysql_safe_string( $_POST['mode'] );
$providerid = @mysql_safe_string( $_SESSION['pid'] );
$roomid = @mysql_safe_string( $_POST['roomid'] );

if($mode == 'A'){
    do_mysqli_query("1","
        insert into roomfavorites (providerid, roomid ) values ($providerid, $roomid )
             ");
}
if($mode == 'D'){
    do_mysqli_query("1","
        delete from roomfavorites where providerid = $providerid and roomid = $roomid
             ");
}
