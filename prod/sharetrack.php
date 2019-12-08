<?php 
session_start();
include("config.php");

$uniqid = uniqid('R');
$providerid = mysql_safe_string( $_POST['providerid'] );
$roomid = mysql_safe_string( $_POST['roomid'] );
$result = do_mysqli_query("1",
        "insert into shares (providerid, sharetype, shareid, sharelocal, sharetitle, 
            shareopentitle,
            shareto, sharedate, shareexpire, securetype, platform, views, likes, 
            setid, proxyfilename,
            collection, roomid ) values (
            $providerid, 'R', '$uniqid', '', '','','Facebook',now(), now(), 
            'C','Facebook',0,0,'','','', $roomid
            )"
        );

