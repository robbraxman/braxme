<?php
session_start();
include("config.php");
require("aws.php");

/*
 * Sharebase.php is used to show links to images inside Javascript code. Since this is exposed, the only
 * identifiers visible is the SHAREID. It doesn't use filenames.
 * Sharedirect.php uses filenames
 */

$share = mysql_safe_string( $_GET['p'] );
$n = "";
if(isset($_GET['n'])) {
    $n = mysql_safe_string( "$_GET[n]" );
}


$result = do_mysqli_query("1","
        select sharelocal, providerid from shares where shareid = '$share' 
        ");

if( !$row = do_mysqli_fetch("1",$result))
{
    $filename = "../img/lock.png";
    if ($fd = fopen ($filename, "rb"))
    {
        set_time_limit(0);
        fpassthru( $fd );
        fclose( $fd);
    }
    exit();
}
$sharelocal = mysql_safe_string($row['sharelocal']);
$providerid = $row['providerid'];


if( $n== "")
{
    $result = do_mysqli_query("1","
            select filename, folder from photolib where filename= '$sharelocal'
            ");
}
if( $n!= "")
{
    $result = do_mysqli_query("1","
            select filename, folder  from photolib where album = '$sharelocal' and providerid=$providerid
            order by filename desc limit $n, 1
            ");
}

if( !$row = do_mysqli_fetch("1",$result))
{
    $filename = "../img/lock.png";
    if ($fd = fopen ($filename, "rb"))
    {
        set_time_limit(0);
        fpassthru( $fd );
        fclose( $fd);
    }
    
    exit();
}

header("Content-Type: application/octet-stream");
header("Cache-control: private;no-cache"); //prevent proxy caching

echo getAWSObject("$row[filename]");

exit();


/*
$filename = "$row[folder]$row[filename]";

if ($fd = fopen ($filename, "rb"))
{
    set_time_limit(0);
    fpassthru( $fd );
    fclose( $fd);
}
else
{
    $filename = "../img/lock.png";
    if ($fd = fopen ($filename, "rb"))
    {
        set_time_limit(0);
        fpassthru( $fd );
        fclose( $fd);
    }
    exit();
    
}
 * 
 */
exit();