<?php
session_start();
include("config-pdo.php");
require("aws.php");

$share = @tvalidator("PURIFY", $_GET['p'] );
$alias = @tvalidator("PURIFY", $_GET['a'] );
$open = @tvalidator("PURIFY",$_GET['o']);

header("Content-Type: application/octet-stream");

if( $alias == ''){

    $result = pdo_query("1","
            select filename, filetype, folder, title, comment, views, likes from photolib where filename=? and (providerid=$_SESSION[pid] or providerid=0 )
            ",array($share));
} else {
    
    $result = pdo_query("1","
            select filename, filetype, folder, title, comment, views, likes from photolib where alias=? and providerid=$_SESSION[pid] 
            ",array($alias));
}
if( !$row = pdo_fetch($result)){

    header("Content-Disposition: filename='expired.jpg'");

    $filename = "$rootserver/img/expired.jpg";


    if ($fd = fopen ($filename, "rb")) {

        fpassthru($fd);
        fclose( $fd);
        exit();
    }
}

$filename = "$rootserver/$installfolder/$row[folder]$row[filename]";

$download_filename = $appname."."."$row[filetype]";

//Ubuntu Touch - Can't get permission to auto-download so pop in browser
if($_SESSION['mobiledevice']=='U'){
    $awsurl = getAWSObjectUrlShortTerm( $row['filename']);

    header('Location: '.$awsurl);
    exit();
}


header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$download_filename");
header("Cache-control: private;no-cache"); //prevent proxy caching

echo getAwsObject($row['filename']);

/*
if ($fd = fopen ($filename, "rb")) {

    fpassthru($fd);
    fclose( $fd);
    exit();
}
*/
