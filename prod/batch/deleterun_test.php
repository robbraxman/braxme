<?php
session_start();
set_time_limit ( 30 );
require_once("config.php");
require_once("aws.php");


$timestamp = time();




$result = do_mysqli_query("1",
"
    select filename from iotphotos where datediff(now(), createdate) > 1 and status='Y'
");
while( $row = do_mysqli_fetch("1",$result))
{
    echo "Deleting ".$row['filename']."<br>";
    //  delete from chatmessage where chatid = $row[chatid]
    deleteAWSObject( $row['filename'] );
    $result2 = do_mysqli_query("1",
    "
        update iotphotos set status='N' where filename='$row[filename]'    
    ");
    
}
$result = do_mysqli_query("1",
"
    delete from iotdata where datediff(now(), msgdate) > 1 
");



?>