<?php
session_start();
include("config-pdo.php");
require("aws.php");

/*
 * Sharebase.php is used to show links to images inside Javascript code. Since this is exposed, the only
 * identifiers visible is the SHAREID. It doesn't use filenames.
 * Sharedirect.php uses filenames
 */

$share = tvalidator("PURIFY", $_GET['p'] );
$n = "";
if(isset($_GET['n'])) {
    $n = tvalidator("PURIFY", "$_GET[n]" );
}


$result = pdo_query("1","
        select sharelocal, providerid from shares where shareid =  
        ",array($share));

if( !$row = pdo_fetch($result))
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
$sharelocal = tvalidator("PURIFY",$row['sharelocal']);
$providerid = $row['providerid'];


if( $n== "")
{
    $result = pdo_query("1","
            select filename, folder from photolib where filename= ?
            ",array($sharelocal));
}
if( $n!= "")
{
    $result = pdo_query("1","
            select filename, folder  from photolib where album = ? and providerid=?
            order by filename desc limit $n, 1
            ",array($sharelocal,$providerid));
}

if( !$row = pdo_fetch($result))
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